<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function exportPDF()
    {
        $clients = Client::all();

        $pdf = Pdf::loadView('client.client-pdf', compact('clients'));

        return $pdf->download('clients-list.pdf');
    }
}
