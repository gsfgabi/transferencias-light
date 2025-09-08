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
        $commonUser = User::create([
            'name' => 'João da Silva',
            'email' => 'joao@example.com',
            'document' => '11122233344', 
            'type' => 'common',
            'password' => Hash::make('password'),
        ]);
        $commonUser->wallet()->create(['balance' => 1000.00]); 

        // Usuário Lojista
        $merchantUser = User::create([
            'name' => 'Loja do Zé',
            'email' => 'loja@example.com',
            'document' => '12345678000199', 
            'type' => 'merchant',
            'password' => Hash::make('password'),
        ]);
        $merchantUser->wallet()->create(['balance' => 500.00]); 

        // Outro Usuário Comum
        $anotherCommonUser = User::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria@example.com',
            'document' => '55566677788', 
            'type' => 'common',
            'password' => Hash::make('password'),
        ]);
        $anotherCommonUser->wallet()->create(['balance' => 200.00]); 
    }
}
