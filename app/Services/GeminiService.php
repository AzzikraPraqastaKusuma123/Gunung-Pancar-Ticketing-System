<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public function generateReply(Conversation $conversation, WhatsappService $whatsappService)
    {
        // Jangan membalas jika bukan tanggung jawab AI (misal: sedang ditangani agen manusia)
        if ($conversation->status !== 'AI_HANDLING') {
            Log::info("GeminiService: Percakapan {$conversation->id} diabaikan karena status {$conversation->status}");
            return;
        }

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            Log::error('Gemini API Key is not set in .env');
            return;
        }

        // Ambil 10 pesan terakhir sebagai konteks ingatan percakapan
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->take(10)->get();

        $contents = [];
        
        // System Prompt (Instruksi Dasar)
        $systemPrompt = "Anda adalah AI Customer Service yang sangat ramah, hangat, dan profesional untuk TicketBrain (sistem tiket & CRM). Tugas Anda adalah menjawab pertanyaan pelanggan dengan singkat, jelas, dan solutif. Jika ada hal yang Anda tidak ketahui, katakan dengan sopan bahwa Anda akan meneruskan chat ini ke agen manusia kami.";
        
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => 'System Instructions: ' . $systemPrompt]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Baik, saya mengerti peran saya dan akan mematuhinya.']]
        ];

        $lastUserMessage = '';

        // Masukkan riwayat pesan
        foreach ($messages as $msg) {
            // Dalam Gemini API, role yang diizinkan hanya 'user' atau 'model'
            $role = ($msg->sender_type === 'ai') ? 'model' : 'user';
            
            // Format konten sesuai struktur Gemini API
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg->content]]
            ];
            
            if ($role === 'user') {
                $lastUserMessage = $msg->content;
            }
        }

        // --- ATURAN PERCAKAPAN GRUP ---
        // Sesuai permintaan: "apakah AI nya bisa membalas lewat grup?"
        // Kita berikan logika: Jika ini grup, hanya balas jika AI di-mention.
        $customer = $conversation->customer;
        if ($customer && $customer->is_group) {
            // Cek apakah ada sebutan untuk bot
            if (stripos($lastUserMessage, '@TicketBrain') === false && stripos($lastUserMessage, '@Admin') === false && stripos($lastUserMessage, '@Bot') === false) {
                Log::info("GeminiService: Pesan grup diabaikan karena bot tidak di-mention (@TicketBrain/Admin/Bot).");
                return;
            }
        }

        // URL API Google Gemini
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7, // Kreativitas sedang
                'maxOutputTokens' => 400, // Jangan terlalu panjang agar natural di WA
            ]
        ];

        try {
            $response = Http::post($url, $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($replyText) {
                    // 1. Kirim pesan ke WhatsApp Meta
                    $recipientPhone = $customer->phone_number;
                    $whatsappService->sendMessage($recipientPhone, $replyText);

                    // 2. Simpan balasan AI ke database
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_type' => 'ai',
                        'sender_id' => null,
                        'content' => $replyText,
                        'message_type' => 'text',
                        'status' => 'delivered',
                        'provider_message_id' => 'ai-gemini-' . uniqid(),
                    ]);
                    
                    $conversation->update(['last_message_at' => now()]);
                    Log::info("GeminiService: Berhasil mengirim dan menyimpan balasan AI.");
                }
            } else {
                Log::error("Gemini API Error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("GeminiService Exception: " . $e->getMessage());
        }
    }
}
