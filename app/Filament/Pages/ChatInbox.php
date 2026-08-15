<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatInbox extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $title = 'Agent Inbox';
    protected ?string $heading = 'Agent Inbox';
    
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Inbox Customer Service';
    protected static string | \UnitEnum | null $navigationGroup = 'Customer Support';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.chat-inbox';

    public $activeConversationId = null;
    public $messageContent = '';

    // Refresh every 5 seconds via Livewire polling on the view
    public function getConversationsProperty()
    {
        return Conversation::with(['customer', 'whatsappAccount', 'assignedAgent'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    public function getActiveConversationProperty()
    {
        if (!$this->activeConversationId) {
            return null;
        }

        return Conversation::with(['customer', 'participants'])->find($this->activeConversationId);
    }

    public function getMessagesProperty()
    {
        if (!$this->activeConversationId) {
            return [];
        }

        return Message::where('conversation_id', $this->activeConversationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function loadConversation($id)
    {
        $this->activeConversationId = $id;
        
        $conversation = Conversation::find($id);
        if ($conversation && $conversation->unread_count > 0) {
            $conversation->update(['unread_count' => 0]);
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'messageContent' => 'required|string',
        ]);

        if (!$this->activeConversationId) {
            return;
        }

        $conversation = Conversation::find($this->activeConversationId);
        
        if ($conversation) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'human_agent',
                'sender_id' => Auth::id(),
                'content' => $this->messageContent,
                'message_type' => 'text',
                'status' => 'sent',
            ]);

            $conversation->update(['last_message_at' => now()]);

            // Ensure the human agent is a participant
            \App\Models\ConversationParticipant::firstOrCreate([
                'conversation_id' => $conversation->id,
                'participant_type' => 'human_agent',
                'participant_id' => Auth::id(),
            ]);

            $textToSend = $this->messageContent;
            $this->messageContent = '';
            
            // Send message to WhatsApp API
            $customerPhone = $conversation->customer->phone_number ?? null;
            if ($customerPhone) {
                app(\App\Services\WhatsappService::class)->sendMessage($customerPhone, $textToSend);
            }
        }
    }

    public function takeOver()
    {
        if (!$this->activeConversationId) return;
        
        Conversation::where('id', $this->activeConversationId)
            ->update([
                'status' => 'HUMAN_HANDLING',
                'assigned_agent_id' => Auth::id(),
            ]);
    }

    public function returnToAi()
    {
        if (!$this->activeConversationId) return;
        
        Conversation::where('id', $this->activeConversationId)
            ->update([
                'status' => 'AI_HANDLING',
                'assigned_agent_id' => null, // or keep it assigned but let AI handle
            ]);
    }
}
