<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'quantite_stock',
        'photo',
        'idCategory',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'idCategory');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'pivot_commandes', 'idCommande', 'idProduct')
            ->withPivot('quantity', 'subtotal');
    }
}
