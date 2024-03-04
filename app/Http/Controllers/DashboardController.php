<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dashboard', ['only' => ['index']]);

    }

    public function index()
    {

        $totalClients = Client::count();
        $totalProduits = Produit::count();
        $nbClientsMasculin = Client::where('sexe', 'M')->count();
        $nbClientsFeminin = Client::where('sexe', 'F')->count();

        return view('dashboard', [
            'totalClients' => $totalClients,
            'totalProduits' => $totalProduits,
            'nbClientsMasculin' => $nbClientsMasculin,
            'nbClientsFeminin' => $nbClientsFeminin,
        ]);
    }
}
