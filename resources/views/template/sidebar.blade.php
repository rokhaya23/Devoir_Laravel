<div class="container-fluid">
    <div class="row flex-nowrap d-flex">
        <aside id="sidebar" class="sidebar col-md-auto">
            <ul class="sidebar-nav" id="sidebar-nav">
                @auth
                    @can('voir-dashboard')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endcan

                    @can('voir-produits')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('produits.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Products</span>
                            </a>
                        </li>
                    @endcan

                    @can('valider-commandes')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Manage Orders</span>
                            </a>
                        </li>
                    @endcan

                    @can('create-users')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Create Users</span>
                            </a>
                        </li>
                    @endcan

                        @can('create-roles')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">
                                    <i class="bi bi-grid"></i>
                                    <span>Create Roles</span>
                                </a>
                            </li>
                        @endcan


                    @else
                        <!-- Handle the case when the user is not authenticated -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Accueil</span>
                            </a>
                        </li>
                @endauth
            </ul>
        </aside>
    </div>
</div>


