@extends('base')
@extends('template.sidebar ')

@section('title', 'User Details')

@section('content')

    <div class="container mt-lg-5">
        <div class="card">
            <h5 class="card-header bg-info text-center">User Details</h5>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('storage/photos/' . $user->photo) }}" style="width: 80%; height: 90%; border-radius: 100%; object-fit: cover" alt="Photo" class="img-thumbnail">
                    </div>
                    <div class="col-md-8">
                        <div class="mt-3">
                            <label for="nom"><strong>Last Name:</strong> </label>
                            <span>{{ $user->nom }}</span>
                        </div>
                        <div>
                            <label for="prenom"><strong>First Name:</strong> </label>
                            <span>{{ $user->prenom }}</span>
                        </div>
                        <div>
                            <label for="email"><strong>Email:</strong> </label>
                            <span>{{ $user->email }}</span>
                        </div>
                        <!-- Add other fields as needed -->

                        <div class="mt-3">
                            <label for="roles"><strong>Roles:</strong> </label>
                            <br>
                            @forelse($user->roles as $role)
                                <span>{{ $role->name }}</span>
                            @empty
                                <span>No roles available</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Close</a>
                </div>
            </div>
        </div>
    </div>

@endsection
