<?php

use App\Livewire\Transfer\TransferFormComponent;
use App\Models\User;
use App\Models\Wallet;
use Livewire\Livewire;

test('transfer component can be instantiated', function () {
    $user = User::factory()->create();
    Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
    
    $this->actingAs($user);
    
    $component = Livewire::test(TransferFormComponent::class);
    
    $component->assertStatus(200);
});

test('transfer form has correct properties', function () {
    $user = User::factory()->create();
    Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
    
    $this->actingAs($user);
    
    $component = Livewire::test(TransferFormComponent::class);
    
    // Verificar se o componente tem a propriedade form
    $this->assertObjectHasProperty('form', $component->instance());
});
