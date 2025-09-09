<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Tentar autenticar diretamente
        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            // Verificar se o usuário existe para dar mensagem específica
            $user = \App\Models\User::where('email', $this->email)->first();
            
            if (!$user) {
                throw ValidationException::withMessages([
                    'form.email' => __('messages.error.user_not_found'),
                ]);
            } else {
                throw ValidationException::withMessages([
                    'form.password' => __('messages.error.wrong_password'),
                ]);
            }
        }
    }

}
