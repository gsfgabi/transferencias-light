<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seeders...');

        $this->call([
            PermissionSeeder::class,
            RolesTableSeeder::class,
            UserSeeder::class,
        ]);
    }
}
