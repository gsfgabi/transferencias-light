<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Services\TransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransferForm extends Component
{
    public $payee_email = '';
    public $amount = '';

    protected $rules = [
        'payee_email' => 'required|email',
        'amount' => 'required|numeric|min:0.01',
    ];

    protected function messages()
    {
        return [
            'payee_email.required' => __('messages.validation.payee_email_required'),
            'payee_email.email' => __('messages.validation.payee_email_email'),
            'payee_email.exists' => __('messages.validation.payee_email_exists'),
            'payee_email.different' => __('messages.validation.payee_email_different'),
            'amount.required' => __('messages.validation.amount_required'),
            'amount.numeric' => __('messages.validation.amount_numeric'),
            'amount.min' => __('messages.validation.amount_min'),
        ];
    }

    // Removido getCurrentUserEmailProperty() - pode estar causando problemas de serialização

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function confirmTransfer()
    {
        // Validação básica primeiro
        $this->validate();

        $sender = Auth::user();

        // Verificar se o email existe
        $payee = User::where('email', $this->payee_email)->first();
        if (!$payee) {
            throw ValidationException::withMessages([
                'payee_email' => [__('messages.error.user_not_found')]
            ]);
        }

        // Verificar se não está transferindo para si mesmo
        if ($sender->id === $payee->id) {
            throw ValidationException::withMessages([
                'payee_email' => [__('messages.error.cannot_transfer_to_self')]
            ]);
        }

        // Verificar se é lojista tentando transferir
        if ($sender->type === 'merchant') {
            throw ValidationException::withMessages([
                'payee_email' => [__('messages.error.merchant_cannot_transfer')]
            ]);
        }

        // Verificar saldo suficiente
        if ($sender->balance < $this->amount) {
            throw ValidationException::withMessages([
                'amount' => [__('messages.error.insufficient_balance')]
            ]);
        }

        // Emitir evento para mostrar confirmação
        $this->dispatch('confirm-transfer', [
            'amount' => $this->amount,
            'recipient' => $payee->name,
            'recipientEmail' => $payee->email
        ]);
    }

    public function testMethod()
    {
        // Teste ultra simples - apenas retornar
        $this->js('console.log("✅ testMethod executado com sucesso!");');
    }

    public function transfer()
    {
        Log::info('=== INÍCIO transfer ===');
        Log::info('Dados recebidos no transfer:', [
            'payee_email' => $this->payee_email,
            'amount' => $this->amount,
            'user_id' => Auth::id()
        ]);

        try {
            Log::info('Validando dados no transfer...');
            $this->validate();
            Log::info('✅ Validação passou no transfer');

            Log::info('Buscando usuários...');
            $sender = Auth::user();
            $payee = User::where('email', $this->payee_email)->first();

            Log::info('Usuários encontrados:', [
                'sender' => [
                    'id' => $sender->id,
                    'name' => $sender->name,
                    'balance' => $sender->balance
                ],
                'payee' => [
                    'id' => $payee->id,
                    'name' => $payee->name,
                    'email' => $payee->email
                ]
            ]);

            Log::info('Chamando TransferService...');
            $transferService = app(TransferService::class);
            $result = $transferService->processTransfer($sender, $payee, $this->amount);
            Log::info('Resultado do TransferService:', $result);

            if ($result['success']) {
                Log::info('✅ Transferência realizada com sucesso');
                session()->flash('success', __('messages.success.transfer_completed') . ' Valor: R$ ' . number_format($this->amount, 2, ',', '.'));
                $this->reset(['payee_email', 'amount']);
                Log::info('✅ Formulário resetado');
            } else {
                Log::error('❌ Transferência falhou:', $result);
                throw ValidationException::withMessages([
                    'payee_email' => [$result['message']]
                ]);
            }

        } catch (ValidationException $e) {
            Log::error('❌ ERRO DE VALIDAÇÃO NO TRANSFER:', [
                'errors' => $e->errors(),
                'payee_email' => $this->payee_email,
                'amount' => $this->amount
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('❌ ERRO GERAL NO TRANSFER:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        Log::info('=== FIM transfer ===');
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.transfer-form', [
            'user' => $user
        ]);
    }
}
