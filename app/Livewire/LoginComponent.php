<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginComponent extends Component
{
    public $email = '';
    public $password = '';
    public $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            Session::regenerate();
            return redirect()->route('dashboard');
        } else {
            $this->errorMessage = 'Invalid credentials. Please try again.';
        }
    }

    public function render()
    {
        return view('livewire.login-component');
    }
}
