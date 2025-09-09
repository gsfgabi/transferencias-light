<?php

use App\Models\User;
use App\Models\Wallet;

test('user can be created', function () {
    $user = User::factory()->common()->create([
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'document' => '12345678901',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'joao@example.com',
        'document' => '12345678901',
    ]);
});

test('user has wallet', function () {
    $user = User::factory()->create();
    $wallet = Wallet::factory()->create(['user_id' => $user->id]);

    expect($user->wallet)->toBeInstanceOf(Wallet::class);
    expect($user->wallet->user_id)->toBe($user->id);
});

test('user can send money if common', function () {
    $user = User::factory()->common()->create();
    
    expect($user->canSendMoney())->toBeTrue();
});

test('user cannot send money if merchant', function () {
    $user = User::factory()->merchant()->create();
    
    expect($user->canSendMoney())->toBeFalse();
});

test('user can receive money', function () {
    $commonUser = User::factory()->common()->create();
    $merchantUser = User::factory()->merchant()->create();
    
    expect($commonUser->canReceiveMoney())->toBeTrue();
    expect($merchantUser->canReceiveMoney())->toBeTrue();
});

test('user balance accessor', function () {
    $user = User::factory()->create();
    $wallet = Wallet::factory()->create([
        'user_id' => $user->id,
        'balance' => 100.50
    ]);

    expect($user->balance)->toBe(100.50);
});

test('user balance accessor returns zero if no wallet', function () {
    $user = User::factory()->create();
    
    expect($user->balance)->toBe(0.0);
});

test('document cleaning', function () {
    expect(User::cleanDocument('123.456.789-01'))->toBe('12345678901');
    expect(User::cleanDocument('12.345.678/0001-95'))->toBe('12345678000195');
});

test('cpf validation', function () {
    expect(User::isValidCpf('11144477735'))->toBeTrue();
    expect(User::isValidCpf('11111111111'))->toBeFalse();
    expect(User::isValidCpf('12345678901'))->toBeFalse();
});

test('cnpj validation', function () {
    expect(User::isValidCnpj('11222333000181'))->toBeTrue();
    expect(User::isValidCnpj('11111111111111'))->toBeFalse();
    expect(User::isValidCnpj('12345678901234'))->toBeFalse();
});

test('document validation', function () {
    expect(User::isValidDocument('11144477735'))->toBeTrue(); // Valid CPF
    expect(User::isValidDocument('11222333000181'))->toBeTrue(); // Valid CNPJ
    expect(User::isValidDocument('11111111111'))->toBeFalse(); // Invalid CPF
    expect(User::isValidDocument('123456789'))->toBeFalse(); // Invalid length
});

test('formatted document accessor', function () {
    $user = User::factory()->create(['document' => '11144477735']);
    expect($user->formatted_document)->toBe('111.444.777-35');

    $user = User::factory()->create(['document' => '11222333000181']);
    expect($user->formatted_document)->toBe('11.222.333/0001-81');
});

test('document type accessor', function () {
    $user = User::factory()->create(['document' => '11144477735']);
    expect($user->document_type)->toBe('CPF');

    $user = User::factory()->create(['document' => '11222333000181']);
    expect($user->document_type)->toBe('CNPJ');
});