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
    // CommandeController.php

    public function update(Request $request, $orderId)
{
    // Validation des données
    $request->validate([
        'produits' => 'required|array',
        'produits.*.id' => 'required|exists:produits,id',
        'produits.*.pivot.quantity' => 'required|integer|min:1',
    ]);

    // Trouver la commande
    $commande = Commande::find($orderId);
    if (!$commande) {
        return response()->json(['error' => 'Commande non trouvée'], 404);
    }

    // Obtenir les produits existants dans la commande pour calculer les changements de stock
    $existingProducts = $commande->produits->keyBy('id');
    
    // Créer une collection pour les nouveaux produits ajoutés
    $newProducts = collect($request->produits)->keyBy('id');
    
    // Traiter les produits existants et les nouveaux produits
    foreach ($existingProducts as $productId => $existingProduct) {
        if (isset($newProducts[$productId])) {
            $newQuantity = $newProducts[$productId]['pivot']['quantity'];
            $existingQuantity = $existingProduct->pivot->quantity;
            
            // Calculer la différence de quantité
            $quantityDifference = $newQuantity - $existingQuantity;

            // Mettre à jour le produit dans la commande
            $commande->produits()->updateExistingPivot($productId, [
                'quantity' => $newQuantity,
                'totale' => $newQuantity * Produit::find($productId)->prix
            ]);

            // Ajuster le stock du produit
            $produit = Produit::find($productId);
            if ($produit) {
                $produit->quantite_stock -= $quantityDifference;
                $produit->save();
            }
        } else {
            // Supprimer le produit qui a été retiré de la commande
            $quantityToRestore = $existingProduct->pivot->quantity;
            $produit = Produit::find($productId);
            if ($produit) {
                $produit->quantite_stock += $quantityToRestore;
                $produit->save();
            }
            $commande->produits()->detach($productId);
        }
    }

    // Ajouter les nouveaux produits qui ne sont pas encore dans la commande
    foreach ($newProducts as $productId => $product) {
        if (!isset($existingProducts[$productId])) {
            $newQuantity = $product['pivot']['quantity'];
            
            // Ajouter le produit à la commande
            $commande->produits()->attach($productId, [
                'quantity' => $newQuantity,
                'totale' => $newQuantity * Produit::find($productId)->prix
            ]);
            
            // Ajuster le stock du produit
            $produit = Produit::find($productId);
            if ($produit) {
                $produit->quantite_stock -= $newQuantity;
                $produit->save();
            }
        }
    }

    return response()->json(['success' => 'Commande mise à jour avec succès'], 200);
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
public function removeProductFromOrder($orderId, $productId)
{
    // Trouver la commande
    $commande = Commande::find($orderId);
    if (!$commande) {
        return response()->json(['error' => 'Commande non trouvée'], 404);
    }

    // Trouver le produit dans la commande
    $pivot = $commande->produits()->wherePivot('idProduct', $productId)->first();
    if (!$pivot) {
        return response()->json(['error' => 'Produit non trouvé dans la commande'], 404);
    }

    // Restaurer le stock du produit
    $produit = Produit::find($productId);
    if ($produit) {
        $produit->quantite_stock += $pivot->pivot->quantity;
        $produit->save();
    }

    // Supprimer le produit de la commande
    $commande->produits()->detach($productId);

    return response()->json(['success' => 'Produit supprimé avec succès'], 200);
}


}
