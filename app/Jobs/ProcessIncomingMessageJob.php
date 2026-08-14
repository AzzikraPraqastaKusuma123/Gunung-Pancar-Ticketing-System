<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class ProcessIncomingMessageJob implements ShouldQueue
{
    use Queueable;

    protected $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $whatsappService, \App\Services\GeminiService $geminiService): void
    {
        Log::info('Processing WhatsApp message in background Job.');
        $conversation = $whatsappService->handleIncomingMessage($this->payload);

        if ($conversation) {
            $geminiService->generateReply($conversation, $whatsappService);
        }
    }
}
