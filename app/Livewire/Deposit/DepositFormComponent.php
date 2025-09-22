<?php

namespace App\Livewire\Deposit;

use App\Livewire\Forms\DepositForm as DepositFormData;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DepositFormComponent extends Component
{
    public DepositFormData $form;


    public function updatedFormAmount(): void
    {
        $this->form->validateOnly('amount');
    }

    public function confirmDeposit(): void
    {
        $this->form->validate();

        if (!$this->form->canDeposit()) {
            $this->addError('form.amount', 'Administradores não podem realizar depósitos.');
            return;
        }

        $this->dispatch('confirm-deposit', [
            'amount' => $this->form->amount
        ]);
    }

    public function deposit(): void
    {
        $result = $this->form->processDeposit();

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->form->resetForm();
            $this->dispatch('deposit-completed');
        } else {
            $this->addError('form.amount', $result['message']);
        }
    }

    public function render()
    {
        return view('livewire.deposit.deposit-form', [
            'user' => Auth::user(),
            'canDeposit' => $this->form->canDeposit()
        ]);
    }
}
