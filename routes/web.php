<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::get('/loa/{id}/print', function ($id) {
    $loa = \App\Models\LetterOfAgreement::with('lead')->findOrFail($id);
    return view('loa.print', compact('loa'));
})->name('loa.print')->middleware(['auth']);

Route::get('/ticket/{uuid}/download', function ($uuid) {
    $booking = \App\Models\Booking::with('tickets', 'items.tourPackage')->where('uuid', $uuid)->firstOrFail();
    
    // Mengembalikan view HTML Premium E-Ticket (mendukung multiple tickets)
    return view('tickets.print-multiple', compact('booking'));
})->name('ticket.download');

use App\Http\Controllers\CheckInController;

Route::middleware(['auth'])->group(function () {
    Route::get('/scanner', [CheckInController::class, 'scanner'])->name('scanner');
    Route::post('/api/tickets/validate/{ticketNumber}', [CheckInController::class, 'validateTicket'])->name('api.tickets.validate');
    Route::get('/api/tickets/validate/{ticketNumber}', [CheckInController::class, 'validateTicketGet'])->name('api.tickets.validate.get');
    Route::get('/tickets/{ticket}/print', [CheckInController::class, 'printTicket'])->name('ticket.print');
    Route::get('/tickets/{ticket}/print-manual', [CheckInController::class, 'printManualTicket'])->name('ticket.print_manual');
});

use App\Http\Controllers\BookingController;

Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/invoice/{uuid}', [BookingController::class, 'invoice'])->name('invoice');
Route::get('/booking/{uuid}/pos-print', [BookingController::class, 'posPrint'])->name('booking.pos_print');
// Route::get('/invoice/{uuid}/download', [BookingController::class, 'downloadPdf'])->name('ticket.download_old');

// Route for LOA PDF
Route::get('/loa/{loa}/pdf', [\App\Http\Controllers\LoaController::class, 'downloadPdf'])->name('loa.pdf');
