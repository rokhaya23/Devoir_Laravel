@extends('base')
@extends('template.sidebar ')

@section('title', 'Clients')

@section('content')

    <div class="container mt-lg-5">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-primary" href="{{ route('clients.create') }}">+ Add Client</a>
            </div>

            <div class="col-6 text-right ml-auto">
                <a href="{{ route('clients.pdf') }}" class="btn btn-dark">Download Clients PDF</a>
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
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Phone</th>
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
                                    <!-- Edit button -->
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>

                                    <!-- Delete button -->
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('clients.index', $client->id) }}')"><i class="bi bi-trash"></i></button>
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
            // Add your delete confirmation logic here
            if (confirm('Do you really want to delete this item?')) {
                // Redirect to the delete URL
                window.location.href = url;
            }
        }
    </script>

@endsection
