<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $username = '';
    public string $password = '';
    public string $errorMessage = '';

    /**
     * Handle an incoming registration request.
     */
    public function register()
    {
        try {
            $this->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users|min:3|alpha_num',
            'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'private_key' => random_int(1, 25),
            ]);

            return redirect()->route('login')->with('status', 'Your account has been created.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $this->errorMessage = $errors->first();
        } catch (\Exception $e) {
            $this->errorMessage = 'An unexpected error occurred. Please try again later.';
        }
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus placeholder="Enter your name" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" name="username" required placeholder="Enter your username" />
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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
