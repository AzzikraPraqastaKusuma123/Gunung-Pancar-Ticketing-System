<x-filament-panels::page>
    <style>
        .chat-container {
            display: grid;
            grid-template-columns: 280px 1fr 300px;
            gap: 1.5rem;
            height: calc(100vh - 11rem);
            min-height: 600px;
        }
        @media (max-width: 1024px) {
            .chat-container {
                grid-template-columns: 1fr;
                height: auto;
            }
        }
        
        .chat-panel {
            background-color: rgb(255 255 255 / 1);
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        :is(.dark .chat-panel) {
            background-color: rgba(24, 24, 27, 1);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .chat-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background-color: rgba(249, 250, 251, 0.5);
        }
        :is(.dark .chat-header) {
            border-bottom-color: rgba(255, 255, 255, 0.1);
            background-color: rgba(24, 24, 27, 0.5);
        }

        .chat-list {
            flex: 1;
            overflow-y: auto;
        }

        .chat-item {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            text-align: left;
            width: 100%;
        }
        :is(.dark .chat-item) {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }
        .chat-item:hover {
            background-color: rgba(0,0,0,0.02);
        }
        :is(.dark .chat-item:hover) {
            background-color: rgba(255,255,255,0.02);
        }
        .chat-item.active {
            background-color: rgba(var(--primary-500), 0.1);
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background-color: rgba(249, 250, 251, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        :is(.dark .chat-messages) {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .chat-bubble {
            max-width: 80%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            position: relative;
        }
        
        .chat-bubble-customer {
            align-self: flex-start;
            background-color: white;
            border: 1px solid rgba(0,0,0,0.05);
            border-bottom-left-radius: 0.25rem;
        }
        :is(.dark .chat-bubble-customer) {
            background-color: rgba(39, 39, 42, 1);
            border-color: rgba(255,255,255,0.1);
            color: white;
        }

        .chat-bubble-agent {
            align-self: flex-end;
            background-color: rgba(var(--primary-600), 1);
            color: white;
            border-bottom-right-radius: 0.25rem;
        }
        
        .chat-bubble-ai {
            align-self: flex-end;
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: rgba(4, 120, 87, 1);
            border-bottom-right-radius: 0.25rem;
        }
        :is(.dark .chat-bubble-ai) {
            background-color: rgba(16, 185, 129, 0.15);
            color: rgba(110, 231, 183, 1);
        }

        .chat-input-area {
            padding: 1rem;
            border-top: 1px solid rgba(0,0,0,0.05);
            background-color: white;
            display: flex;
            gap: 0.75rem;
        }
        :is(.dark .chat-input-area) {
            border-top-color: rgba(255, 255, 255, 0.1);
            background-color: rgba(24, 24, 27, 1);
        }
        
        .chat-input {
            flex: 1;
            border-radius: 0.5rem;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
            background: transparent;
        }
        :is(.dark .chat-input) {
            border-color: rgba(255,255,255,0.2);
            color: white;
        }
        .chat-input:focus {
            border-color: rgba(var(--primary-500), 1);
            box-shadow: 0 0 0 1px rgba(var(--primary-500), 1);
        }

        .chat-placeholder {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(156, 163, 175, 1);
        }
    </style>

    <div class="chat-container" wire:poll.3s>
        
        <!-- Left Column: Conversations List -->
        <div class="chat-panel">
            <div class="chat-header">
                <h3 style="font-weight: 600;">Conversations</h3>
            </div>
            <div class="chat-list">
                @forelse($this->conversations as $conversation)
                    <button wire:click="loadConversation({{ $conversation->id }})" class="chat-item {{ $activeConversationId === $conversation->id ? 'active' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%; margin-bottom: 0.5rem;">
                            <span style="font-weight: 500; font-size: 0.875rem;">{{ $conversation->customer->name ?? 'Unknown' }}</span>
                            <span style="font-size: 0.75rem; opacity: 0.6;">{{ $conversation->last_message_at?->diffForHumans(null, true, true) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <x-filament::badge size="xs" color="{{ $conversation->status === 'AI_HANDLING' ? 'success' : 'warning' }}">
                                {{ str_replace('_', ' ', $conversation->status) }}
                            </x-filament::badge>
                            @if($conversation->unread_count > 0)
                                <x-filament::badge size="xs" color="danger">
                                    {{ $conversation->unread_count }}
                                </x-filament::badge>
                            @endif
                        </div>
                    </button>
                @empty
                    <div style="padding: 2rem; text-align: center; color: gray; font-size: 0.875rem;">
                        No active conversations
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Center Column: Chat Window -->
        <div class="chat-panel">
            @if($this->activeConversation)
                <!-- Chat Header -->
                <div class="chat-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-weight: 600; font-size: 1rem;">{{ $this->activeConversation->customer->name }}</h3>
                        <p style="font-size: 0.75rem; opacity: 0.7;">{{ $this->activeConversation->customer->phone_number }}</p>
                    </div>
                    <div>
                        @if($this->activeConversation->status === 'AI_HANDLING')
                            <x-filament::button size="sm" wire:click="takeOver" color="warning" icon="heroicon-m-hand-raised">
                                Take Over
                            </x-filament::button>
                        @else
                            <x-filament::button size="sm" wire:click="returnToAi" color="success" icon="heroicon-m-cpu-chip">
                                Return to AI
                            </x-filament::button>
                        @endif
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-messages" id="chat-messages">
                    @forelse($this->messages as $message)
                        @php
                            $isCustomer = $message->sender_type === 'customer';
                            $isAi = $message->sender_type === 'ai';
                            $isAgent = $message->sender_type === 'human_agent';
                            
                            $bubbleClass = $isCustomer ? 'chat-bubble-customer' : ($isAi ? 'chat-bubble-ai' : 'chat-bubble-agent');
                        @endphp
                        
                        <div style="display: flex; flex-direction: column; align-items: {{ $isCustomer ? 'flex-start' : 'flex-end' }}; width: 100%;">
                            <span style="font-size: 0.65rem; opacity: 0.7; margin-bottom: 0.25rem;">
                                {{ $isAi ? '🤖 AI Assistant' : ($isAgent ? '👨‍💻 You (Agent)' : '👤 ' . $this->activeConversation->customer->name) }}
                            </span>
                            
                            <div class="chat-bubble {{ $bubbleClass }}">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            
                            <span style="font-size: 0.65rem; opacity: 0.5; margin-top: 0.25rem;">
                                {{ $message->created_at->format('H:i') }}
                                @if(!$isCustomer)
                                    <span style="margin-left: 0.25rem;">&bull; {{ ucfirst($message->status) }}</span>
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="chat-placeholder">
                            <p>No messages yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Input Area -->
                <div style="display: flex; flex-direction: column;">
                    <form wire:submit.prevent="sendMessage" class="chat-input-area">
                        <input type="text" 
                               wire:model="messageContent" 
                               placeholder="{{ $this->activeConversation->status === 'AI_HANDLING' ? 'Take over to type a message...' : 'Type a message...' }}" 
                               class="chat-input"
                               {{ $this->activeConversation->status === 'AI_HANDLING' ? 'disabled' : '' }}
                               autocomplete="off"
                        >
                        <x-filament::button type="submit" color="primary" icon="heroicon-m-paper-airplane" :disabled="$this->activeConversation->status === 'AI_HANDLING'">
                            Send
                        </x-filament::button>
                    </form>
                    @if($this->activeConversation->status === 'AI_HANDLING')
                        <div style="background-color: rgba(245, 158, 11, 0.1); color: #d97706; font-size: 0.75rem; text-align: center; padding: 0.5rem; font-weight: 500;">
                            ⚠️ You are in view-only mode. Click "Take Over" to pause AI and reply.
                        </div>
                    @endif
                </div>
            @else
                <div class="chat-placeholder">
                    <x-heroicon-o-chat-bubble-oval-left-ellipsis style="width: 4rem; height: 4rem; opacity: 0.2; margin-bottom: 1rem;" />
                    <p style="font-weight: 500;">Select a conversation to start messaging</p>
                </div>
            @endif
        </div>

        <!-- Right Column: Context & CRM Data -->
        <div class="chat-panel" style="padding: 1.25rem; overflow-y: auto;">
            @if($this->activeConversation)
                <h3 style="font-weight: 600; font-size: 1rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                    <x-heroicon-o-user style="width: 1.25rem; height: 1.25rem; color: rgba(var(--primary-500), 1);" />
                    Customer Context
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div>
                        <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; opacity: 0.5; display: block; margin-bottom: 0.25rem;">Name</span>
                        <span style="font-size: 0.875rem; font-weight: 600;">{{ $this->activeConversation->customer->name }}</span>
                    </div>
                    
                    <div>
                        <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; opacity: 0.5; display: block; margin-bottom: 0.25rem;">Phone Number</span>
                        <span style="font-size: 0.875rem;">{{ $this->activeConversation->customer->phone_number }}</span>
                    </div>
                    
                    <div style="background-color: rgba(var(--primary-500), 0.05); border: 1px solid rgba(var(--primary-500), 0.1); border-radius: 0.75rem; padding: 1rem;">
                        <span style="color: rgba(var(--primary-600), 1); font-size: 0.65rem; text-transform: uppercase; font-weight: 700; display: flex; align-items: center; gap: 0.25rem; margin-bottom: 0.5rem;">
                            <x-heroicon-o-sparkles style="width: 0.875rem; height: 0.875rem;" />
                            AI Brain Summary
                        </span>
                        <p style="font-size: 0.75rem; font-style: italic; opacity: 0.8; line-height: 1.5;">
                            {{ $this->activeConversation->customer->ai_summary ?? 'The AI is currently analyzing this customer. Check back later after a few interactions.' }}
                        </p>
                    </div>

                    <div>
                        <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; opacity: 0.5; display: block; margin-bottom: 0.5rem;">Customer Tags</span>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.375rem;">
                            @if(is_array($this->activeConversation->customer->tags) && count($this->activeConversation->customer->tags) > 0)
                                @foreach($this->activeConversation->customer->tags as $tag)
                                    <x-filament::badge color="info" size="xs">
                                        {{ $tag }}
                                    </x-filament::badge>
                                @endforeach
                            @else
                                <span style="font-size: 0.75rem; font-style: italic; opacity: 0.5;">No tags extracted yet</span>
                            @endif
                        </div>
                    </div>

                    <div style="border-top: 1px solid rgba(0,0,0,0.05); padding-top: 1rem;">
                        <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; opacity: 0.5; display: block; margin-bottom: 0.5rem;">System Info</span>
                        <div style="font-size: 0.75rem; opacity: 0.7; display: flex; flex-direction: column; gap: 0.375rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>First seen:</span>
                                <span style="font-weight: 600;">{{ $this->activeConversation->customer->first_interaction_at?->format('d M Y') ?? '-' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Channel:</span>
                                <span style="font-weight: 600;">{{ $this->activeConversation->whatsappAccount->phone_number ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="chat-placeholder">
                    <x-heroicon-o-document-text style="width: 3rem; height: 3rem; opacity: 0.2; margin-bottom: 1rem;" />
                    <p style="font-size: 0.875rem;">Context will appear here</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Scroll to bottom logic for chat -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollToBottom = () => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            };
            
            scrollToBottom();
            
            Livewire.hook('morph.updated', (el, component) => {
                if (el.id === 'chat-messages' || (el.querySelector && el.querySelector('#chat-messages'))) {
                    setTimeout(scrollToBottom, 50);
                }
            });
        });
    </script>
</x-filament-panels::page>
