@extends('base')
@extends('template.sidebar')

@section('title', 'Détails Produit')

@section('content')
    <div class="container mt-lg-5">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col-auto d-none d-lg-block">
                <img src="{{ asset('storage/photos/' . $produit->photo) }}" alt="{{ $produit->nom }}">
            </div>
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-success">{{ $produit->category->libelle }}</strong>
                <h5 class="mb-0">{{ $produit->nom }}</h5>
                <hr>
                <p class="mb-auto text-muted">{{ $produit->description }}</p>
                <strong class="mb-auto font-weight-normal text-secondary">{{ number_format($produit->prix, 2, ',', ' ') }} F CFA</strong>
                <form action="{{ route('panier.store', $produit->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $produit->id }}">
                    <input type="hidden" name="nom" value="{{ $produit->nom }}">
                    <input type="hidden" name="prix" value="{{ $produit->prix }}">
                        <label for="quantity">Quantité :</label>
                        <select name="quantity" id="quantity">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>

                        <button type="submit" class="btn btn-dark">Ajouter au panier</button>
                    </form>


                <div class="mt-3">
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">Fermer</a>
                    @can(['Admin', 'Product Manager'], $produit)
                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning mx-1">Modifier</a>
                        <form id="delete-form-{{ $produit->id }}" action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">Supprimer</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Section pour afficher d'autres produits suggérés -->
    <div class="mt-5">
        <h4>Vous pourriez aussi aimer</h4>
        <div class="row">
            @foreach($suggestedProducts as $suggestedProduct)
                <div class="col-md-3">
                    <div class="card mb-4">
                        <img style="width: 100%; border-radius: 8px; object-fit: cover;" src="{{ asset('storage/photos/' . $suggestedProduct->photo) }}" alt="{{ $suggestedProduct->nom }}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">{{ $suggestedProduct->nom }}</h5>
                            <p class="card-text">{{ $suggestedProduct->description }}</p>
                            <form action="{{ route('panier.store', $suggestedProduct->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $suggestedProduct->id }}">
                                <input type="hidden" name="nom" value="{{ $suggestedProduct->nom }}">
                                <input type="hidden" name="prix" value="{{ $suggestedProduct->prix }}">
                                <button type="submit" id="addToCartBtn" class="btn btn-dark">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addToCartBtn').on('click', function() {
                var formData = $('#addToCartForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('addToCart') }}',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Ajouté au panier!',
                            text: 'Que souhaitez-vous faire?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aller au panier',
                            cancelButtonText: 'Continuer mes achats'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('cart') }}';
                            } else {
                                // Redirection ou autre logique pour continuer les achats
                            }
                        });
                    },
                    error: function(error) {
                        console.error('Erreur lors de l\'ajout au panier:', error);
                    }
                });
            });
        });
    </script>
@endsection
