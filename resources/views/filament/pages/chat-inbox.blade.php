<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-[calc(100vh-10rem)] min-h-[600px]" wire:poll.3s>
        
        <!-- Left Column: Conversations List -->
        <div class="col-span-1 bg-white dark:bg-gray-900 shadow-sm rounded-xl ring-1 ring-gray-950/5 dark:ring-white/10 overflow-y-auto flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 sticky top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-10">
                <h3 class="font-semibold text-gray-950 dark:text-white">Conversations</h3>
            </div>
            <div class="flex flex-col divide-y divide-gray-200 dark:divide-white/10 flex-1">
                @forelse($this->conversations as $conversation)
                    <button wire:click="loadConversation({{ $conversation->id }})" 
                            class="p-4 text-left transition hover:bg-gray-50 dark:hover:bg-white/5 {{ $activeConversationId === $conversation->id ? 'bg-primary-50 dark:bg-primary-500/10' : '' }}">
                        <div class="flex justify-between items-start">
                            <span class="font-medium text-sm text-gray-950 dark:text-white">{{ $conversation->customer->name ?? 'Unknown' }}</span>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">{{ $conversation->last_message_at?->diffForHumans(null, true, true) }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
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
                    <div class="p-8 text-center text-gray-400 text-sm flex-1 flex items-center justify-center">
                        No active conversations
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Center Column: Chat Window -->
        <div class="col-span-2 bg-white dark:bg-gray-900 shadow-sm rounded-xl ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col relative overflow-hidden">
            @if($this->activeConversation)
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-white/5 absolute top-0 w-full z-10 backdrop-blur-md">
                    <div>
                        <h3 class="font-semibold text-gray-950 dark:text-white">{{ $this->activeConversation->customer->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $this->activeConversation->customer->phone_number }}</p>
                    </div>
                    <div class="flex gap-2">
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
                <div class="flex-1 p-4 overflow-y-auto space-y-4 pt-24 pb-24 bg-gray-50/50 dark:bg-gray-900/50" id="chat-messages">
                    @forelse($this->messages as $message)
                        @php
                            $isCustomer = $message->sender_type === 'customer';
                            $isAi = $message->sender_type === 'ai';
                            $isAgent = $message->sender_type === 'human_agent';
                        @endphp
                        <div class="flex flex-col {{ $isCustomer ? 'items-start' : 'items-end' }}">
                            <span class="text-[10px] text-gray-500 mb-1 px-1 font-medium">
                                {{ $isAi ? '🤖 AI Assistant' : ($isAgent ? '👨‍💻 You (Agent)' : '👤 ' . $this->activeConversation->customer->name) }}
                            </span>
                            <div class="max-w-[85%] px-4 py-2.5 rounded-2xl shadow-sm 
                                {{ $isCustomer ? 'bg-white dark:bg-gray-800 ring-1 ring-gray-950/5 dark:ring-white/10 text-gray-900 dark:text-gray-100 rounded-bl-sm' : 
                                  ($isAi ? 'bg-success-100 dark:bg-success-900/40 text-success-900 dark:text-success-100 ring-1 ring-success-500/20 rounded-br-sm' : 
                                           'bg-primary-600 text-white rounded-br-sm') }}">
                                <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $message->content }}</p>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1 px-1">
                                {{ $message->created_at->format('H:i') }} 
                                @if(!$isCustomer)
                                    <span class="ml-1 opacity-75">&bull; {{ ucfirst($message->status) }}</span>
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                            <p>No messages yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Input Area -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 absolute bottom-0 w-full z-10">
                    <form wire:submit.prevent="sendMessage" class="flex gap-3">
                        <input type="text" 
                               wire:model="messageContent" 
                               placeholder="{{ $this->activeConversation->status === 'AI_HANDLING' ? 'Take over to type a message...' : 'Type a message...' }}" 
                               class="flex-1 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm disabled:opacity-50 disabled:bg-gray-50 dark:disabled:bg-gray-900" 
                               {{ $this->activeConversation->status === 'AI_HANDLING' ? 'disabled' : '' }}
                               autocomplete="off"
                        >
                        <x-filament::button type="submit" color="primary" icon="heroicon-m-paper-airplane" :disabled="$this->activeConversation->status === 'AI_HANDLING'">
                            Send
                        </x-filament::button>
                    </form>
                    @if($this->activeConversation->status === 'AI_HANDLING')
                        <p class="text-[10px] text-warning-600 dark:text-warning-400 mt-2 text-center font-medium">⚠️ You are in view-only mode. Click "Take Over" to pause AI and reply.</p>
                    @endif
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400 text-center flex-col gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="p-5 rounded-full bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                        <x-heroicon-o-chat-bubble-oval-left-ellipsis class="w-12 h-12 text-gray-300 dark:text-gray-600" />
                    </div>
                    <p class="text-sm font-medium text-gray-500">Select a conversation to start messaging</p>
                </div>
            @endif
        </div>

        <!-- Right Column: Context & CRM Data -->
        <div class="col-span-1 bg-white dark:bg-gray-900 shadow-sm rounded-xl ring-1 ring-gray-950/5 dark:ring-white/10 p-5 overflow-y-auto">
            @if($this->activeConversation)
                <h3 class="font-semibold text-gray-950 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-800 pb-3 flex items-center gap-2">
                    <x-heroicon-o-user class="w-4 h-4 text-primary-500"/>
                    Customer Context
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <span class="text-gray-500 block text-[10px] uppercase tracking-wider font-bold mb-1">Name</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $this->activeConversation->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-[10px] uppercase tracking-wider font-bold mb-1">Phone Number</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $this->activeConversation->customer->phone_number }}</span>
                    </div>
                    
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100/50 dark:from-primary-900/20 dark:to-primary-800/10 rounded-xl p-4 ring-1 ring-primary-500/20">
                        <span class="text-primary-700 dark:text-primary-400 block text-[10px] uppercase tracking-wider font-bold mb-2 flex items-center gap-1.5">
                            <x-heroicon-o-sparkles class="w-3.5 h-3.5"/>
                            AI Brain Summary
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 italic text-xs leading-relaxed">
                            {{ $this->activeConversation->customer->ai_summary ?? 'The AI is currently analyzing this customer. Check back later after a few interactions.' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-[10px] uppercase tracking-wider font-bold mb-2">Customer Tags</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if(is_array($this->activeConversation->customer->tags) && count($this->activeConversation->customer->tags) > 0)
                                @foreach($this->activeConversation->customer->tags as $tag)
                                    <x-filament::badge color="info" size="xs">
                                        {{ $tag }}
                                    </x-filament::badge>
                                @endforeach
                            @else
                                <span class="text-gray-400 italic text-xs">No tags extracted yet</span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                        <span class="text-gray-500 block text-[10px] uppercase tracking-wider font-bold mb-2">System Info</span>
                        <div class="text-[11px] text-gray-500 space-y-1.5">
                            <p class="flex justify-between"><span>First seen:</span> <span class="font-medium text-gray-700 dark:text-gray-300">{{ $this->activeConversation->customer->first_interaction_at?->format('d M Y') ?? '-' }}</span></p>
                            <p class="flex justify-between"><span>Channel:</span> <span class="font-medium text-gray-700 dark:text-gray-300">{{ $this->activeConversation->whatsappAccount->phone_number ?? '-' }}</span></p>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex h-full items-center justify-center text-gray-400 text-center flex-col gap-3">
                    <x-heroicon-o-document-text class="w-8 h-8 opacity-50" />
                    <p class="text-xs">Context will appear here</p>
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
                scrollToBottom();
            });
        });
    </script>
</x-filament-panels::page>
