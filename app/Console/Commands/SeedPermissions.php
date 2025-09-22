<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolesTableSeeder;

class SeedPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:permissions {--fresh : Reset all permissions and roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permissions and roles with proper separation of concerns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Gerenciando Permissões e Roles...');
        $this->newLine();

        if ($this->option('fresh')) {
            $this->warn('⚠️ Modo FRESH: Todas as permissões e roles serão resetadas!');
            
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('❌ Operação cancelada.');
                return;
            }

            $this->info('🗑️ Limpando permissões e roles existentes...');
            
            // Clear all permissions and roles
            \Spatie\Permission\Models\Permission::query()->delete();
            \Spatie\Permission\Models\Role::query()->delete();
            
            $this->info('✅ Permissões e roles limpas.');
            $this->newLine();
        }

        // Step 1: Create permissions
        $this->info('📋 Passo 1: Criando permissões...');
        $this->call('db:seed', ['--class' => PermissionSeeder::class]);
        $this->newLine();

        // Step 2: Create roles
        $this->info('👥 Passo 2: Criando roles...');
        $this->call('db:seed', ['--class' => RolesTableSeeder::class]);
        $this->newLine();

        // Step 3: Assign permissions to roles
        $this->info('🔐 Passo 3: Atribuindo permissões às roles...');
        $this->call('role:assign-permissions');
        $this->newLine();

        // Summary
        $permissionCount = \Spatie\Permission\Models\Permission::count();
        $roleCount = \Spatie\Permission\Models\Role::count();

        $this->info('📊 Resumo Final:');
        $this->line("   • Permissões: {$permissionCount}");
        $this->line("   • Roles: {$roleCount}");
        $this->newLine();

        $this->info('🎉 Processo concluído com sucesso!');
        
        // Show available roles
        $this->info('👥 Roles disponíveis:');
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $permissionCount = $role->permissions->count();
            $this->line("   • {$role->name} ({$permissionCount} permissões)");
        }
    }
}