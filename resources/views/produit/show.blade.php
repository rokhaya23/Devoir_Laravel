@extends('base')
@extends('template.sidebar ')

@section('title', 'Détails Produit')

@section('content')
    <div class="container mt-lg-5">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col-auto d-none d-lg-block">
                <img src="{{ asset('storage/photos/' . $produit->photo) }}" alt="{{ $produit->nom }}">
            </div>
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-success">{{ $produit->category->libelle }}</strong>
                <h5 class="mb-0">{{ $produit->nom }}</h5>
                <hr>
                <p class="mb-auto text-muted">{{ $produit->description }}</p>
                <strong class="mb-auto font-weight-normal text-secondary">{{ number_format($produit->prix, 2, ',', ' ') }} F CFA</strong>
                <strong class="mb-auto font-weight-normal text-secondary">Quantité en stock : {{ $produit->quantite_stock }}</strong>
                <form action="{{ route('produits.show', $produit->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $produit->id }}">
                    <input type="hidden" name="nom" value="{{ $produit->nom }}">
                    <input type="hidden" name="prix" value="{{ $produit->prix }}">
                    <input type="hidden" name="quantite_stock" value="{{ $produit->quantite_stock }}">

                </form>


                <div class="mt-3">
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">Fermer</a>
                    @can(['Admin', 'Product Manager'], $produit)
                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning mx-1">Modifier</a>
                        <form id="delete-form-{{ $produit->id }}" action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">Supprimer</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>


@endsection
