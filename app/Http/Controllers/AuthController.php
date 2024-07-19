<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role; 
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);

    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $roles = $user->getRoleNames(); // Get the first role name
                $token = $user->createToken('auth-token', ['*'], now()->addMinutes(120))->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'message' => 'Utilisateur connecté avec succès!',
                    'user' => $user,
                    'roles' => $roles,
                    'expires_at' => now()->addMinutes(120)->format('Y-m-d H:i:s')
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Identifiants invalides'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs',
                'password' => 'required|string|min:8',
            ]);

            // Création de l'utilisateur
            $user = Utilisateur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Recherche du rôle par défaut "Client"
            $defaultRole = Role::where('name', 'Client')->first();

            if ($defaultRole) {
                // Assigner le rôle à l'utilisateur
                $user->roles()->attach($defaultRole->id);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Le rôle par défaut "Apprenant" est introuvable.'
                ], 500);
            }

            // Authentification de l'utilisateur
            Auth::login($user);

            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur enregistré avec succès!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        $user = Auth::user();
        $user->tokens()->delete(); // Supprime tous les tokens existants de l'utilisateur


        $token = $user->createToken('auth_token',['*'], now()->addMinutes(120))->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'tokenExpiry' => now()->addMinutes(120)->format('Y-m-d H:i:s'),
            'access_token' => $token,
        ]);
    }
}
