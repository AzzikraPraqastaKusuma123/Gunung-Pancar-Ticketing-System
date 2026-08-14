<?php

namespace App\Http\Controllers;

use App\Models\LetterOfAgreement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoaController extends Controller
{
    public function downloadPdf(LetterOfAgreement $loa)
    {
        $pdf = Pdf::loadView('pdf.loa', compact('loa'));
        return $pdf->stream('LOA-' . $loa->document_number . '.pdf');
    }
}
