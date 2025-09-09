<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin can access dashboard', function () {
    // Create admin user
    $admin = User::factory()->admin()->create();
    
    // Create role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole($role);
    
    $this->actingAs($admin);
    
    $response = $this->get('/admin');
    
    $response->assertStatus(200);
    $response->assertSee('Painel Administrativo');
});

test('non-admin cannot access admin dashboard', function () {
    // Create common user
    $user = User::factory()->common()->create();
    
    $this->actingAs($user);
    
    $response = $this->get('/admin');
    
    $response->assertStatus(403);
});

test('admin can view users list', function () {
    // Create admin user
    $admin = User::factory()->admin()->create();
    
    // Create role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole($role);
    
    $this->actingAs($admin);
    
    $response = $this->get('/admin/users');
    
    $response->assertStatus(200);
    $response->assertSee(__('messages.titles.users'));
});

test('admin can create user form', function () {
    // Create admin user
    $admin = User::factory()->admin()->create();
    
    // Create role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole($role);
    
    $this->actingAs($admin);
    
    $response = $this->get('/admin/users/create');
    
    $response->assertStatus(200);
    $response->assertSee('Criar Usuário');
});

test('admin can access permissions page', function () {
    // Create admin user
    $admin = User::factory()->admin()->create();
    
    // Create role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole($role);
    
    $this->actingAs($admin);
    
    $response = $this->get('/admin/permissions');
    
    $response->assertStatus(200);
    $response->assertSee(__('messages.titles.permissions'));
});

test('admin can login as user and return to admin', function () {
    // Create admin user
    $admin = User::factory()->admin()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole($role);
    
    // Create regular user
    $user = User::factory()->common()->create();
    $userRole = Role::firstOrCreate(['name' => 'common-user']);
    $user->assignRole($userRole);
    
    $this->actingAs($admin);
    
    // Login as user
    $response = $this->post("/admin/login-as/{$user->id}");
    $response->assertRedirect(route('dashboard'));
    
    // Check if admin_id is stored in session
    $this->assertEquals($admin->id, session('admin_id'));
    
    // Check if we're logged in as the user
    $this->assertEquals($user->id, auth()->id());
    
    // Simulate the back to admin functionality by manually setting session
    // and calling the controller method directly
    session(['admin_id' => $admin->id]);
    
    // Return to admin - we need to act as the user but with admin session
    $response = $this->post(route('admin.back'));
    $response->assertRedirect(route('admin.dashboard'));
    
    // Check if we're logged in as admin again
    $this->assertEquals($admin->id, auth()->id());
    
    // Check if admin_id is removed from session
    $this->assertNull(session('admin_id'));
});