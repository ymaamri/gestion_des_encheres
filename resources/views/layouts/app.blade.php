{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" type="image/png">    <link rel="icon" href="{{ asset('assets/img/bid.png') }}">
    <title>@yield('title', 'Marketplace d\'Enchères') - BidMaster</title>

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />


    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Navbar Styles from Welcome Page */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            background: white;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: #4a5568 !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #667eea !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .nav-link.active {
            color: #667eea !important;
        }

        .nav-link.active::after {
            width: 80%;
        }

        /* Dropdown Menu */
        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 240px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: none;
            padding: 0.5rem;
        }

        .dropdown-toggle-custom {
            cursor: pointer;
        }

        .dropdown-toggle-custom:hover .dropdown-menu-custom,
        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item-custom {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            white-space: normal;
        }

        .dropdown-item-custom:hover {
            background: #f7fafc;
            transform: translateX(5px);
        }

        .dropdown-item-custom i {
            font-size: 1.2rem;
            color: #667eea;
        }

        #notificationsDropdown {
            width: 320px;
            max-width: 90vw;
            padding: 0;
            overflow: hidden;
            right: 0;
            margin-right: 15px;
        }

        #userMenuDropdown {
            right: 0 !important;
            margin-right: 15px;
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-avatar:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .user-avatar i {
            font-size: 1.5rem;
            color: #4a5568;
        }

        /* Notification Badge */
        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f56565;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-top: 80px;
            padding: 1.5rem;
            min-height: calc(100vh - 80px);
            background: #f8f9fa;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Buttons matching home page */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);  
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-outline-gradient {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;  
            display: inline-block;
            flex-shrink: 0;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        /* Custom Theme Gradient Background */
        .bg-gradient-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="fas fa-gavel text-primary"></i>
                BidMaster
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @auth
                        @role('admin')
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"
                                href="{{ route('admin.categories.index') }}">Catégories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('admin.auctions*') ? 'active' : '' }}"
                                href="{{ route('admin.auctions.index') }}">Enchères</a>
                        </li>
                        @endrole

                        @role('vendeur')
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('seller.products*') ? 'active' : '' }}"
                                href="{{ route('seller.products.index') }}">Mes Produits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('annonces.index') ? 'active' : '' }}"
                                href="{{ route('annonces.index') }}">Mes Annonces</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('seller.bids.index') ? 'active' : '' }}"
                                href="{{ route('seller.bids.index') }}">Offres reçues</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('seller.sales*') ? 'active' : '' }}"
                                href="{{ route('seller.sales.index') }}">Mes Ventes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('annonces.create') ? 'active' : '' }}"
                                href="{{ route('annonces.create') }}">Créer</a>
                        </li>
                        @endrole

                        @role('client')
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('auctions.active') ? 'active' : '' }}"
                                href="{{ route('auctions.active') }}">Enchères Actives</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('my.bids') ? 'active' : '' }}"
                                href="{{ route('my.bids') }}">Mes Offres</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('my.won') ? 'active' : '' }}"
                                href="{{ route('my.won') }}">Gagnées</a>
                        </li>
                        @endrole
                    @else
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ url('/') }}#home">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ url('/') }}#products">Produits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ url('/') }}#categories">Catégories</a>
                        </li>
                    @endauth
                </ul>

                <div class="user-menu mt-3 mt-lg-0">
                    @auth
                        <!-- Notifications -->
                        <div class="nav-item dropdown-toggle-custom" onclick="toggleNotifications()">
                            <div class="notification-badge">
                                <div class="user-avatar">
                                    <i class="material-symbols-rounded">notifications</i>
                                </div>
                                @php
                                    $unreadCount = Auth::check() && Auth::user()->client ? Auth::user()->client->notifications()->where('lue', false)->count() : 0;
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge-count">{{ $unreadCount }}</span>
                                @endif
                            </div>
                            <div class="dropdown-menu-custom" id="notificationsDropdown">
                                <div style="padding: 1rem; border-bottom: 1px solid #e2e8f0; background: #f8f9fa;">
                                    <strong style="color: #4a5568;">Notifications</strong>
                                    @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.mark-all-read') }}"
                                            class="d-inline float-end">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none"
                                                style="color: #667eea;">Tout lu</button>
                                        </form>
                                    @endif
                                </div>
                                <div style="max-height: 320px; overflow-y: auto;">
                                    @forelse(Auth::check() && Auth::user()->client ? Auth::user()->client->notifications()->latest()->take(5)->get() : [] as $notification)
                                        <a class="dropdown-item-custom" href="{{ route('notifications.mark', $notification) }}"
                                            style="border-bottom: 1px solid #f0f0f0; border-radius: 0; transform: none; padding: 1rem;">
                                            <div style="width: 100%;">
                                                <div
                                                    style="font-size: 0.85rem; color: #4a5568; line-height: 1.4; margin-bottom: 0.3rem;">
                                                    {{ $notification->message }}</div>
                                                <small style="font-size: 0.7rem; color: #a0aec0;">
                                                    <i
                                                        class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </a>
                                    @empty
                                        <div style="padding: 2rem 1rem; text-align: center; color: #a0aec0;">
                                            <i class="far fa-bell-slash mb-2" style="font-size: 1.5rem;"></i><br>
                                            Aucune notification
                                        </div>
                                    @endforelse
                                </div>
                                @if(Auth::check() && Auth::user()->client && Auth::user()->client->notifications()->count() > 0)
                                    <div
                                        style="padding: 0.75rem; text-align: center; border-top: 1px solid #e2e8f0; background: #f8f9fa;">
                                        <a href="{{ route('notifications.index') }}"
                                            style="color: #667eea; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Voir
                                            toutes les notifications</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="nav-item dropdown-toggle-custom ms-3" onclick="toggleUserMenu()">
                            <div class="user-avatar">
                                <i class="material-symbols-rounded">account_circle</i>
                            </div>
                            <div class="dropdown-menu-custom" id="userMenuDropdown" style="right: 0; left: auto;">
                                <div style="padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                                    <div><strong>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</strong></div>
                                    <small style="color: #a0aec0;">{{ Auth::user()->email }}</small>
                                </div>
                                <a class="dropdown-item-custom" href="{{ route('profile.edit') }}">
                                    <i class="material-symbols-rounded">person</i> Mon Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item-custom"
                                        style="width: 100%; background: none; border: none; cursor: pointer;">
                                        <i class="material-symbols-rounded">logout</i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="#" class="btn btn-outline-gradient me-2" data-bs-toggle="modal"
                            data-bs-target="#loginModal">Connexion</a>
                        <a href="{{ route('register') }}" class="btn btn-gradient">Inscription</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" style="margin-top: 100px;">

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 16px;">
                <i class="material-symbols-rounded me-2">check_circle</i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 16px;">
                <i class="material-symbols-rounded me-2">error</i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 16px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Login Modal (for guests) -->
    @guest
        @include('components.login-modal')
    @endguest

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>

    <script>
        // Notifications Dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            if (dropdown.style.opacity === '1') {
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
            } else {
                // Close other dropdowns
                document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                });
                dropdown.style.opacity = '1';
                dropdown.style.visibility = 'visible';
            }
        }

        // User Menu Dropdown
        function toggleUserMenu() {
            const dropdown = document.getElementById('userMenuDropdown');
            if (dropdown.style.opacity === '1') {
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
            } else {
                document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                });
                dropdown.style.opacity = '1';
                dropdown.style.visibility = 'visible';
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.dropdown-toggle-custom')) {
                document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                });
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>