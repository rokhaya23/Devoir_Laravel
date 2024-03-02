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
        $commandes = Commande::with('produits')->get();
        $clients = Client::all();
        $produits = Produit::all();
        return view('commande.FormCommande', compact('commandes','clients','produits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commande = new Commande();

        $clients = Client::all();
        $produits = Produit::all();

        return view('commande.FormCommande', compact('clients', 'produits','commande'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'idClient' => 'required|exists:clients,id',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
            'sexe' => 'required|string',
            'date_commande' => 'required|date',
            'idProduct.*' => 'required|exists:produits,id',
            'quantity.*' => 'required|integer|min:1',
            'total_amount' => 'required',
        ]);

        $commande = new Commande;
        $commande->idClient = $request->input('idClient');
        $commande->total_amount = 0; // Initialisez le total_amount
        $commande->date_commande = $request->input('date_commande');
        $commande->save();

        $commandeId = $commande->id;

        $clientAttributes = [
            'nom' => $request->user()->nom,
            'adresse' => $request->input('adresse'),
            'telephone' => $request->input('telephone'),
            'sexe' => $request->input('sexe'),
        ];

        $commande->client()->create($clientAttributes);

        $totalAmount = 0;

        foreach ($request->input('idProduct') as $key => $idProduct) {
            $produit = Produit::findOrFail($idProduct);
            $subtotal = $produit->prix * $request->input('quantity')[$key];
            $totalAmount += $subtotal;

            // Utilisez sync pour gérer la relation many-to-many avec des données pivot
            $commande->produits()->sync([$idProduct => [
                'quantity' => $request->input('quantity')[$key],
                'subtotal' => $subtotal,
            ]], false);
        }

        // Mettez à jour le total_amount avant de sauvegarder
        $commande->total_amount = $totalAmount;
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
        $commande = Commande::with('client', 'produits')->findOrFail($id);
        $clients = Client::all();
        $produits = Produit::all();

        return view('commande.FormCommande', compact('commande', 'clients', 'produits'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'idClient' => 'required|exists:clients,id',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
            'sexe' => 'required|string',
            'date_commande' => 'required|date',
            'idProduct.*' => 'required|exists:produits,id',
            'quantity.*' => 'required|integer|min:1',
            // ... (ajoutez d'autres règles de validation au besoin)
        ]);

        $commande = Commande::findOrFail($id);
        $commande->load(['client', 'produits']);
        $clientAttributes = [
            'adresse' => $request->input('adresse'),
            'telephone' => $request->input('telephone'),
            'sexe' => $request->input('sexe'),
        ];

        $commande->client->update($clientAttributes);
        $commande->produits()->detach();
        $commande->total_amount = 0;

        foreach ($request->input('idProduct') as $key => $produit_id) {
            $produit = Produit::findOrFail($produit_id);
            $subtotal = $produit->prix * $request->input('quantity')[$key];

            $commande->produits()->attach($produit_id, [
                'idCommande' => $commande->id,
                'quantity' => $request->input('quantity')[$key],
                'subtotal' => $subtotal,
            ]);

            $commande->total_amount += $subtotal;
        }

        $commande->save();

        return redirect()->route('commande.FormCommande')->with('success', 'Commande mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */




    public function getCustomerDetails($id)
    {
        // Récupérer les détails du client en fonction de l'ID
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        // Utiliser les noms de colonnes appropriés
        return response()->json([
            'adresse' => $client->adresse,
            'telephone' => $client->telephone,
            'sexe' => $client->sexe,
        ]);
    }

    public function getProductDetails($id)
    {
        // Récupérer les détails du produit en fonction de l'ID
        $product = Produit::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'prix' => $product->prix,
            'quantite_stock' => $product->quantite_stock,
        ]);
    }


}
