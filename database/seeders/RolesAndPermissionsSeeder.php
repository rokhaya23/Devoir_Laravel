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
            'manage-products',
            'validate-orders',
            'create-users',
            'create-roles',
            'create-clients',
            'manage-orders',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
//creer des roles

        $admin = Role::create(['name' => 'Admin']);
        $productManager = Role::create(['name' => 'Product Manager']);

        $productManager->givePermissionTo([
            'manage-products',
            'manage-orders',
            'create-clients',
        ]);


        $admin->givePermissionTo([
            'dashboard',
            'manage-products',
            'manage-orders',
            'create-users',
            'create-roles',
            'create-clients',
            'validate-orders',
        ]);

//creer des utilisateurs
        $Admin = Utilisateur::create([
            'nom' => 'Beye',
            'prenom' => 'Rokhaya',
            'email' => 'rbeye23@gmail.com',
            'password' => Hash::make('passer@1'),
        ]);
        $Admin->assignRole('Admin');


        $Manager = Utilisateur::create([
            'nom' => 'Fall',
            'prenom' => 'Fallou',
            'email' => 'falloufall@gmail.com',
            'password' => Hash::make('passer@2'),
        ]);
        $Manager->assignRole('Product Manager');






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
