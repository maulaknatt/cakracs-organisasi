<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    /**
     * Ambil riwayat chat user.
     */
    public function history()
    {
        $chats = Chat::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($chats);
    }

    /**
     * Kirim pesan ke AI dengan pemahaman konteks.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userId = auth()->id();

        // 1. Simpan pesan terbaru user ke database agar masuk hitungan history
        $userChat = Chat::create([
            'user_id' => $userId,
            'role' => 'user',
            'message' => $request->message,
        ]);

        try {
            $apiKey = config('services.huggingface.key');
            $model = config('services.huggingface.model');
            
            if (!$apiKey) {
                return response()->json([
                    'role' => 'assistant',
                    'message' => 'Hugging Face API Key belum dikonfigurasi di file .env.'
                ]);
            }

            // 2. Ambil history chat sebelumnya untuk konteks
            // Kita ambil 15 pesan terakhir (termasuk pesan user yang baru saja disimpan)
            $history = Chat::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(15) 
                ->get()
                ->reverse(); // Urutkan kembali agar menjadi ASC (kronologis)

            // 3. Format pesan untuk OpenAI-compatible API (Hugging Face Router)
            $messages = [
                [
                    'role' => 'system', 
                    'content' => 'Anda adalah asisten AI yang membantu organisasi. Jawablah dalam bahasa Indonesia yang ramah dan profesional. Anda harus mengingat detail yang disebutkan user sebelumnya.'
                ]
            ];

            foreach ($history as $chat) {
                $messages[] = [
                    'role' => $chat->role,
                    'content' => $chat->message
                ];
            }

            // 4. Panggil Hugging Face Router API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("https://router.huggingface.co/v1/chat/completions", [
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => 1200,
                'temperature' => 0.7,
                'stream' => false
            ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $errorBody['error'] ?? 'Gagal menghubungi AI Router';
                throw new \Exception($errorMessage);
            }

            $result = $response->json();
            $aiMessage = $result['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa memproses jawaban saat ini.';

            // 5. Simpan balasan AI ke database
            $aiChat = Chat::create([
                'user_id' => $userId,
                'role' => 'assistant',
                'message' => $aiMessage,
            ]);

            return response()->json($aiChat);

        } catch (\Exception $e) {
            \Log::error('AI Chat Error (Contextual): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId
            ]);
            
            return response()->json([
                'role' => 'assistant',
                'message' => 'Maaf, terjadi kesalahan pada asisten AI: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bersihkan riwayat chat user.
     */
    public function clearHistory()
    {
        Chat::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'History cleared successfully']);
    }
}
