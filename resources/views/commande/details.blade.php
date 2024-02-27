@extends('base')
@extends('template.sidebar ')

@section('title', 'Produits')

@section('content')

    <div class="container mt-lg-5">

        <div class="row">
            <div class="col-6">
                <a class="btn btn-primary" href="{{ route('produits.create') }}">+ Add Product</a>
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

    <!-- Script pour afficher les détails lors du clic sur l'image -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sélectionnez toutes les images avec l'id "main-image"
            var images = document.querySelectorAll('#main-image');

            // Ajoutez un gestionnaire d'événements pour chaque image
            images.forEach(function (image) {
                image.addEventListener('click', function () {
                    // Récupérez l'URL de l'image cliquée
                    var imageUrl = this.src;

                    // Affichez les détails ou effectuez une action de votre choix (par exemple, ouvrez une modale avec les détails)
                    console.log('Cliquez sur l\'image pour afficher les détails:', imageUrl);
                });
            });
        });
    </script>

@endsection
