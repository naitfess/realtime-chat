<?php

use App\Http\Controllers\MasterController;
use App\Livewire\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\LoginComponent;

Route::get('/login', LoginComponent::class)->name('login');

Route::get('/', function () {
    return view('dashboard', [
        'users' => User::where('id', '!=', Auth::id())->get(),
    ]);
})->middleware(['auth'])->name('dashboard');

Route::get('/chat/{user}', Chat::class)
    ->middleware(['auth'])
    ->name('chat');

Route::post('/chat/{user}', [MasterController::class, 'sendMessage'])
    ->middleware(['auth'])
    ->name('send-message');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
