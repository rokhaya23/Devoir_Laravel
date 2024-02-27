@extends('base')
@extends('template.sidebar ')

@section('title', 'Utilisateurs')

@section('content')

    <div class="container mt-lg-5">

        <a class="btn btn-primary" href="{{ route('users.create') }}">Ajouter un utilisateur</a>
        <br>
        <br>

        <div class="card">
            <div class="card-header bg-success-subtle">LISTE DES UTILISATEURS</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prenom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->nom }}</td>
                            <td>{{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->getRoleNames()->toArray() ? implode(', ', $user->getRoleNames()->toArray()) : 'Aucun rôle' }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    @if (in_array('Admin', $user->getRoleNames()->toArray() ?? []) )
                                        @if (Auth::user()->hasRole('Admin'))
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                        @endif
                                    @else
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>

                                        @if (Auth::user()->id!=$user->id)
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this user?');"><i class="bi bi-trash"></i> Delete</button>
                                        @endif
                                    @endif

                                </form>
                            </td>
                        </tr>
                        @empty
                            <td colspan="5">
                        <span class="text-danger">
                            <strong>No User Found!</strong>
                        </span>
                            </td>
                        @endforelse

                    </tbody>
                </table>


            </div>
        </div>
    </div>

@endsection
