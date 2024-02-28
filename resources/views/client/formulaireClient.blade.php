@extends('base')
@extends('template.sidebar ')

@section('title', 'Formulaire Client')

@section('content')

    <div class="container mt-lg-5">
        <div class="card">
            <div class="card-header bg-success-subtle">
                {{ $client->exists ? "Modifier client" : "Formulaire Ajout client" }}
            </div>
            <div class="card-body">
                <form method="post" action="{{ route($client->exists ? 'clients.update' : 'clients.store', $client) }}" enctype="multipart/form-data">
                    @csrf
                    @method($client->exists ? 'put' : 'post')

                    <div class="mb-3 row">
                        <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ $client->nom }}" required>
                            @if ($errors->has('nom'))
                                <span class="text-danger">{{ $errors->first('nom') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="adresse" class="col-md-4 col-form-label text-md-end text-start">Adresse</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ $client->adresse }}" required>
                            @if ($errors->has('adresse'))
                                <span class="text-danger">{{ $errors->first('adresse') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sexe" class="col-md-4 col-form-label text-md-end text-start">Sexe</label>
                        <div class="col-md-6">
                            <select class="form-select @error('sexe') is-invalid @enderror" aria-label="Sexe" id="sexe" name="sexe" required>
                                <option value="M" {{ $client->sexe == 'M' ? 'selected' : '' }}>M</option>
                                <option value="F" {{ $client->sexe == 'F' ? 'selected' : '' }}>F</option>
                            </select>
                            @if ($errors->has('sexe'))
                                <span class="text-danger">{{ $errors->first('sexe') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="telephone" class="col-md-4 col-form-label text-md-end text-start">Téléphone</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ $client->telephone }}" required>
                            @if ($errors->has('telephone'))
                                <span class="text-danger">{{ $errors->first('telephone') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Reste du formulaire... -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-secondary">Fermer</button>
                        <button type="submit" class="btn btn-primary">{{ $client->exists ? "Modifier" : "Ajouter" }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
