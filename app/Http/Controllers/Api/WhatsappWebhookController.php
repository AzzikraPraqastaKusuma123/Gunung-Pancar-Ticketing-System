<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
    protected WhatsappService $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle Meta Webhook Verification (GET request)
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = env('WA_WEBHOOK_VERIFY_TOKEN');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                Log::info('WhatsApp Webhook verified successfully.');
                return response($challenge, 200);
            } else {
                Log::warning('WhatsApp Webhook verification failed.');
                return response()->json(['status' => 'error'], 403);
            }
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Handle incoming WhatsApp messages
     */
    public function receive(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Incoming WhatsApp Webhook: ', $payload);

            // Dispatch job to process the message in the background
            \App\Jobs\ProcessIncomingMessageJob::dispatch($payload);

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Error handling WhatsApp webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle message delivery status updates
     */
    public function status(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('WhatsApp Status Update: ', $payload);

            $this->whatsappService->handleStatusUpdate($payload);

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Error handling WhatsApp status: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
