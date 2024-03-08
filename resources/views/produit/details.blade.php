@extends('base')
@extends('template.sidebar ')

@section('title', 'Products')

@section('content')

    <div class="container mt-lg-5">

        <div class="row">
            <div class="col-6">
                <a class="btn btn-primary" href="{{ route('produits.create') }}">+ Add Product</a>
            </div>
            <div class="col-6 text-right ml-auto">
                <a href="{{ route('export.excel') }}" class="btn btn-dark"> Export to Excel</a>
            </div>
        </div>
        <br>
        <br>

        <div class="row">
            @foreach($produits as $produit)
                <div class="col-md-6 mb-3" style="width: 30%;">
                    <div class="card bg-light border-primary d-flex flex-column" style="width: auto;">
                        <div class="card">
                            <a href="{{ route('produits.show', $produit->id) }}">
                                <img id="main-image" style="width: 100%; border-radius: 8px; object-fit: cover;" src="{{ asset('storage/photos/' . $produit->photo) }}" alt="{{ $produit->nom }}" class="card-img-top">
                            </a>
                        </div>
                        <div class="card-body text-center flex-grow-1">
                            <h5 class="card-title">{{ $produit->nom }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Script to display details when clicking on the image -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all images with the id "main-image"
            var images = document.querySelectorAll('#main-image');

            // Add an event listener for each image
            images.forEach(function (image) {
                image.addEventListener('click', function () {
                    // Get the URL of the clicked image
                    var imageUrl = this.src;

                    // Display details or perform an action of your choice (e.g., open a modal with details)
                    console.log('Click on the image to display details:', imageUrl);
                });
            });
        });
    </script>

@endsection
