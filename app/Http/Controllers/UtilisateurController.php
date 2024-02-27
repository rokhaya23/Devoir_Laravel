<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtilisateurRequest;
use App\Models\Produit;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;


class UtilisateurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-users', ['only' => ['index','show','create','update','store','edit','destroy']]);

    }

    public function index()
    {

        $users = Utilisateur::all(); // Récupérez tous les produits disponibles

        return view('utilisateur.utilisateur', compact('users'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('Admin')) {
        $user = new Utilisateur();

        $roles = Role::all(); // Récupérez tous les rôles
        return view('utilisateur.formulaire_users', compact('user','roles'));
    } else {
        abort(403);
    }
    }

    public function store(UtilisateurRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        // Traitement pour stocker la photo
        $photoPath = $request->file('photo')->store('public/photos');
        $input['photo'] = basename($photoPath);

        $user = Utilisateur::create($input);
        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');


    }

    public function edit(Utilisateur $user)
    {
        $roles = Role::all();
        return view('utilisateur.formulaire_users', compact('user', 'roles'));
    }

    public function update(UtilisateurRequest $request, Utilisateur $user)
    {
        $input = $request->all();

        if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = basename($path);
        }

        $user->update($input);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');

    }

    public function destroy(Utilisateur $user)
    {
        if ($user->hasRole('Admin') || $user->id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        $user->syncRoles([]);

        $photoPath = 'public/photos/' . $user->photo;
        Storage::delete([$photoPath]);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }



}
