<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\WhatsappAccount;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsappService
{
    /**
     * Handle incoming WhatsApp message webhook payload (Meta API).
     */
    public function handleIncomingMessage(array $payload): ?Conversation
    {
        if (!isset($payload['entry'][0]['changes'][0]['value'])) {
            return null;
        }

        $value = $payload['entry'][0]['changes'][0]['value'];

        // Cek jika ini adalah pesan masuk
        if (!isset($value['messages'][0])) {
            // Bisa jadi ini adalah status update
            if (isset($value['statuses'][0])) {
                $this->handleStatusUpdate($value['statuses'][0]);
            }
            return null;
        }

        $messageData = $value['messages'][0];
        $contactData = $value['contacts'][0] ?? null;
        $metadata = $value['metadata'];

        // 1. Identify the WhatsApp Account receiving the message
        $receiverPhoneId = $metadata['phone_number_id'] ?? env('WA_PHONE_NUMBER_ID'); 
        $receiverPhone = $metadata['display_phone_number'] ?? 'unknown';
        
        $whatsappAccount = WhatsappAccount::firstOrCreate(
            ['phone_number' => $receiverPhoneId],
            [
                'provider' => 'meta', 
                'is_active' => true,
                'name' => 'Business Account ('. $receiverPhone .')'
            ]
        );

        // 2. Identify the Customer
        $senderPhone = $messageData['from'] ?? 'unknown';
        $senderName = $contactData['profile']['name'] ?? 'Unknown Customer';
        
        $customer = Customer::firstOrCreate(
            ['phone_number' => $senderPhone],
            [
                'name' => $senderName,
                'is_group' => false,
                'first_interaction_at' => now(),
            ]
        );
        $customer->update(['last_interaction_at' => now()]);
        
        // 3. Find or Create Conversation
        $conversation = Conversation::firstOrCreate(
            [
                'whatsapp_account_id' => $whatsappAccount->id,
                'customer_id' => $customer->id,
                'status' => 'AI_HANDLING',
            ],
            [
                'priority' => 'normal',
                'last_message_at' => now(),
            ]
        );
        
        $conversation->increment('unread_count');
        $conversation->update(['last_message_at' => now()]);

        // 4. Register Participants
        ConversationParticipant::firstOrCreate([
            'conversation_id' => $conversation->id,
            'participant_type' => 'customer',
            'participant_id' => $customer->id,
        ]);
        
        ConversationParticipant::firstOrCreate([
            'conversation_id' => $conversation->id,
            'participant_type' => 'ai',
            'participant_id' => null,
        ]);

        // 5. Save the Message
        $messageType = $messageData['type'] ?? 'text';
        $content = '';
        if ($messageType === 'text') {
            $content = $messageData['text']['body'] ?? '';
        } else {
            $content = '[Pesan tipe: ' . $messageType . ']'; // Handle media later
        }

        $providerMessageId = $messageData['id'] ?? Str::uuid()->toString();
        
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->id,
            'content' => $content,
            'message_type' => $messageType,
            'status' => 'delivered',
            'provider_message_id' => $providerMessageId,
            'metadata' => $payload,
        ]);

        Log::info("Message saved for conversation {$conversation->id}: {$content}");
        
        return $conversation;
    }

    /**
     * Handle message status updates (Meta API)
     */
    public function handleStatusUpdate(array $statusData)
    {
        $providerMessageId = $statusData['id'] ?? null;
        $status = $statusData['status'] ?? null;
        
        if ($providerMessageId && $status) {
            Message::where('provider_message_id', $providerMessageId)
                ->update(['status' => $status]);
        }
    }

    /**
     * Send message using Meta WhatsApp Business API
     */
    public function sendMessage($to, $text, $waAccountId = null)
    {
        $phoneNumberId = env('WA_PHONE_NUMBER_ID');
        $accessToken = env('WA_ACCESS_TOKEN');

        if (!$phoneNumberId || !$accessToken) {
            Log::error('Meta WhatsApp API credentials not configured.');
            return false;
        }

        $url = "https://graph.facebook.com/v20.0/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $text
            ]
        ]);

        if ($response->successful()) {
            Log::info("Message sent successfully to {$to}");
            return $response->json();
        } else {
            Log::error("Failed to send message to {$to}: " . $response->body());
            return false;
        }
    }
}
