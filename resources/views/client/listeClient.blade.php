@extends('base')
@extends('template.sidebar ')

@section('title', 'Clients')

@section('content')

    <div class="container mt-lg-5">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-primary" href="{{ route('clients.create') }}">+ Add client</a>
            </div>

            <div class="col-6 text-right ml-auto">
                <a href="{{ route('clients.pdf') }}" class="btn btn-dark">Télécharger PDF des clients</a>
            </div>
        </div>
        <br>
        <br>

        <div class="card">
            <div class="card-header bg-success-subtle">CUSTOMER LISTS</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">Sexe</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->adresse }}</td>
                            <td>{{ $client->sexe }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>
                                <div class="d-inline-flex gap-3">
                                    <!-- Bouton de modification -->
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>

                                    <!-- Bouton de suppression -->
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('clients.destroy', $client->id) }}')"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <td colspan="7">
                            <span class="text-danger">
                                <strong>No Client Found!</strong>
                            </span>
                        </td>
                    @endforelse

                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <script>
        function confirmDelete(url) {
            // Ajoutez votre logique de confirmation de suppression ici
            if (confirm('Voulez-vous vraiment supprimer cet élément?')) {
                // Rediriger vers l'URL de suppression
                window.location.href = url;
            }
        }
    </script>

@endsection
