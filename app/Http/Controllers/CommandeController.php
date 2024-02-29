<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-orders', ['only' => ['index','create','update','store','edit','destroy']]);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::all();
        $clients = Client::all();
        $produits = Produit::all();
        return view('commande.FormCommande', compact('commandes','clients','produits'));
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
            'idClient' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'idProdduct' => 'required|array',
            'total_amount' => 'required|array',
        ]);

        // Extraire les attributs liés au client
        $clientAttributes = $request->only(['adresse', 'telephone', 'sexe']);

        // Créer une nouvelle commande
        $commande = new Commande;
        $commande->idClient = $request->input('idClient');
        $commande->date_commande = $request->input('date_commande');
        $commande->total_amount = 0;

        // Charger le client et les produits avec leurs relations
        $commande->load(['client', 'produits']);

        // Mettre à jour les attributs du client
        $commande->client->update($clientAttributes);

        // Gérer les produits associés à la commande
        foreach ($request->input('idProduct') as $key => $produit_id) {
            // Trouver le produit
            $produit = Produit::findOrFail($produit_id);

            // Calculer le montant total pour ce produit
            $prix_total = $produit->prix * $request->input('quantite')[$key];

            // Attacher le produit à la commande
            $commande->produits()->attach($produit_id, [
                'quantity' => $request->input('quantite')[$key],
                'prix' => $produit->prix,
                'total_amount' => $prix_total,
            ]);

            // Mettre à jour total_amount pour la commande
            $commande->total_amount += $prix_total;
        }

        // Sauvegarder le total_amount mis à jour
        $commande->save();

        $selectedClient = Client::findOrFail($request->input('idClient'));

        return redirect()->route('commande.FormCommande')->with('success', 'Commande créée avec succès')->with('selectedClient', $selectedClient);

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
        $commande = Commande::with('client', 'produits')->findOrFail($id);
        $clients = Client::all();
        $produits = Produit::all();

        return view('commande.edit', compact('commande', 'clients', 'produits'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'idClient' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'idProdduct' => 'required|array',
            'total_amount' => 'required|array',
        ]);

        // Extract client-related attributes
        $clientAttributes = $request->only(['adresse', 'telephone', 'sexe']);

        // Find the Commande
        $commande = Commande::findOrFail($id);

        // Update the Commande
        $commande->update([
            'idClient' => $request->input('idClient'),
            'date_commande' => $request->input('date_commande'),
        ]);

        // Load the client and produits with their relations
        $commande->load(['client', 'produits']);

        // Update the client attributes
        $commande->client->update($clientAttributes);

        $commande->produits()->sync();

        // Now, handle the products associated with the commande
        foreach ($request->input('idProduct') as $key => $produit_id) {
            // Find the Produit
            $produit = Produit::findOrFail($produit_id);

            // Calculate total amount for this product
            $prix_total = $produit->prix * $request->input('quantity')[$key];

            // Attach the produit to the commande
            $commande->produits()->attach($produit_id, [
                'quantity' => $request->input('quantity')[$key],
                'prix' => $produit->prix,
                'total_amount' => $prix_total,
            ]);

            // Update total_amount for the commande
            $commande->total_amount += $prix_total;
        }

        // Save the updated total_amount
        $commande->save();

        return redirect()->route('commande.FormCommande')->with('success', 'Commande updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();

        return redirect()->route('commande.FormCommande')->with('success', 'Commande deleted successfully');

    }
}
