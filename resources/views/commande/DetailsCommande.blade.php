@extends('base')
@extends('template.sidebar')

@section('title', 'Détails de la commande')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h2 class="mb-0">Détails de la commande #{{ $commande->id }}</h2>
            </div>
            <div class="card-body">
                <!-- Informations sur le client -->
                <div class="mb-4">
                    <h3 class="text-info">Informations sur le client</h3>
                    <p class="mb-1"><strong>Nom du client:</strong> {{ $commande->client->nom }}</p>
                    <p class="mb-1"><strong>Adresse du client:</strong> {{ $commande->client->adresse }}</p>
                    <p class="mb-1"><strong>Téléphone du client:</strong> {{ $commande->client->telephone }}</p>
                </div>

                <!-- Liste des produits commandés -->
                <div>
                    <h3 class="text-info">Produits commandés</h3>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($commande->produits as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td>{{ $produit->pivot->quantity }}</td>
                                <td>{{ $produit->prix }} FCFA</td>
                                <td>{{ $produit->pivot->total }} FCFA</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
