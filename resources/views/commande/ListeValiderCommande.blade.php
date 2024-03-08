@extends('base')
@extends('template.sidebar')

@section('title', 'List of Orders')

@section('content')
    <div class="container mt-lg-5">
        <h2>List of Orders</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($commandes as $commande)
                <tr>
                    <td>{{ $commande->id }}</td>
                    <td>{{ $commande->client->nom }}</td>
                    <td>
                        <a href="{{ route('status', ['commande' => $commande->id]) }}" class="btn btn-primary">Show</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
