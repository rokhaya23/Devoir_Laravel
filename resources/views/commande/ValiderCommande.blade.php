<!-- resources/views/commande/details.blade.php -->

@extends('base')
@extends('template.sidebar')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-lg-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Order Details #{{ $commande->id }}</h2>
            </div>

            <div class="card-body">
                <!-- Customer Information -->
                <h3 class="mb-3">Customer Information</h3>
                <p><strong>Customer Name:</strong> {{ $commande->client->nom }}</p>
                <p><strong>Customer Address:</strong> {{ $commande->client->adresse }}</p>
                <p><strong>Customer Phone:</strong> {{ $commande->client->telephone }}</p>

                <!-- Order Status -->
                <h3 class="mb-3">Order Status</h3>
                <p><strong>Current Status:</strong> {{ $commande->status }}</p>

                <!-- Update Order Status -->
                <h3 class="mb-3">Update Order Status</h3>
                <form action="{{ route('updateStatus', ['commande' => $commande->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select id="newStatus" name="newStatus" class="form-select" required>
                            <option value="En Attente" @if($commande->status === 'En Attente') selected @endif>Pending</option>
                            <option value="Acceptée" @if($commande->status === 'Acceptée') selected @endif>Accepted</option>
                            <option value="Livrée" @if($commande->status === 'Livrée') selected @endif>Delivered</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
