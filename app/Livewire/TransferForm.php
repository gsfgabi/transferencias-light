<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Services\TransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransferForm extends Component
{
    public $payee_email = '';
    public $amount = '';

    protected $rules = [
        'payee_email' => 'required|email|exists:users,email|different:current_user_email',
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

    public function getCurrentUserEmailProperty()
    {
        return Auth::user()->email ?? '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function transfer(TransferService $transferService)
    {
        $this->validate();

        $sender = Auth::user();
        $payee = User::where('email', $this->payee_email)->first();

        // Verificar se não está transferindo para si mesmo
        if ($sender->id === $payee->id) {
            throw ValidationException::withMessages([
                'payee_email' => [__('messages.error.cannot_transfer_to_self')]
            ]);
        }

        // Verificar se é lojista tentando transferir
        if ($sender->hasRole('merchant')) {
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

        // Realizar transferência
        $result = $transferService->processTransfer($sender, $payee, $this->amount);

        if ($result['success']) {
            session()->flash('success', __('messages.success.transfer_completed') . ' Valor: R$ ' . number_format($this->amount, 2, ',', '.'));
            $this->reset(['payee_email', 'amount']);
        } else {
            throw ValidationException::withMessages([
                'payee_email' => [$result['message']]
            ]);
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('livewire.transfer-form', [
            'user' => $user
        ]);
    }
}
