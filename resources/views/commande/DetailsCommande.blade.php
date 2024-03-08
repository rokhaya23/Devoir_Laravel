@extends('base')
@extends('template.sidebar')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h2 class="mb-0">Order Details #{{ $commande->id }}</h2>
            </div>
            <div class="card-body">
                <!-- Customer Information -->
                <div class="mb-4">
                    <h3 class="text-info">Customer Information</h3>
                    <p class="mb-1"><strong>Customer Name:</strong> {{ $commande->client->nom }}</p>
                    <p class="mb-1"><strong>Customer Address:</strong> {{ $commande->client->adresse }}</p>
                    <p class="mb-1"><strong>Customer Phone:</strong> {{ $commande->client->telephone }}</p>
                </div>

                <!-- List of Ordered Products -->
                <div>
                    <h3 class="text-info">Ordered Products</h3>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
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
