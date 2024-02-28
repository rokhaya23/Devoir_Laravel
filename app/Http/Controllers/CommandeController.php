<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::all();
        return view('commande.index', compact('commandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $produits = Produit::all();

        return view('commande.FormCommande', compact('clients', 'produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'produit_id' => 'required|array',
            'quantite' => 'required|array',
            // ... autres règles de validation pour vos champs
        ]);

        // Extraire les attributs liés au client
        $clientAttributes = $request->only(['adresse', 'telephone', 'sexe']);

        // Créer une nouvelle commande
        $commande = new Commande;
        $commande->idClient = $request->input('client_id');
        $commande->date_commande = $request->input('date_commande');
        $commande->total_amount = 0; // Vous devrez peut-être mettre à jour cela en fonction de votre logique

        // Charger le client et les produits avec leurs relations
        $commande->load(['client', 'produits']);

        // Mettre à jour les attributs du client
        $commande->client->update($clientAttributes);

        // Gérer les produits associés à la commande
        foreach ($request->input('produit_id') as $key => $produit_id) {
            // Trouver le produit
            $produit = Produit::findOrFail($produit_id);

            // Calculer le montant total pour ce produit
            $prix_total = $produit->prix * $request->input('quantite')[$key];

            // Attacher le produit à la commande
            $commande->produits()->attach($produit_id, [
                'quantity' => $request->input('quantite')[$key],
                'prix_unitaire' => $produit->prix,
                'prix_total' => $prix_total,
            ]);

            // Mettre à jour total_amount pour la commande
            $commande->total_amount += $prix_total;
        }

        // Sauvegarder le total_amount mis à jour
        $commande->save();

        return redirect()->route('commande.FormCommande')->with('success', 'Commande créée avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
