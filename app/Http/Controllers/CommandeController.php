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


class CommandeController extends Controller
{
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

        Auth::login($user);

        return $this->createOrderForUser($request, $user);
    }

    // Méthode pour créer une commande pour un utilisateur donné
    private function createOrderForUser(Request $request, Utilisateur $user)
    {
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
            // 'status' => 'pending',
        ]);

        foreach ($request->products as $product) {
            $produit = Produit::find($product['id']);
            Pivot::create([
                'idProduct' => $produit->id,
                'idCommande' => $commande->id,
                'quantity' => $product['quantity'],
                'totale' => $produit->prix * $product['quantity'],
            ]);
        }

        return response()->json(['message' => 'Commande créée avec succès', 'commande' => $commande], 201);
    }
}
