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

                    @can('manage-products')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('produits.index') }}">
                                <i class="bi bi-grid"></i>
                                <span>Products</span>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#submenu-orders">
                            <i class="bi bi-grid"></i>
                            <span>Orders</span>
                        </a>
                        <ul class="submenu-list collapse" id="submenu-orders">
                            @can('manage-orders')
                                <li><a href="{{ route('commande.index') }}">Manage Orders</a></li>
                            @endcan

                            @can('validate-orders')
                                <li><a href="{{ route('users.index') }}">Validate Orders</a></li>
                            @endcan
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#submenu-page">
                            <i class="bi bi-grid"></i>
                            <span>Page</span>
                        </a>
                        <ul class="submenu-list collapse" id="submenu-page">
                            @can('create-users')
                                <li><a href="{{ route('users.index') }}">Create Users</a></li>
                            @endcan

                            @can('create-roles')
                                <li><a href="{{ route('roles.index') }}">Create Roles</a></li>
                            @endcan

                            @can('create-clients')
                                <li><a href="{{ route('clients.index') }}">Create Clients</a></li>
                            @endcan
                        </ul>
                    </li>
                @endauth
            </ul>
        </aside>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $('.nav-link[data-bs-toggle="collapse"]').click(function() {
            var target = $(this).attr("href");
            $(target).toggleClass('show');
        });
    });
</script>
