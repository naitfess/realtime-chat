<?php

use App\Http\Controllers\MasterController;
use App\Livewire\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/hidden-message', function () {
    return view('hidden-message');
})->middleware(['auth'])->name('hidden-message');

Route::post('/hidden-message', [MasterController::class, 'extractHiddenMessage'])
    ->middleware(['auth'])
    ->name('hidden-message');

require __DIR__ . '/auth.php';
