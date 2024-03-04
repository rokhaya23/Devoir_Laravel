<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ['idClient','date_commande','status'];


    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'pivot_commandes', 'idCommande', 'idProduct')
            ->withPivot('quantity', 'total');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idClient');
    }
}
