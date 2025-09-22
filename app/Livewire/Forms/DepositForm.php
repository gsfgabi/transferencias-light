<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DepositForm extends Form
{
    #[Validate('required|numeric|min:0.01|max:10000.00')]
    public string $amount = '';

    protected function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:10000.00'
            ]
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.required' => __('messages.validation.amount_required'),
            'amount.numeric' => __('messages.validation.amount_numeric'),
            'amount.min' => __('messages.validation.amount_min'),
            'amount.max' => __('messages.validation.amount_max'),
        ];
    }

    public function canDeposit(): bool
    {
        $user = Auth::user();
        return $user && !$user->hasRole('admin');
    }

    public function processDeposit(): array
    {
        $this->validate();

        $user = Auth::user();

        if (!$this->canDeposit()) {
            return [
                'success' => false,
                'message' => 'Administradores não podem realizar depósitos.'
            ];
        }

        try {
            DB::transaction(function () use ($user) {
                $amount = (float) $this->amount;

                // Criar registro de depósito na tabela transactions
                $transaction = \App\Models\Transaction::create([
                    'sender_id' => $user->id,
                    'payee_id' => $user->id,
                    'amount' => $amount,
                    'status' => 'completed',
                ]);

                // Atualizar o saldo da carteira
                $user->wallet->increment('balance', $amount);
            });

            return [
                'success' => true,
                'message' => 'Depósito realizado com sucesso! Valor: R$ ' . number_format($this->amount, 2, ',', '.')
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao processar o depósito. Tente novamente.'
            ];
        }
    }

    public function resetForm(): void
    {
        $this->reset(['amount']);
    }
}
