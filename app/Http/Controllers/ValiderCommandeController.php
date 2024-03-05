<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class ValiderCommandeController extends Controller
{
    public function List()
    {
        $commandes= Commande::all();

        return view('commande.ListeValiderCommande', compact('commandes'));
    }

    public function showList(Commande $commande)
    {
        return view('commande.ValiderCommande' ,compact('commande'));
    }

    public function updateStatus(Request $request, Commande $commande)
    {
        {
            // Validez les données du formulaire
            $request->validate([
                'newStatus' => ['required', 'in:En Attente,Acceptée,Livrée'],
            ]);

            // Mettez à jour le statut de la commande
            $commande->update(['status' => $request->input('newStatus')]);

            // Redirigez l'utilisateur vers la page des détails de la commande
            return redirect()->route('commande.index', ['commande' => $commande->id])->with('success', 'Statut mis à jour avec succès.');
        }
    }
}
