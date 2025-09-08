<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Garantir que o usuário está autenticado
        if (!$user) {
            return redirect()->route('login');
        }

        // Buscar dados para o dashboard
        $sentTransactions = $user->sentTransactions()->latest()->take(3)->get();
        $receivedTransactions = $user->receivedTransactions()->latest()->take(3)->get();
        
        // Buscar todas as transações do usuário (enviadas e recebidas) sem duplicação
        $allTransactions = $user->transactions()->latest()->take(5)->get();

        $stats = [
            'total_transactions' => $user->transactions()->count(),
            'sent_transactions' => $user->sentTransactions()->count(),
            'received_transactions' => $user->receivedTransactions()->count(),
        ];

        return view('dashboard', compact('user', 'allTransactions', 'stats'));
    }
}