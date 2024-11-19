<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Services\DiffieHellmanService;
use App\Services\EncryptionService;

class Chat extends Component
{
    public User $user;
    public $search = '';
    protected $encryptionService;
    protected $diffieHellmanService;

    public function __construct()
    {
        $this->encryptionService = app(EncryptionService::class);
        $this->diffieHellmanService = app(DiffieHellmanService::class);
    }

    public function render()
    {
        // Update status pesan menjadi "dibaca"
        Message::where('from_user_id', $this->user->id)
            ->where('to_user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // Parameter Diffie-Hellman
        $p = 23;
        $g = 5;

        $currentUser = Auth::user();
        $fromPrivateKey = $currentUser->private_key;
        $toPrivateKey = $this->user->private_key;

        // Generate shared secret
        $fromPublicKey = $this->diffieHellmanService->generatePublicKey($fromPrivateKey, $p, $g);
        $toPublicKey = $this->diffieHellmanService->generatePublicKey($toPrivateKey, $p, $g);

        $sharedSecretFrom = $this->diffieHellmanService->generateSharedSecret($toPublicKey, $fromPrivateKey, $p);
        $sharedSecretTo = $this->diffieHellmanService->generateSharedSecret($fromPublicKey, $toPrivateKey, $p);

        // Ambil dan decrypt pesan
        $messages = Message::where(function ($query) {
            $query->where('from_user_id', Auth::id())
                ->where('to_user_id', $this->user->id);
        })->orWhere(function ($query) {
            $query->where('from_user_id', $this->user->id)
                ->where('to_user_id', Auth::id());
        })->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($sharedSecretFrom) {
                return $this->decryptMessage($message, $sharedSecretFrom);
            });

        // Ambil pengguna lain
        $users = User::where('id', '!=', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.chat', [
            'messages' => $messages,
            'users' => $users,
        ]);
    }

    private function decryptMessage($message, $sharedSecret)
    {
        switch ($message->subject) {
            case 'text':
                // Dekripsi pesan teks
                $rc4Decrypted = $this->encryptionService->rc4Decrypt($message->content, strval($sharedSecret));
                $caesarDecrypted = $this->encryptionService->caesarDecrypt($rc4Decrypted, $sharedSecret);
                $message->content = $caesarDecrypted;
                break;
    
            case 'image':
                // Tidak ada dekripsi pada gambar, hanya menampilkan URL
                break;
    
            case 'file':
                try {
                    // Gunakan path lokal ke file terenkripsi
                    $fileUrl = $message->content; // URL file
                    $encryptedFilePath = storage_path('app/public/files/' . basename($fileUrl)); // Path lokal file
    
                    // Pastikan file ada
                    if (!file_exists($encryptedFilePath)) {
                        $message->content = 'File tidak ditemukan di server.';
                        break;
                    }
    
                    // Baca isi file terenkripsi
                    $encryptedContent = file_get_contents($encryptedFilePath);
    
                    // Dekripsi file
                    $key = hash('sha256', $sharedSecret);
                    $decryptedFile = $this->encryptionService->aesDecrypt($encryptedContent, $key);
    
                    // Simpan file hasil dekripsi ke lokasi sementara
                    $decryptedFilePath = storage_path('app/public/temp/' . uniqid() . '_' . pathinfo($encryptedFilePath, PATHINFO_FILENAME));
                    file_put_contents($decryptedFilePath, $decryptedFile);
    
                    // Perbarui content dengan URL ke file sementara
                    $message->content = asset('storage/temp/' . basename($decryptedFilePath));
                } catch (\Exception $e) {
                    // Tangani error
                    $message->content = 'Error decrypting file: ' . $e->getMessage();
                }
                break;
        }
    
        return $message;
    }    

}
