<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WhatsappWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// WhatsApp Webhook Routes
Route::prefix('webhooks/whatsapp')->group(function () {
    Route::get('/receive', [WhatsappWebhookController::class, 'verify']);
    Route::post('/receive', [WhatsappWebhookController::class, 'receive']);
    Route::post('/status', [WhatsappWebhookController::class, 'status']);
});

// Midtrans Webhook Route
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handleWebhook']);
