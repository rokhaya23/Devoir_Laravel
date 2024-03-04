<!-- resources/views/commande/details.blade.php -->

@extends('base')
@extends('template.sidebar')

@section('title', 'Détails de la commande')

@section('content')
    <div class="container mt-lg-5">
        <h2>Détails de la commande #{{ $commande->id }}</h2>

        <!-- Informations sur le client -->
        <h3>Informations sur le client</h3>
        <p>Nom du client: {{ $commande->client->nom }}</p>
        <p>Adresse du client: {{ $commande->client->adresse }}</p>
        <p>Téléphone du client: {{ $commande->client->telephone }}</p>

        <!-- Liste des produits commandés -->
        <h3>Produits commandés</h3>
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
                    <td>{{ $produit->prix }}</td>
                    <td>{{ $produit->pivot->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Statut de la commande -->
        <h3>Statut de la commande</h3>
        <p>Statut actuel: {{ $commande->status }}</p>

        <!-- Modifier le statut de la commande -->
        <h3>Modifier le statut</h3>
        <form action="{{ route('commande.updateStatus', ['commande' => $commande->id]) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="newStatus" class="form-label">Nouveau statut</label>
                <select id="newStatus" name="newStatus" class="form-select" required>
                    <option value="En Attente" @if($commande->status === 'En Attente') selected @endif>En Attente</option>
                    <option value="Acceptée" @if($commande->status === 'Acceptée') selected @endif>Acceptée</option>
                    <option value="Livrée" @if($commande->status === 'Livrée') selected @endif>Livrée</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour le statut</button>
        </form>

        <!-- Retour à la liste des commandes -->
        <a href="{{ route('commande.index') }}" class="btn btn-primary">Retour à la liste des commandes</a>
    </div>
@endsection
