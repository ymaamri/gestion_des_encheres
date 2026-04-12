{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>@yield('title', 'Marketplace d\'Enchères') - Gestion des Enchères</title>

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />

    @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="{{ route('dashboard') }}">
                <span class="ms-1 text-sm text-dark font-weight-bolder">Marketplace d'Enchères</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                @auth
                    @role('admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded opacity-5">dashboard</i>
                            <span class="nav-link-text ms-1">Tableau de Bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="material-symbols-rounded opacity-5">people</i>
                            <span class="nav-link-text ms-1">Utilisateurs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('admin.categories.index') }}">
                            <i class="material-symbols-rounded opacity-5">category</i>
                            <span class="nav-link-text ms-1">Catégories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.auctions*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('admin.auctions.index') }}">
                            <i class="material-symbols-rounded opacity-5">gavel</i>
                            <span class="nav-link-text ms-1">Enchères</span>
                        </a>
                    </li>
                    @endrole

                    @role('vendeur')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded opacity-5">dashboard</i>
                            <span class="nav-link-text ms-1">Tableau de Bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('annonces*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('annonces.index') }}">
                            <i class="material-symbols-rounded opacity-5">inventory_2</i>
                            <span class="nav-link-text ms-1">Mes Annonces</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('annonces.create') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('annonces.create') }}">
                            <i class="material-symbols-rounded opacity-5">add_circle</i>
                            <span class="nav-link-text ms-1">Créer une Annonce</span>
                        </a>
                    </li>
                    @endrole

                    @role('client')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded opacity-5">dashboard</i>
                            <span class="nav-link-text ms-1">Tableau de Bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('auctions.active') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('auctions.active') }}">
                            <i class="material-symbols-rounded opacity-5">gavel</i>
                            <span class="nav-link-text ms-1">Enchères Actives</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('my.bids') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('my.bids') }}">
                            <i class="material-symbols-rounded opacity-5">history</i>
                            <span class="nav-link-text ms-1">Mes Offres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('my.won') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('my.won') }}">
                            <i class="material-symbols-rounded opacity-5">emoji_events</i>
                            <span class="nav-link-text ms-1">Enchères Gagnées</span>
                        </a>
                    </li>
                    @endrole

                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Compte</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('profile.edit') }}">
                            <i class="material-symbols-rounded opacity-5">person</i>
                            <span class="nav-link-text ms-1">Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="nav-link text-dark" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="material-symbols-rounded opacity-5">logout</i>
                                <span class="nav-link-text ms-1">Déconnexion</span>
                            </a>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                            @yield('breadcrumb', 'Tableau de Bord')</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">@yield('page-title', 'Tableau de Bord')</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Rechercher...</label>
                            <input type="text" class="form-control" id="global-search">
                        </div>
                    </div>
                    <ul class="navbar-nav d-flex align-items-center justify-content-end">
                        <li class="nav-item dropdown pe-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-rounded">notifications</i>
                                @php
                                    $unreadCount = Auth::check() && Auth::user()->client ? Auth::user()->client->notifications()->where('lue', false)->count() : 0;
                                @endphp
                                @if($unreadCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                                @forelse(Auth::check() && Auth::user()->client ? Auth::user()->client->notifications()->latest()->take(5)->get() : [] as $notification)
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md"
                                            href="{{ route('notifications.mark', $notification) }}">
                                            <div class="d-flex py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        {{ $notification->message }}
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center py-2">
                                        <span class="text-sm">Aucune notification</span>
                                    </li>
                                @endforelse
                                @if(Auth::check() && Auth::user()->client && Auth::user()->client->notifications()->count() > 0)
                                    <li class="text-center mt-2">
                                        <a href="{{ route('notifications.index') }}" class="text-primary text-sm">Voir
                                            tout</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('profile.edit') }}" class="nav-link text-body font-weight-bold px-0">
                                <i class="material-symbols-rounded">account_circle</i>
                                <span class="ms-1 d-none d-md-block">{{ Auth::user()->nom }}
                                    {{ Auth::user()->prenom }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-2">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>

    @stack('scripts')

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = { damping: '0.5' }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
</body>

</html>