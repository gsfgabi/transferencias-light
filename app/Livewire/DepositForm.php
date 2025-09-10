<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DepositForm extends Component
{
    public $amount = '';

    protected $rules = [
        'amount' => 'required|numeric|min:0.01|max:10000.00',
    ];

    protected function messages()
    {
        return [
            'amount.required' => __('messages.validation.amount_required'),
            'amount.numeric' => __('messages.validation.amount_numeric'),
            'amount.min' => __('messages.validation.amount_min'),
            'amount.max' => __('messages.validation.amount_max'),
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function confirmDeposit()
    {
        $this->validate();

        $user = Auth::user();

        // Verificar se é administrador
        if ($user->hasRole('admin')) {
            throw ValidationException::withMessages([
                'amount' => [__('messages.error.admin_cannot_deposit')]
            ]);
        }

        // Emitir evento para mostrar confirmação
        $this->dispatch('confirm-deposit', [
            'amount' => $this->amount
        ]);
    }

    public function deposit()
    {
        \Illuminate\Support\Facades\Log::info('=== INÍCIO deposit ===');
        \Illuminate\Support\Facades\Log::info('Dados recebidos no deposit:', [
            'amount' => $this->amount,
            'user_id' => Auth::id()
        ]);

        try {
            $this->validate();
            \Illuminate\Support\Facades\Log::info('✅ Validação passou no deposit');

            $user = Auth::user();

            // Verificar se é administrador
            if ($user->hasRole('admin')) {
                \Illuminate\Support\Facades\Log::error('❌ Administrador tentando depositar');
                throw ValidationException::withMessages([
                    'amount' => [__('messages.error.admin_cannot_deposit')]
                ]);
            }

            \Illuminate\Support\Facades\Log::info('Iniciando transação de depósito...');
            DB::transaction(function () use ($user) {
                $amount = $this->amount;

                // Criar registro de depósito na tabela transactions
                $transaction = \App\Models\Transaction::create([
                    'sender_id' => $user->id, // Usuário fazendo o depósito
                    'payee_id' => $user->id,   // Usuário recebendo o depósito (mesmo usuário)
                    'amount' => $amount,
                    'status' => 'completed',   // Depósito é sempre aprovado
                ]);

                // Atualizar o saldo da carteira
                $user->wallet->increment('balance', $amount);

                \Illuminate\Support\Facades\Log::info('Depósito realizado', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'new_balance' => $user->wallet->fresh()->balance
                ]);
            });

            \Illuminate\Support\Facades\Log::info('✅ Depósito processado com sucesso');
            session()->flash('success', __('messages.success.deposit_completed') . ' Valor: R$ ' . number_format($this->amount, 2, ',', '.'));
            $this->reset(['amount']);
            
            // Forçar atualização da página para mostrar o novo saldo
            $this->js('window.location.reload();');
            
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('❌ ERRO DE VALIDAÇÃO NO DEPOSIT:', [
                'errors' => $e->errors(),
                'amount' => $this->amount
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ ERRO GERAL NO DEPOSIT:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            throw ValidationException::withMessages([
                'amount' => [__('messages.error.deposit_failed')]
            ]);
        }

        \Illuminate\Support\Facades\Log::info('=== FIM deposit ===');
    }

    public function render()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('livewire.deposit-form', [
            'user' => $user
        ]);
    }
}
