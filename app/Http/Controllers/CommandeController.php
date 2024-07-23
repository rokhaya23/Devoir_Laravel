<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Commande;
use App\Models\Pivot;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class CommandeController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Récupérer l'utilisateur connecté
        if ($user) {
            $commandes = Commande::with('produits', 'user')
                ->where('idUser', $user->id) // Filtrer par ID utilisateur
                ->get();
            return response()->json($commandes);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // Méthode pour créer une commande pour un utilisateur connecté
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        return $this->createOrderForUser($request, $user);
    }

    // Méthode pour s'inscrire et créer une commande en même temps
    public function registerAndCreateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'departement' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'departement' => $request->departement,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Client');
        $token = $user->createToken('auth-token', ['*'], now()->addMinutes(120))->plainTextToken;


        Auth::login($user);

        // Proceed with order creation
        $response = $this->createOrderForUser($request, $user);

        // Return response with token
        return response()->json([
            'message' => 'User registered and order created successfully!',
            'token' => $token,
            'user' => $user,
            'response' => $response
        ], 201);
    }

    // Méthode pour créer une commande pour un utilisateur donné
    private function createOrderForUser(Request $request, Utilisateur $user)
    {
        $user->update([
            'prenom' => $request->input('prenom'),
            'nom' => $request->input('nom'),
            'telephone' => $request->input('telephone'),
            'adresse' => $request->input('adresse'),
            'departement' => $request->input('departement'),
            'email' => $request->input('email'), // Assurez-vous que cette adresse email est unique
        ]);


        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:produits,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $commande = Commande::create([
            'idUser' => $user->id,
            'date_commande' => now(),
            'statut' => 'Pending',
        ]);

        foreach ($request->products as $product) {
            $produit = Produit::find($product['id']);
    
            // Vérifier si la quantité demandée est disponible
            if ($produit->quantite_stock < $product['quantity']) {
                return response()->json(['error' => 'La quantité demandée pour le produit ' . $produit->nom . ' dépasse la quantité disponible.'], 400);
            }
    
            Pivot::create([
                'idProduct' => $produit->id,
                'idCommande' => $commande->id,
                'quantity' => $product['quantity'],
                'totale' => $produit->prix * $product['quantity'],
            ]);
    
            // Décrémenter la quantité du produit
            $produit->quantite_stock -= $product['quantity'];
            $produit->save();
        }
    
        return response()->json(['message' => 'Commande créée avec succès', 'commande' => $commande], 201);
    }

    public function show($id)
    {
        $user = auth()->user();
        $commande = Commande::with('produits')->where('idUser', $user->id)->findOrFail($id);
        return response()->json($commande);
    }

    // Update a specific order
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $commande = Commande::where('idUser', $user->id)->findOrFail($id);

        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:produits,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $commande->produits()->sync(
            collect($validatedData['products'])->mapWithKeys(function ($product) {
                return [$product['id'] => ['quantity' => $product['quantity']]];
            })->toArray()
        );

        $commande->save();

        return response()->json(['message' => 'Order updated successfully', 'order' => $commande]);
    }

    public function fetchValidatedOrders()
{
    try {
        $orders = Commande::with('user') // Assurez-vous que la relation 'user' est définie correctement dans votre modèle Commande
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'userName' => $order->user->nom . ' ' . $order->user->prenom, // Assurez-vous que 'name' est bien le champ contenant le nom de l'utilisateur
                    'status' => $order->status
                ];
            });

        return response()->json($orders);
    } catch (\Exception $e) {
        // Log l'erreur pour le débogage
        \Log::error('Erreur lors de la récupération des commandes : ' . $e->getMessage());

        // Retourner une réponse JSON avec un code d'erreur HTTP
        return response()->json(['error' => 'Impossible de récupérer les commandes'], 500);
    }
}


public function updateOrderStatus(Request $request, $id)
{
    $order = Commande::findOrFail($id);
    $order->status = $request->input('status');
    $order->save();

    return response()->json($order);
}


}
