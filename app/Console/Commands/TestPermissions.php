<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the permission system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Testando Sistema de Permissões...');
        $this->newLine();

        // Listar todas as roles
        $this->info('📋 Roles disponíveis:');
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->line("  - {$role->name}");
        }
        $this->newLine();

        // Listar todas as permissões
        $this->info('🔑 Permissões disponíveis:');
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $this->line("  - {$permission->name}");
        }
        $this->newLine();

        // Testar usuários
        $this->info('👥 Testando usuários:');
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $this->line("  {$user->name} ({$user->email}):");
            $this->line("    Tipo: {$user->type}");
            $this->line("    Roles: " . $user->roles->pluck('name')->join(', '));
            
            // Testar permissões específicas
            $permissionsToTest = [
                'dashboard.view',
                'transfer.create',
                'transfer.view',
                'deposit.create',
                'deposit.view'
            ];
            
            foreach ($permissionsToTest as $permission) {
                $hasPermission = $user->can($permission);
                $status = $hasPermission ? '✅' : '❌';
                $this->line("      {$status} {$permission}");
            }
            $this->newLine();
        }

        $this->info('✅ Teste de permissões concluído!');
    }
}