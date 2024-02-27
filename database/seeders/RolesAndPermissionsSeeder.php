<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 //creer des permissions
        $permissions = [
            'dashboard',
            'gerer-produits',
            'valider-commandes',
            'create-users',
            'create-roles',
            'accueil',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
//creer des roles

        $admin = Role::create(['name' => 'Admin']);
        $client = Role::create(['name' => 'Client']);
        $productManager = Role::create(['name' => 'Product Manager']);

        $productManager->givePermissionTo([
            'gerer-produits',
            'valider-commandes',
        ]);

        $client->givePermissionTo([
            'accueil'
        ]);

        $admin->givePermissionTo([
            'dashboard',
            'gerer-produits',
            'valider-commandes',
            'create-users',
            'create-roles',
        ]);

//creer des utilisateurs
        $Admin = Utilisateur::create([
            'nom' => 'Beye',
            'prenom' => 'Rokhaya',
            'email' => 'rbeye23@gmail.com',
            'password' => Hash::make('passer@1'),
        ]);
        $Admin->assignRole('Admin');

        // Creating Admin User
        $Manager = Utilisateur::create([
            'nom' => 'Fall',
            'prenom' => 'Fallou',
            'email' => 'falloufall@gmail.com',
            'password' => Hash::make('passer@2'),
        ]);
        $Manager->assignRole('Product Manager');

        // Creating Product Manager User
        $Client = Utilisateur::create([
            'nom' => 'Ndiaye',
            'prenom' => 'Fallou',
            'email' => 'falloundiaye@gmail.com',
            'password' => Hash::make('passer@3')
        ]);
        $Client->assignRole('Client');


        Categorie::create([
            'libelle'=>'Alimentaire'
        ]);

        Categorie::create([
            'libelle'=>'Cosmetique'
        ]);

        Categorie::create([
            'libelle'=>'Electromenagers'
        ]);

        Categorie::create([
            'libelle'=>'Equipement et Divers'
        ]);
    }


}
