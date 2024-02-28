@extends('base ')
@extends('template.sidebar ')

@section('title', 'Formulaire Produit')

@section('content')

    <div class="container mt-lg-5">
        <div class="card">
            <div class="card-header bg-success-subtle">
                {{ $produit->exists ? "Modifier Produit" : "Formulaire Ajout Produit" }}
            </div>
            <div class="card-body">
                <form method="post" action="{{ route($produit->exists ? 'produits.update' : 'produits.store', $produit) }}" enctype="multipart/form-data">
                    @csrf
                    @method($produit->exists ? 'put' : 'post')

                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ $produit->nom }}" required>

                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $produit->description }}</textarea>

                    <label for="prix">Prix</label>
                    <input type="text" class="form-control" id="prix" name="prix" value="{{ $produit->prix }}" required>

                    <label for="quantite_stock">Quantité en Stock</label>
                    <input type="text" class="form-control" id="quantite_stock" name="quantite_stock" value="{{ $produit->quantite_stock }}" required>

                    <label for="prix">Libelle</label>
                    <select name="idCategory" id="idCategory" class="form-control">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $produit->idCategory == $category->id ? 'selected' : '' }}>
                                {{ $category->libelle }}
                            </option>
                        @endforeach
                    </select>
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-secondary">Fermer</button>
                        <button type="submit" class="btn btn-primary">{{ $produit->exists ? "Modifier" : "Ajouter" }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
