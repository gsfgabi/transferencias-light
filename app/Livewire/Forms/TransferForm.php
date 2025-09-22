<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Services\TransferService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransferForm extends Form
{
    #[Validate('required|email')]
    public string $payee_email = '';

    #[Validate('required|numeric|min:0.01')]
    public string $amount = '';

    protected function rules(): array
    {
        return [
            'payee_email' => [
                'required',
                'email',
                'exists:users,email',
                'different:' . Auth::user()?->email
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . (Auth::user()?->balance ?? 0)
            ]
        ];
    }

    protected function messages(): array
    {
        return [
            'payee_email.required' => __('messages.validation.payee_email_required'),
            'payee_email.email' => __('messages.validation.payee_email_email'),
            'payee_email.exists' => __('messages.validation.payee_email_exists'),
            'payee_email.different' => __('messages.validation.payee_email_different'),
            'amount.required' => __('messages.validation.amount_required'),
            'amount.numeric' => __('messages.validation.amount_numeric'),
            'amount.min' => __('messages.validation.amount_min'),
            'amount.max' => __('messages.error.insufficient_balance'),
        ];
    }

    public function getPayeeProperty(): ?User
    {
        if (empty($this->payee_email)) {
            return null;
        }

        return User::where('email', $this->payee_email)->first();
    }

    public function canTransfer(): bool
    {
        $sender = Auth::user();
        $payee = $this->getPayeeProperty();
        
        if (!$sender || !$payee) {
            return false;
        }

        return $sender->canSendMoney() && 
               $sender->id !== $payee->id &&
               $sender->balance >= (float) $this->amount;
    }

    public function processTransfer(): array
    {
        $this->validate();

        $sender = Auth::user();
        $payee = $this->getPayeeProperty();

        if (!$this->canTransfer()) {
            return [
                'success' => false,
                'message' => 'Não foi possível realizar a transferência. Verifique os dados e tente novamente.'
            ];
        }

        $transferService = app(TransferService::class);
        return $transferService->processTransfer($sender, $payee, (float) $this->amount);
    }

    public function resetForm(): void
    {
        $this->reset(['payee_email', 'amount']);
    }
}
