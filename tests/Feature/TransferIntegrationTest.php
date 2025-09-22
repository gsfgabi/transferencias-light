<?php

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use App\Livewire\Transfer\TransferFormComponent;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

test('complete transfer flow', function () {
    // Mock HTTP responses
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Autorizado'], 200),
        'util.devi.tools/api/v1/notify' => Http::response(['message' => 'Enviado'], 200),
    ]);

    // Create users with wallets
    $sender = User::factory()->common()->create([
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'document' => '11144477735'
    ]);
    
    $payee = User::factory()->common()->create([
        'name' => 'Maria Santos',
        'email' => 'maria@example.com',
        'document' => '22255588899'
    ]);

    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    // Authenticate the sender
    $this->actingAs($sender);

    // Test the Livewire component
    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'maria@example.com')
        ->set('form.amount', 25.00)
        ->call('transfer');

    // Assert success
    $component->assertSee(__('messages.success.transfer_completed'));

    // Check database state
    $sender->refresh();
    $payee->refresh();

    expect($sender->balance)->toBe(75.00);
    expect($payee->balance)->toBe(75.00);

    // Check transaction was recorded
    $transaction = Transaction::where('sender_id', $sender->id)
        ->where('payee_id', $payee->id)
        ->first();

    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toEqual(25.0);
    expect($transaction->status)->toBe('completed');
    expect($transaction->error_message)->toBeNull();
});

test('transfer validation errors', function () {
    $sender = User::factory()->common()->create([
        'email' => 'joao@example.com'
    ]);

    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);

    // Authenticate the sender
    $this->actingAs($sender);

    // Test invalid email
    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'invalid-email')
        ->set('form.amount', 25.00)
        ->call('transfer');

    $component->assertHasErrors(['form.payee_email']);

    // Test insufficient amount
    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'maria@example.com')
        ->set('form.amount', 0)
        ->call('transfer');

    $component->assertHasErrors(['form.amount']);

    // Test transferring to self
    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'joao@example.com')
        ->set('form.amount', 25.00)
        ->call('transfer');

    $component->assertHasErrors(['form.payee_email']);
});

test('merchant cannot transfer', function () {
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Autorizado'], 200),
    ]);

    // Criar um merchant com o email que o TransferForm espera
    $merchant = User::factory()->merchant()->create([
        'email' => 'joao@example.com'
    ]);
    
    $payee = User::factory()->common()->create([
        'email' => 'maria@example.com'
    ]);

    Wallet::factory()->create(['user_id' => $merchant->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    // Authenticate the merchant
    $this->actingAs($merchant);

    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'maria@example.com')
        ->set('form.amount', 25.00)
        ->call('confirmTransfer');

    $component->assertSee('Lojistas não podem realizar transferências.');
});

test('authorization service failure', function () {
    // Temporariamente mudar o ambiente para não simular autorização
    $originalEnv = app()->environment();
    app()->instance('env', 'production');
    
    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Não autorizado'], 200),
    ]);

    $sender = User::factory()->common()->create([
        'email' => 'joao@example.com'
    ]);
    
    $payee = User::factory()->common()->create([
        'email' => 'maria@example.com'
    ]);

    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    // Authenticate the sender
    $this->actingAs($sender);

    $component = Livewire::test(TransferFormComponent::class)
        ->set('form.payee_email', 'maria@example.com')
        ->set('form.amount', 25.00)
        ->call('transfer');

    $component->assertSee('Transferência não autorizada pelo serviço externo.');
    
    // Restaurar o ambiente original
    app()->instance('env', $originalEnv);

    // Check that balances weren't changed
    $sender->refresh();
    $payee->refresh();

    expect($sender->balance)->toBe(100.00);
    expect($payee->balance)->toBe(50.00);
});

test('dashboard displays correct information', function () {
    // Create some test data
    $users = User::factory()->count(5)->create();
    $merchants = User::factory()->merchant()->count(3)->create();

    foreach ($users->concat($merchants) as $user) {
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
    }

    // Create some transactions
    Transaction::factory()->count(10)->create();

    // Authenticate a user to access dashboard
    $user = $users->first();
    
    // Create role and permission if they don't exist
    $role = Role::firstOrCreate(['name' => 'common-user']);
    $permission = Permission::firstOrCreate(['name' => 'dashboard.view']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);
    
    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('8'); // Total users (5 + 3)
    $response->assertSee('5'); // Common users
    $response->assertSee('3'); // Merchants
});