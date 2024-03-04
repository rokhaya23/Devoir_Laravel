<?php

namespace App\Http\Controllers;

use App\Exports\ProduitsExport;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new ProduitsExport , 'produit.xlsx');
    }
}
