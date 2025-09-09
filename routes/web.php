<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\TransferController;

// Redirecionar usuários não autenticados para login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'permission:dashboard.view'])
    ->name('dashboard');

// Rotas administrativas
Route::prefix('admin')->middleware(['auth', 'permission:admin.users.view'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}', [App\Http\Controllers\AdminController::class, 'userShow'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/login-as/{user}', [App\Http\Controllers\AdminController::class, 'loginAsUser'])->name('admin.login-as');
    
    // Rotas de permissões
    Route::get('/permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::put('/permissions/role/{role}', [App\Http\Controllers\PermissionController::class, 'updateRolePermissions'])->name('admin.permissions.update-role');
    Route::post('/permissions/create', [App\Http\Controllers\PermissionController::class, 'createPermission'])->name('admin.permissions.create');
    Route::delete('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'deletePermission'])->name('admin.permissions.delete');
    Route::post('/permissions/reset', [App\Http\Controllers\PermissionController::class, 'resetPermissions'])->name('admin.permissions.reset');
});

// Rota para voltar ao admin (não precisa de permissão específica, apenas verifica sessão)
Route::post('/admin/back-to-admin', [App\Http\Controllers\AdminController::class, 'backToAdmin'])->name('admin.back');

// Rota de teste do Livewire
Route::get('/test-livewire', function () {
    return view('test-livewire');
});

// Rotas protegidas para depósito e transferência
Route::middleware(['auth'])->group(function () {
    // Visualização - permitida para todos os usuários autenticados
    Route::get('/deposit', [DepositController::class, 'show'])
        ->name('deposit.form');
    Route::get('/transfer', [TransferController::class, 'show'])
        ->name('transfer.form');
    
});

// Rota de teste sem middleware para debug
Route::get('/transfer-debug', [TransferController::class, 'show'])
    ->name('transfer.debug');

require __DIR__.'/auth.php';
