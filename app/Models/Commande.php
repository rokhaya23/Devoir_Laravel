<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ['idClient','idProduct','total_amount','date_commande'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'pivot_commandes', 'idCommande', 'idProduct')
            ->withPivot('quantity', 'subtotal');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idClient');
    }
}
