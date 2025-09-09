<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Livewire\Volt\Volt;

test('shows specific error for non-existent user', function () {
    $component = Livewire::test('pages.auth.login')
        ->set('form.email', 'nonexistent@example.com')
        ->set('form.password', 'password123')
        ->call('login');

    $component->assertHasErrors(['form.email']);
    $component->assertSee(__('messages.error.user_not_found'));
});

test('shows specific error for wrong password', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'common-user']);
    $user->assignRole($role);

    $component = Livewire::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrongpassword')
        ->call('login');

    $component->assertHasErrors(['form.password']);
    $component->assertSee(__('messages.error.wrong_password'));
});

test('successful login with correct credentials', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'common-user']);
    $user->assignRole($role);

    $component = Livewire::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login');

    $component->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});
