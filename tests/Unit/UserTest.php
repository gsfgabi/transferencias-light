<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'document' => '12345678901',
            'type' => 'common',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'joao@example.com',
            'document' => '12345678901',
        ]);
    }

    public function test_user_has_wallet()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Wallet::class, $user->wallet);
        $this->assertEquals($user->id, $user->wallet->user_id);
    }

    public function test_user_can_send_money_if_common()
    {
        $user = User::factory()->create(['type' => 'common']);
        
        $this->assertTrue($user->canSendMoney());
    }

    public function test_user_cannot_send_money_if_merchant()
    {
        $user = User::factory()->create(['type' => 'merchant']);
        
        $this->assertFalse($user->canSendMoney());
    }

    public function test_user_can_receive_money()
    {
        $commonUser = User::factory()->create(['type' => 'common']);
        $merchantUser = User::factory()->create(['type' => 'merchant']);
        
        $this->assertTrue($commonUser->canReceiveMoney());
        $this->assertTrue($merchantUser->canReceiveMoney());
    }

    public function test_user_balance_accessor()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
            'balance' => 100.50
        ]);

        $this->assertEquals(100.50, $user->balance);
    }

    public function test_user_balance_accessor_returns_zero_if_no_wallet()
    {
        $user = User::factory()->create();
        
        $this->assertEquals(0.0, $user->balance);
    }

    public function test_document_cleaning()
    {
        $this->assertEquals('12345678901', User::cleanDocument('123.456.789-01'));
        $this->assertEquals('12345678000195', User::cleanDocument('12.345.678/0001-95'));
    }

    public function test_cpf_validation()
    {
        $this->assertTrue(User::isValidCpf('11144477735'));
        $this->assertFalse(User::isValidCpf('11111111111'));
        $this->assertFalse(User::isValidCpf('12345678901'));
    }

    public function test_cnpj_validation()
    {
        $this->assertTrue(User::isValidCnpj('11222333000181'));
        $this->assertFalse(User::isValidCnpj('11111111111111'));
        $this->assertFalse(User::isValidCnpj('12345678901234'));
    }

    public function test_document_validation()
    {
        $this->assertTrue(User::isValidDocument('11144477735')); // Valid CPF
        $this->assertTrue(User::isValidDocument('11222333000181')); // Valid CNPJ
        $this->assertFalse(User::isValidDocument('11111111111')); // Invalid CPF
        $this->assertFalse(User::isValidDocument('123456789')); // Invalid length
    }

    public function test_formatted_document_accessor()
    {
        $user = User::factory()->create(['document' => '11144477735']);
        $this->assertEquals('111.444.777-35', $user->formatted_document);

        $user = User::factory()->create(['document' => '11222333000181']);
        $this->assertEquals('11.222.333/0001-81', $user->formatted_document);
    }

    public function test_document_type_accessor()
    {
        $user = User::factory()->create(['document' => '11144477735']);
        $this->assertEquals('CPF', $user->document_type);

        $user = User::factory()->create(['document' => '11222333000181']);
        $this->assertEquals('CNPJ', $user->document_type);
    }
}
