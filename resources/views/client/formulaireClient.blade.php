@extends('base')
@extends('template.sidebar ')

@section('title', 'Client Form')

@section('content')

    <div class="container mt-lg-5">
        <div class="card">
            <div class="card-header bg-success-subtle">
                {{ $client->exists ? "Edit Client" : "Add Client Form" }}
            </div>
            <div class="card-body">
                <form method="post" action="{{ route($client->exists ? 'clients.update' : 'clients.store', $client) }}" enctype="multipart/form-data">
                    @csrf
                    @method($client->exists ? 'put' : 'post')

                    <div class="mb-3 row">
                        <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ $client->nom }}" required>
                            @if ($errors->has('nom'))
                                <span class="text-danger">{{ $errors->first('nom') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="adresse" class="col-md-4 col-form-label text-md-end text-start">Address</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ $client->adresse }}" required>
                            @if ($errors->has('adresse'))
                                <span class="text-danger">{{ $errors->first('adresse') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sexe" class="col-md-4 col-form-label text-md-end text-start">Gender</label>
                        <div class="col-md-6">
                            <select class="form-select @error('sexe') is-invalid @enderror" aria-label="Gender" id="sexe" name="sexe" required>
                                <option value="M" {{ $client->sexe == 'M' ? 'selected' : '' }}>Male</option>
                                <option value="F" {{ $client->sexe == 'F' ? 'selected' : '' }}>Female</option>
                            </select>
                            @if ($errors->has('sexe'))
                                <span class="text-danger">{{ $errors->first('sexe') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="telephone" class="col-md-4 col-form-label text-md-end text-start">Phone</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ $client->telephone }}" required>
                            @if ($errors->has('telephone'))
                                <span class="text-danger">{{ $errors->first('telephone') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Rest of the form... -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-secondary">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $client->exists ? "Edit" : "Add" }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
