<?php

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->transferService = new TransferService();
});

test('successful transfer between common users', function () {
    // Mock HTTP responses
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Autorizado'], 200),
        'util.devi.tools/api/v1/notify' => Http::response(['message' => 'Enviado'], 200),
    ]);

    // Create users with wallets
    $sender = User::factory()->common()->create();
    $payee = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    $result = $this->transferService->processTransfer($sender, $payee, 25.00);

    expect($result['success'])->toBeTrue();
    expect($result['message'])->toBe('Transferência realizada com sucesso!');

    // Check balances
    $sender->refresh();
    $payee->refresh();
    expect($sender->balance)->toBe(75.00);
    expect($payee->balance)->toBe(75.00);

    // Check transaction record
    $transaction = Transaction::where('sender_id', $sender->id)
        ->where('payee_id', $payee->id)
        ->first();
    
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toEqual(25.0);
    expect($transaction->status)->toBe('completed');
});

test('merchant cannot send money', function () {
    $sender = User::factory()->merchant()->create();
    $payee = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    $result = $this->transferService->processTransfer($sender, $payee, 25.00);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Lojistas não podem realizar transferências.');
});

test('insufficient balance transfer', function () {
    $sender = User::factory()->common()->create();
    $payee = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 10.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    $result = $this->transferService->processTransfer($sender, $payee, 25.00);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Saldo insuficiente para realizar a transferência.');
});

test('cannot transfer to self', function () {
    $user = User::factory()->common()->create();
    Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);

    $result = $this->transferService->processTransfer($user, $user, 25.00);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Não é possível transferir para si mesmo.');
});

test('authorization service failure', function () {
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Não autorizado'], 200),
    ]);

    $sender = User::factory()->common()->create();
    $payee = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    $result = $this->transferService->processTransfer($sender, $payee, 25.00);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Transferência não autorizada pelo serviço externo.');
});

test('authorization service unavailable', function () {
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([], 500),
    ]);

    $sender = User::factory()->common()->create();
    $payee = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    $result = $this->transferService->processTransfer($sender, $payee, 25.00);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Serviço de autorização indisponível.');
});

test('get user transaction history', function () {
    $user = User::factory()->common()->create();
    $otherUser = User::factory()->common()->create();
    
    Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $otherUser->id, 'balance' => 50.00]);

    // Create some transactions
    Transaction::factory()->create([
        'sender_id' => $user->id,
        'payee_id' => $otherUser->id,
        'amount' => 25.00,
        'status' => 'completed'
    ]);

    Transaction::factory()->create([
        'sender_id' => $otherUser->id,
        'payee_id' => $user->id,
        'amount' => 15.00,
        'status' => 'completed'
    ]);

    $history = $this->transferService->getUserTransactionHistory($user);

    expect($history['sent'])->toHaveCount(1);
    expect($history['received'])->toHaveCount(1);
    expect($history['total_sent'])->toEqual(25.0);
    expect($history['total_received'])->toEqual(15.0);
});