<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatInbox extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Agent Inbox';
    protected ?string $heading = 'Agent Inbox';

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

            $this->messageContent = '';
            
            // TODO: In Phase 5/8 trigger job to actually send message to WhatsApp API
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
