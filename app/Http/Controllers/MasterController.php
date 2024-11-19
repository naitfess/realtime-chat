<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'messageType' => 'required|string'
        ]);
        $selectedUser = User::where('id', $id)->first();
        $message = new Message();

        switch ($request->messageType) {
            case 'text':
                $request->validate([
                    'message' => 'required|string'
                ]);
                $message->content = $request->message;
                break;
            case 'image':
                try {
                    $request->validate([
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        'hiddenMessage' => 'required|string'
                    ]);

                    // Simpan gambar ke storage lokal
                    $image = $request->file('image');
                    $imagePath = $image->storeAs('images', $image->hashName(), 'public');

                    // Menyimpan URL gambar ke dalam database
                    $message->content = asset('storage/' . $imagePath);
                } catch (\Exception $e) {
                    // Tangkap dan log error
                    Log::error('Error uploading image: ' . $e->getMessage());

                    // Mengembalikan respon error dengan pesan yang sesuai
                    return response()->json([
                        'error' => 'There was an issue with uploading the image. Please try again later.',
                        'details' => $e->getMessage()
                    ], 500);
                }
                break;
            case 'file':
                try {
                    $request->validate([
                        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:2048'
                    ]);

                    // Simpan gambar ke storage lokal
                    $file = $request->file('file');
                    $filePath = $file->storeAs('files', $file->hashName(), 'public');

                    // Menyimpan URL gambar ke dalam database
                    $message->content = asset('storage/' . $filePath);
                } catch (\Exception $e) {
                    // Tangkap dan log error
                    Log::error('Error uploading file: ' . $e->getMessage());

                    // Mengembalikan respon error dengan pesan yang sesuai
                    return response()->json([
                        'error' => 'There was an issue with uploading the file. Please try again later.',
                        'details' => $e->getMessage()
                    ], 500);
                }
                break;
            default:
                return redirect()->route('chat', ['id' => $id])->withErrors(['message_error' => 'Invalid message type'])->withInput();
        }

        $message->from_user_id = Auth::id();
        $message->to_user_id = $selectedUser->id;
        $message->subject = $request->messageType;
        $message->save();

        return redirect()->route('chat', ['user' => $id]);
    }
}
