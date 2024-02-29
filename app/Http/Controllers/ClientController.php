<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-clients', ['only' => ['index','create','update','store','edit','destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all(); // Récupérez tous les clients

        return view('client.listeClient', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $client = new Client();
        return view('client.formulaireClient',compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required',
            'adresse' => 'required',
            'sexe' => 'required',
            'telephone' => 'required',
        ]);

        // Création d'un nouveau client
        Client::create($validatedData);

        return redirect()->route('clients.store')->with('success', 'Client ajouté avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('client.formulaireClient', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required',
            'adresse' => 'required',
            'sexe' => 'required',
            'telephone' => 'required',
        ]);

        // Mise à jour du client
        $client->update($validatedData);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Suppression du client
        $client->delete();

        return redirect()->route('clients.destroy')->with('success', 'Client supprimé avec succès.');
    }
}
