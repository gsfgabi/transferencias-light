<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Exception;

class DepositController extends Controller
{
    public function show()
    {
        return view('deposit-form');
    }

    public function process(Request $request)
    {
        // Se não estiver autenticado, redirecionar para login
        if (!Auth::check()) {
            return redirect()->route('deposit.form')->with('error', 
                'Você precisa fazer login para realizar um depósito.'
            );
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:10000.00',
        ], [
            'amount.required' => 'O valor do depósito é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min' => 'O valor mínimo para depósito é R$ 0,01.',
            'amount.max' => 'O valor máximo para depósito é R$ 10.000,00.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $amount = $request->amount;

                // Criar registro de depósito na tabela transactions
                $transaction = Transaction::create([
                    'sender_id' => $user->id, // Usuário fazendo o depósito
                    'payee_id' => $user->id,   // Usuário recebendo o depósito (mesmo usuário)
                    'amount' => $amount,
                    'status' => 'completed',   // Depósito é sempre aprovado
                ]);

                // Atualizar o saldo da carteira
                $user->wallet->increment('balance', $amount);

                Log::info('Depósito realizado', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'new_balance' => $user->wallet->fresh()->balance
                ]);
            });

            return redirect()->route('deposit.form')->with('success', 
                'Depósito realizado com sucesso! Seu saldo foi atualizado.'
            );

        } catch (Exception $e) {
            Log::error('Erro ao processar depósito', [
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('deposit.form')->with('error', 
                'Erro ao processar o depósito. Tente novamente.'
            );
        }
    }
}
