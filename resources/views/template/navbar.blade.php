@include('template.sidebar')
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <span class="d-none d-lg-block"></span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- ... Autres parties de votre en-tête ... -->
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar  -->

    <li class="nav-item dropdown ms-auto pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @if(Auth::check())
                <div class="dropdown-toggle d-flex align-items-center user-settings">
                    <img src="{{ asset('storage/photos/' . Auth::user()->photo) }}" class="img-3x m-2 me-0 rounded-5" alt="">
                    <span class="d-none d-md-block ps-2">{{ Auth::user()->nom }}</span>
                </div>
            @else
                <span class="nav-link"><a href="{{ route('users.login') }}">Login</a></span>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
                @if(Auth::check())
                    <h6>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h6>
                    <span>{{ Auth::user()->email }}</span>
                @endif
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </button>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </ul>
    </li>


</header>
