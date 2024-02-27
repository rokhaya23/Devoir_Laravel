<!-- home.blade.php -->

@extends('base')

@section('title', 'Accueil')

@section('content')

    <div class="container mt-lg-5">

        <h1>Bienvenue sur notre site</h1>

        <div class="row">
            @foreach($produits as $produit)
                <div class="col-md-4 mb-3">
                    <div class="card bg-light border-primary">
                        <div class="card">
                            <img id="main-image" style="width: 100%; border-radius: 8px; object-fit: cover;" src="{{ asset('storage/photos/' . $produit->photo) }}" alt="{{ $produit->nom }}" class="card-img-top">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $produit->nom }}</h5>
                            <p class="card-text">{{ $produit->description }}</p>
                            <p class="card-text"><strong>Prix:</strong> {{ number_format($produit->prix, 2, ',', ' ') }} F CFA</p>
                            <a href="{{ route('produits.show', $produit->id) }}" class="btn btn-info">Détails</a>

                            @if(auth()->check() && auth()->user()->role === 'manager')
                                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning mx-1">Modifier</a>
                            @else
                                <form action="{{ route('cart.add', $produit->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Ajouter au panier</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
