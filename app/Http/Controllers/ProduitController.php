<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitRequest;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:gerer-produits', ['only' => ['index','show','creste','update','store','edit','destroy']]);

    }

    public function index()
    {

        $produits = Produit::all();
        return view('commande.details', compact('produits'));
    }

    public function create()
    {
        $produit = new Produit();// Créez une instance vide de Produit
        $categories = Categorie::all();
        return view('commande.formulaire',compact('produit','categories'));
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

        // Utilisation de la relation pour attribuer la catégorie
        $produit->category()->associate($request->idCategory);

        $produit->save();

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {
        // Récupérez d'autres produits comme suggestions (exemple: les 4 derniers produits)
        $suggestedProducts = Produit::where('id', '!=', $produit->id)->latest()->take(4)->get();

        return view('commande.show', compact('produit','suggestedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        $categories = Categorie::all();
        return view('commande.formulaire', compact('produit','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProduitRequest $request, Produit $produit)
    {
        // Récupérez les données validées à partir de la requête
        $validatedData = $request->validated();

        // Si une nouvelle photo est téléchargée, traitez-la
        if ($request->hasFile('photo')) {
            // Supprimez l'ancienne photo
            Storage::disk('public')->delete('photos/' . $produit->photo);

            // Stockez la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = basename($path);
        }

        // Utilisation de la relation pour mettre à jour la catégorie
        $produit->category()->associate($request->idCategory);

        // Mettez à jour les autres champs avec les données validées
        $produit->update($validatedData);


        // Redirigez vers la page des étudiants avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        // Avant la suppression - Chemin de la photo
        $photoPath = 'public/photos/' . $produit->photo;

        // Supprimez la photo du dossier
        Storage::delete([$photoPath]);

        // Supprimez l'instance
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    private function extractData(ProduitRequest $request): array
    {
        // Utilisez les règles de validation de la demande
        $data = $request->validated();

        // Vérifiez si une nouvelle photo a été téléchargée
        if ($request->hasFile('photo')) {
            // Supprimez l'ancienne photo avant de stocker la nouvelle
            Storage::disk('public')->delete($data['photo']);

            // Stockez la nouvelle photo
            $photoPath = $request->file('photo')->store('public/photos');
            $data['photo'] = basename($photoPath);
        }

        return $data;
    }
}
