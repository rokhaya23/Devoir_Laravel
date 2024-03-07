<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $clients = Client::all();

        $pdf = PDF::loadView('client.client-pdf', compact('clients'));

        return $pdf->download('Liste-Client.pdf');
    }
}
