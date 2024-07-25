<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function index()
    {

        $inStock = Produit::where('quantite_stock', '>', 0)->count();
        $outOfStock = Produit::where('quantite_stock', 0)->count();
        $userCount = Utilisateur::count();
        $deliveredOrdersCount = Commande::where('status', 'Delivered')->count();
        $pendingOrdersCount = Commande::where('status', 'Pending')->count();
        $totalRevenue = DB::table('pivot_commandes')
            ->join('commandes', 'pivot_commandes.idCommande', '=', 'commandes.id')
            ->where('commandes.status', 'Delivered')
            ->sum('pivot_commandes.totale');


        return response()->json([
            'inStock' => $inStock,
            'outOfStock' => $outOfStock,
            'userCount' => $userCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'totalRevenue' => $totalRevenue
        ]);
    }
}
