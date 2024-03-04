@extends('base')
@extends('template.sidebar')

@section('title', 'Liste des commandes')

@section('content')
    <div class="container mt-lg-5">
        <h2>Liste des commandes</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Numéro de commande</th>
                <th>Nom du client</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($commandes as $commande)
                <tr>
                    <td>{{ $commande->id }}</td>
                    <td>{{ $commande->client->nom }}</td>
                    <td>
                        <a href="{{ route('commande.showList', ['commande' => $commande->id]) }}" class="btn btn-primary">Show</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
