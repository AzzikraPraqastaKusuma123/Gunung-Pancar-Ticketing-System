<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AgentInbox extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Inbox Customer Service';
    protected static ?string $title = 'Agent Inbox';
    protected static ?string $slug = 'agent-inbox';
    protected static string | \UnitEnum | null $navigationGroup = 'Customer Support';
    
    protected string $view = 'filament.pages.agent-inbox';

    public $activeConversationId = null;

    public function mount()
    {
        // Load initial data if needed
    }

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'sales']);
    }
}
