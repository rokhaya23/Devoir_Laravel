<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitRequest;
use App\Models\Utilisateur;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    // }

    public function index(Request $request)
{
    try {
        // Set the number of items per page
        $limit = $request->get('limit', 9); // Default to 10 items per page
        
        // Retrieve paginated products
        $produits = Produit::paginate($limit);

        return response()->json([
            'status' => 200,
            'message' => 'Liste des produits',
            'produits' => $produits
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => $e->getCode(),
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function store(ProduitRequest $request)
    {
        $produit = new Produit([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'quantite_stock' => $request->quantite_stock,
            'idCategory' => $request->idCategory,
        ]);

        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('public/photos');
            $produit->photo = basename($imagePath);
        }

        $produit->category()->associate($request->idCategory);
        $produit->save();

        return response()->json(['message' => 'Produit ajouté avec succès.', 'produit' => $produit], 201);
    }

    public function show(Produit $produit)
    {
        $produit->load('category');
        return response()->json(['produit' => $produit]);
    }

    public function update(ProduitRequest $request, Produit $produit)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete('photos/' . $produit->photo);
            $path = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = basename($path);
        }

        $produit->category()->associate($request->idCategory);
        $produit->update($validatedData);

        return response()->json(['message' => 'Produit mis à jour avec succès.', 'produit' => $produit]);
    }

    public function destroy(Produit $produit)
    {
        Storage::delete('public/photos/' . $produit->photo);
        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès.']);
    }



    public function indexCategories()
    {
        $categories = Categorie::all(); // Récupère toutes les catégories depuis la base de données

        return response()->json($categories, 200);
    }

    public function related()
    {
        // Exemple: récupérer 4 produits aléatoires
        $relatedProducts = Produit::inRandomOrder()->take(4)->get();

        return response()->json($relatedProducts,200);
    }


    public function getRandomProducts1()
    {
        // Récupère 4 produits aléatoires
        $products = Produit::inRandomOrder()->limit(4)->get();
        return response()->json($products);
    }
    public function getRandomProducts2()
    {
        // Récupère 4 produits aléatoires
        $products = Produit::inRandomOrder()->limit(4)->get();
        return response()->json($products);
    }
}
