<?php

namespace App\Livewire\Transfer;

use App\Livewire\Forms\TransferForm as TransferFormData;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TransferFormComponent extends Component
{
    public TransferFormData $form;


    public function updatedFormPayeeEmail(): void
    {
        $this->form->validateOnly('payee_email');
    }

    public function updatedFormAmount(): void
    {
        $this->form->validateOnly('amount');
    }

    public function confirmTransfer(): void
    {
        $this->form->validate();

        if (!$this->form->canTransfer()) {
            $this->addError('form.payee_email', 'Lojistas não podem realizar transferências.');
            return;
        }

        $payee = $this->form->getPayeeProperty();
        $this->dispatch('confirm-transfer', [
            'amount' => $this->form->amount,
            'recipient' => $payee->name,
            'recipientEmail' => $payee->email
        ]);
    }

    public function transfer(): void
    {
        $result = $this->form->processTransfer();

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->form->resetForm();
            $this->dispatch('transfer-completed');
        } else {
            $this->addError('form.payee_email', $result['message']);
        }
    }

    public function render()
    {
        return view('livewire.transfer.transfer-form', [
            'user' => Auth::user(),
            'canTransfer' => $this->form->canTransfer(),
            'payee' => $this->form->getPayeeProperty()
        ]);
    }
}
