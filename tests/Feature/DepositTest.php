<?php

use App\Models\User;
use App\Models\Wallet;
use App\Livewire\Deposit\DepositFormComponent;
use Livewire\Livewire;

test('deposit works correctly', function () {
    // Criar um usuário comum
    $user = User::factory()->common()->create();
    
    // Criar uma carteira para o usuário
    Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);

    // Autenticar o usuário
    $this->actingAs($user);

    // Testar o componente de depósito
    $component = Livewire::test(DepositFormComponent::class)
        ->set('form.amount', 50.00)
        ->call('deposit');

    // Verificar se o depósito foi bem-sucedido
    $component->assertSee('Depósito realizado com sucesso!');
    
    // Verificar se o saldo foi atualizado
    $user->refresh();
    expect($user->balance)->toBe(150.00);
});

test('admin cannot deposit', function () {
    // Criar um usuário admin
    $admin = User::factory()->admin()->create();
    
    // Criar uma carteira para o admin
    Wallet::factory()->create(['user_id' => $admin->id, 'balance' => 100.00]);

    // Autenticar o admin
    $this->actingAs($admin);

    // Testar o componente de depósito
    $component = Livewire::test(DepositFormComponent::class)
        ->set('form.amount', 50.00)
        ->call('deposit');

    // Verificar se o depósito foi bloqueado
    $component->assertSee('Administradores não podem realizar depósitos.');
    
    // Verificar se o saldo não foi alterado
    $admin->refresh();
    expect($admin->balance)->toBe(100.00);
});

test('transfer works correctly', function () {
    // Criar usuários
    $sender = User::factory()->common()->create();
    $payee = User::factory()->common()->create();
    
    // Criar carteiras
    Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100.00]);
    Wallet::factory()->create(['user_id' => $payee->id, 'balance' => 50.00]);

    // Autenticar o remetente
    $this->actingAs($sender);

    // Testar o componente de transferência
    $component = Livewire::test(\App\Livewire\Transfer\TransferFormComponent::class)
        ->set('form.payee_email', $payee->email)
        ->set('form.amount', 25.00)
        ->call('transfer');

    // Verificar se a transferência foi bem-sucedida
    $component->assertSee('Transferência realizada com sucesso!');
    
    // Verificar se os saldos foram atualizados
    $sender->refresh();
    $payee->refresh();
    expect($sender->balance)->toBe(75.00);
    expect($payee->balance)->toBe(75.00);
});
