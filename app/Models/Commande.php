<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Utilisateur;
use App\Models\Produit;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = ['idUser','date_commande','status'];


    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'pivot_commandes', 'idCommande', 'idProduct')
            ->withPivot('quantity', 'totale');
    }

    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'idUser');
    }
}
