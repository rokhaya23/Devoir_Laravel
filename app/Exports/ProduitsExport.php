<?php

namespace App\Exports;

use App\Models\Produit;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;


class ProduitsExport implements FromCollection  ,WithMapping,WithHeadings,ShouldAutoSize
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Produit::all();
    }

    public function headings(): array
    {
        // Definir les colonnes de l'en tete
        return [
            'ID',
            'Nom',
            'Description',
            'Prix',
            'Stock',
            'IdCategory',
            'Photo',

        ];
    }

    public function map( $produit ): array
    {
        $produit->load('category');
        return [
            $produit->id,
            $produit->nom,
            $produit->description,
            $produit->prix,
            $produit->quantite_stock,
            $produit->category->libelle,
            $produit->photo,
        ];
    }

}
