@extends('base')
@extends('template.sidebar')

@section('title', 'Détails Commande')
@section('content')
    <div class="container mt-5">
        <h2>Mon Panier</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Nom du Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['nom'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['prix'] }} F CFA</td>
                    <td>{{ $item['total_price'] }} F CFA</td>
                    <td>
                        <a href="{{ route('editCartItem', $item['product_id']) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="{{ route('deleteCartItem', $item['product_id']) }}" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <h4>Total du Panier : {{ $totalCartPrice }} F CFA</h4>
            <a href="{{ route('checkout') }}" class="btn btn-primary">Valider la Commande</a>
        </div>
    </div>
@endsection
