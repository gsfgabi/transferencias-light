<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuário Comum
        $commonUser = User::firstOrCreate(
            ['email' => 'joao@example.com'],
            [
                'name' => 'João da Silva',
                'document' => '11122233344', 
                'password' => Hash::make('password'),
            ]
        );
        if (!$commonUser->wallet) {
            $commonUser->wallet()->create(['balance' => 1000.00]);
        }
        $commonUser->assignRole('common-user');

        // Usuário Lojista
        $merchantUser = User::firstOrCreate(
            ['email' => 'loja@example.com'],
            [
                'name' => 'Loja do Zé',
                'document' => '12345678000199', 
                'password' => Hash::make('password'),
            ]
        );
        if (!$merchantUser->wallet) {
            $merchantUser->wallet()->create(['balance' => 500.00]);
        }
        $merchantUser->assignRole('merchant');

        // Outro Usuário Comum
        $anotherCommonUser = User::firstOrCreate(
            ['email' => 'maria@example.com'],
            [
                'name' => 'Maria Oliveira',
                'document' => '55566677788', 
                'password' => Hash::make('password'),
            ]
        );
        if (!$anotherCommonUser->wallet) {
            $anotherCommonUser->wallet()->create(['balance' => 200.00]);
        }
        $anotherCommonUser->assignRole('common-user');

        // Usuário Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Sistema',
                'document' => '99988877766',
                'password' => Hash::make('password'),
            ]
        );
        if (!$adminUser->wallet) {
            $adminUser->wallet()->create(['balance' => 0.00]);
        }
        $adminUser->assignRole('admin'); 
    }
}
