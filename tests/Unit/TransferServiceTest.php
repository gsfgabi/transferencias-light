<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransferServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TransferService $transferService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferService = new TransferService();
    }

    public function test_successful_transfer_between_common_users()
    {
        // Mock HTTP responses
        Http::fake([
            'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Autorizado'], 200),
            'util.devi.tools/api/v1/notify' => Http::response(['message' => 'Enviado'], 200),
        ]);

        // Create users with wallets
        $sender = User::factory()->create(['type' => 'common']);
        $payee = User::factory()->create(['type' => 'common']);
        
        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
        Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

        $result = $this->transferService->processTransfer($sender, $payee, 25.00);

        $this->assertTrue($result['success']);
        $this->assertEquals('Transferência realizada com sucesso!', $result['message']);

        // Check balances
        $sender->refresh();
        $payee->refresh();
        $this->assertEquals(75.00, $sender->balance);
        $this->assertEquals(75.00, $payee->balance);

        // Check transaction record
        $transaction = Transaction::where('sender_id', $sender->id)
            ->where('payee_id', $payee->id)
            ->first();
        
        $this->assertNotNull($transaction);
        $this->assertEquals(25.00, $transaction->amount);
        $this->assertEquals('completed', $transaction->status);
    }

    public function test_merchant_cannot_send_money()
    {
        $sender = User::factory()->create(['type' => 'merchant']);
        $payee = User::factory()->create(['type' => 'common']);
        
        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
        Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

        $result = $this->transferService->processTransfer($sender, $payee, 25.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Lojistas não podem realizar transferências.', $result['message']);
    }

    public function test_insufficient_balance_transfer()
    {
        $sender = User::factory()->create(['type' => 'common']);
        $payee = User::factory()->create(['type' => 'common']);
        
        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 10.00]);
        Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

        $result = $this->transferService->processTransfer($sender, $payee, 25.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Saldo insuficiente para realizar a transferência.', $result['message']);
    }

    public function test_cannot_transfer_to_self()
    {
        $user = User::factory()->create(['type' => 'common']);
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);

        $result = $this->transferService->processTransfer($user, $user, 25.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Não é possível transferir para si mesmo.', $result['message']);
    }

    public function test_authorization_service_failure()
    {
        Http::fake([
            'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Não autorizado'], 200),
        ]);

        $sender = User::factory()->create(['type' => 'common']);
        $payee = User::factory()->create(['type' => 'common']);
        
        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
        Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

        $result = $this->transferService->processTransfer($sender, $payee, 25.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Transferência não autorizada pelo serviço externo.', $result['message']);
    }

    public function test_authorization_service_unavailable()
    {
        Http::fake([
            'util.devi.tools/api/v2/authorize' => Http::response([], 500),
        ]);

        $sender = User::factory()->create(['type' => 'common']);
        $payee = User::factory()->create(['type' => 'common']);
        
        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
        Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

        $result = $this->transferService->processTransfer($sender, $payee, 25.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Serviço de autorização indisponível.', $result['message']);
    }


    public function test_get_user_transaction_history()
    {
        $user = User::factory()->create(['type' => 'common']);
        $otherUser = User::factory()->create(['type' => 'common']);
        
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

        $this->assertCount(1, $history['sent']);
        $this->assertCount(1, $history['received']);
        $this->assertEquals(25.00, $history['total_sent']);
        $this->assertEquals(15.00, $history['total_received']);
    }
}
