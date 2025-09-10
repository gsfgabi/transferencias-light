<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }

    /**
     * Alternative logout method using direct Auth
     */
    public function logoutDirect(): void
    {
        Auth::guard('web')->logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Usar JavaScript para forçar redirecionamento
        $this->js('window.location.href = "' . route('login') . '"');
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}

