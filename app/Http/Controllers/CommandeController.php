<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Pivot;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-orders', ['only' => ['create', 'update', 'store', 'edit']]);
        $this->middleware('permission:lists_orders', ['only' => ['index', 'show', 'destroy']]);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::with('produits', 'client')->get();
        $clients = Client::all();
        $produits = Produit::all();
        return view('commande.ListeCommandes', compact('commandes', 'clients', 'produits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commande = new Commande();

        $clients = Client::all();
        $produits = Produit::all();

        return view('commande.FormCommande', compact('clients', 'produits', 'commande'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'idClient' => 'required',
            'date_commande' => 'required',
            'idProduct' => 'required|array',
            'produits.*.idProduct' => 'required',
            'produits.*.quantity' => 'required|numeric|min:1',
        ]);

        // Utilisation d'une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        // Création de la commande
        $commande = Commande::create([
            'idClient' => $request->input('idClient'),
            'date_commande' => $request->input('date_commande'),
            'status' => 'En Attente',
        ]);

        // Ajout des produits à la commande avec la quantité et le total dans la table pivot_commandes
        foreach ($request->input('idProduct') as $index => $idProduct) {
            $produit = Produit::find($idProduct);

            if ($produit) {
                $quantity = $request->input('quantity')[$index];
                $total = $produit->prix * $quantity;

                Pivot::create([
                    'idProduct' => $produit->id,
                    'idCommande' => $commande->id,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);

                // Mettez à jour la quantité en stock du produit
                $produit->decrement('quantite_stock', $quantity);
            }
        }

        // Si tout s'est bien déroulé, valide la transaction
        DB::commit();

        return redirect()->route('commande.index')->with('success', 'Commande créée avec succès');
    }


    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {

        return view('commande.DetailsCommande', compact('commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        $clients = Client::all();
        $produits = Produit::all();
        // Chargez les produits associés à la commande
        $commande->load('produits');

        return view('commande.FormCommande', compact('commande', 'clients', 'produits'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        // Validation des données de la requête pour la mise à jour
        $request->validate([
            'idClient' => 'required',
            'date_commande' => 'required',
            'idProduct' => 'required|array',
            'quantity' => 'required|array',
        ]);

        // Utilisation d'une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        $oldQuantities = [];

        foreach ($commande->produits as $produit) {
            $oldQuantities[$produit->id] = $produit->pivot->quantity;
        }

        // Mise à jour de la commande
        $commande->update([
            'idClient' => $request->input('idClient'),
            'date_commande' => $request->input('date_commande'),
        ]);

        // Suppression des produits associés à la commande dans la table pivot_commande
        $commande->produits()->detach();

        // Ajout des produits mis à jour à la commande avec la quantité et le total dans la table pivot_commande
        foreach ($request->input('idProduct') as $index => $idProduct) {
            $produit = Produit::find($idProduct);

            if ($produit) {
                $quantity = $request->input('quantity')[$index];
                $total = $produit->prix * $quantity;

                $commande->produits()->attach($produit->id, [
                    'quantity' => $quantity,
                    'total' => $total,
                ]);

                // Mettez à jour la quantité en stock du produit
                $produit->decrement('quantite_stock', $quantity);

                // Restituer la quantité précédente au stock
                if (isset($oldQuantities[$produit->id])) {
                    $produit->increment('quantite_stock', $oldQuantities[$produit->id]);
                }
            }
        }

        // Si tout s'est bien déroulé, valide la transaction
        DB::commit();

            return redirect()->route('commande.index')->with('success', 'Commande mise à jour avec succès');

    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Commande $commande)
    {
        // Utilisation d'une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

            // Vérifiez le statut de la commande avant de permettre la suppression
            if ($commande->status === 'En Attente') {
                // Récupérez les informations sur les produits liés à la commande
                $productsInfo = [];

                foreach ($commande->produits as $produit) {
                    $productsInfo[] = [
                        'id' => $produit->id,
                        'quantity' => $produit->pivot->quantity,
                    ];
                }

                // Supprimez la commande et ses relations
                $commande->produits()->detach();
                $commande->delete();

                // Restituer la quantité au stock
                foreach ($productsInfo as $productInfo) {
                    $produit = Produit::find($productInfo['id']);
                    if ($produit) {
                        $produit->increment('quantite_stock', $productInfo['quantity']);
                    }
                }

                // Si tout s'est bien déroulé, valide la transaction
                DB::commit();

            }
        }

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
