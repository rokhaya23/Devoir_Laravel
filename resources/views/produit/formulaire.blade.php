@extends('base ')
@extends('template.sidebar ')

@section('title', 'Product Form')

@section('content')

    <div class="container mt-lg-5">
        <div class="card">
            <div class="card-header bg-success-subtle">
                {{ $produit->exists ? "Edit Product" : "Add Product Form" }}
            </div>
            <div class="card-body">
                <form method="post" action="{{ route($produit->exists ? 'produits.update' : 'produits.store', $produit) }}" enctype="multipart/form-data">
                    @csrf
                    @method($produit->exists ? 'put' : 'post')

                    <label for="nom">Name</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ $produit->nom }}" required>

                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $produit->description }}</textarea>

                    <label for="prix">Price</label>
                    <input type="text" class="form-control" id="prix" name="prix" value="{{ $produit->prix }}" required>

                    <label for="quantite_stock">Quantity in Stock</label>
                    <input type="text" class="form-control" id="quantite_stock" name="quantite_stock" value="{{ $produit->quantite_stock }}" required>

                    <label for="prix">Category</label>
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
                        <button type="submit" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $produit->exists ? "Edit" : "Add" }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
