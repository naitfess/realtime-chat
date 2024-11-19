<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public User $user;
    public $search = '';

    public function render()
    {
        //update is_read
        Message::where('from_user_id', $this->user->id)
            ->where('to_user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('livewire.chat', [
            'messages' => Message::where(function ($query) {
                $query->where('from_user_id', Auth::id())
                    ->where('to_user_id', $this->user->id);
            })->orWhere(function ($query) {
                $query->where('from_user_id', $this->user->id)
                    ->where('to_user_id', Auth::id());
            })->orderBy('created_at', 'asc')->get(),
            'users' => User::where('id', '!=', Auth::id())
                ->when($this->search, function ($query) {
                    $query->where('username', 'like', '%' . $this->search . '%');
                })
                ->get(),
        ]);
    }
}
