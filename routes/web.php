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

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Rotas protegidas para depósito e transferência
Route::middleware(['auth'])->group(function () {
    Route::get('/deposit', [DepositController::class, 'show'])->name('deposit.form');
    Route::post('/deposit', [DepositController::class, 'process'])->name('deposit.process');
    
    Route::get('/transfer', [TransferController::class, 'show'])->name('transfer.form');
    Route::post('/transfer', [TransferController::class, 'process'])->name('transfer.process');
});

require __DIR__.'/auth.php';
