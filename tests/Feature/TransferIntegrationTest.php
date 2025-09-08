<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use App\Livewire\TransferForm;

class TransferIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_transfer_flow()
    {
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

        // Test the Livewire component
        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'maria@example.com')
            ->set('amount', 25.00)
            ->call('transfer');

        // Assert success
        $component->assertSee('Transferência realizada com sucesso!');

        // Check database state
        $sender->refresh();
        $payee->refresh();

        $this->assertEquals(75.00, $sender->balance);
        $this->assertEquals(75.00, $payee->balance);

        // Check transaction was recorded
        $transaction = Transaction::where('sender_id', $sender->id)
            ->where('payee_id', $payee->id)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(25.00, $transaction->amount);
        $this->assertEquals('completed', $transaction->status);
        $this->assertNull($transaction->error_message);
    }

    public function test_transfer_validation_errors()
    {
        $sender = User::factory()->common()->create([
            'email' => 'joao@example.com'
        ]);

        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);

        // Test invalid email
        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'invalid-email')
            ->set('amount', 25.00)
            ->call('transfer');

        $component->assertHasErrors(['payee_email']);

        // Test insufficient amount
        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'maria@example.com')
            ->set('amount', 0)
            ->call('transfer');

        $component->assertHasErrors(['amount']);

        // Test transferring to self
        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'joao@example.com')
            ->set('amount', 25.00)
            ->call('transfer');

        $component->assertHasErrors(['payee_email']);
    }

    public function test_merchant_cannot_transfer()
    {
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

        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'maria@example.com')
            ->set('amount', 25.00)
            ->call('transfer');

        $component->assertSee('Lojistas não podem realizar transferências.');
    }

    public function test_authorization_service_failure()
    {
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

        $component = Livewire::test(TransferForm::class)
            ->set('payee_email', 'maria@example.com')
            ->set('amount', 25.00)
            ->call('transfer');

        $component->assertSee('Transferência não autorizada pelo serviço externo.');

        // Check that balances weren't changed
        $sender->refresh();
        $payee->refresh();

        $this->assertEquals(100.00, $sender->balance);
        $this->assertEquals(50.00, $payee->balance);
    }

    public function test_dashboard_displays_correct_information()
    {
        // Create some test data
        $users = User::factory()->count(5)->create();
        $merchants = User::factory()->merchant()->count(3)->create();

        foreach ($users->concat($merchants) as $user) {
            Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
        }

        // Create some transactions
        Transaction::factory()->count(10)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('8'); // Total users (5 + 3)
        $response->assertSee('5'); // Common users
        $response->assertSee('3'); // Merchants
    }
}

