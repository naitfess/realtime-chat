<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public $username = '';
    public $password = '';
    public $errorMessage = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        try {
            $this->validate([
                'username' => 'required|alpha_num',
                'password' => 'required',
            ]);

            if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
                Session::regenerate();
                return redirect()->route('dashboard');
            } else {
                $this->errorMessage = 'Invalid credentials. Please try again.';
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $this->errorMessage = $errors->first();
        } catch (\Exception $e) {
            $this->errorMessage = 'An unexpected error occurred. Please try again later.';
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')"/>
            <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" name="username" required autofocus placeholder="Enter your username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required placeholder="Enter your password" />

            <x-input-error :messages="$errorMessage" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>

            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}" wire:navigate>
                {{ __('Don\'t have an account?') }}
            </a>
        </div>        
    </form>
</div>
