@extends('base')
@extends('template.sidebar')

@section('title', 'Liste des commandes')

@section('content')
    <div class="container mt-lg-5">
        <div class="card mb-2">
            <div class="card-body">
                <Legend class="bg-success">Liste des commandes</Legend>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Nom du Client</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($commandes as $commande)
                        <tr>
                            <td>{{ $commande->id }}</td>
                            <td>{{ $commande->client->nom }}</td>
                            <td class="badge bg-info">{{ $commande->status }}</td>
                            <td>
                                <a href="{{ route('commande.show', ['commande' => $commande->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if ($commande->status === 'En Attente')
                                    <a href="{{ route('commande.edit', ['commande' => $commande->id]) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('commande.destroy', ['commande' => $commande->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
