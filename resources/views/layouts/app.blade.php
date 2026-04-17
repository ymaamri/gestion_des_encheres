{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>@yield('title', 'Marketplace d\'Enchères') - BidMaster</title>

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Top Navbar Styles */
        .top-navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .nav-item-custom {
            position: relative;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50px !important;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link-custom.active {
            background: rgba(255, 255, 255, 0.25);
            color: white !important;
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

        .nav-item-custom:hover .dropdown-menu-custom {
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
        }

        .dropdown-item-custom:hover {
            background: #f7fafc;
            transform: translateX(5px);
        }

        .dropdown-item-custom i {
            font-size: 1.2rem;
            color: #667eea;
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
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .user-avatar i {
            font-size: 1.5rem;
            color: white;
        }

        .user-info {
            color: white;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.7rem;
            opacity: 0.8;
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

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: white;
            z-index: 1050;
            transition: left 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            left: 0;
        }

        .mobile-sidebar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            color: white;
        }

        .mobile-sidebar-content {
            padding: 1rem;
        }

        .mobile-nav-item {
            display: block;
            padding: 0.75rem 1rem;
            color: #4a5568;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .mobile-nav-item:hover,
        .mobile-nav-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .mobile-nav-item i {
            margin-right: 0.75rem;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .navbar-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .user-info {
                display: none;
            }
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
    </style>

    @stack('styles')
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="navbar-container">
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i class="material-symbols-rounded">menu</i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="material-symbols-rounded">gavel</i>
                <span>BidMaster</span>
            </a>

            <!-- Desktop Navigation Menu -->
            <div class="navbar-menu">
                @auth
                    @role('admin')
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="material-symbols-rounded">people</i>
                            <span>Utilisateurs</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="material-symbols-rounded">category</i>
                            <span>Catégories</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('admin.auctions*') ? 'active' : '' }}" href="{{ route('admin.auctions.index') }}">
                            <i class="material-symbols-rounded">gavel</i>
                            <span>Enchères</span>
                        </a>
                    @endrole

                    @role('vendeur')
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('annonces.index') ? 'active' : '' }}" href="{{ route('annonces.index') }}">
                            <i class="material-symbols-rounded">inventory_2</i>
                            <span>Mes Annonces</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('annonces.create') ? 'active' : '' }}" href="{{ route('annonces.create') }}">
                            <i class="material-symbols-rounded">add_circle</i>
                            <span>Créer</span>
                        </a>
                    @endrole

                    @role('client')
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="material-symbols-rounded">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('auctions.active') ? 'active' : '' }}" href="{{ route('auctions.active') }}">
                            <i class="material-symbols-rounded">gavel</i>
                            <span>Enchères</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('my.bids') ? 'active' : '' }}" href="{{ route('my.bids') }}">
                            <i class="material-symbols-rounded">history</i>
                            <span>Mes Offres</span>
                        </a>
                        <a class="nav-link-custom {{ request()->routeIs('my.won') ? 'active' : '' }}" href="{{ route('my.won') }}">
                            <i class="material-symbols-rounded">emoji_events</i>
                            <span>Gagnées</span>
                        </a>
                    @endrole
                @endauth
            </div>

            <!-- User Menu -->
            <div class="user-menu">
                @auth
                    <!-- Notifications -->
                    <div class="nav-item-custom">
                        <div class="notification-badge" onclick="toggleNotifications()">
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
                            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">
                                <strong>Notifications</strong>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline float-end">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0">Tout marquer lu</button>
                                    </form>
                                @endif
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                @forelse(Auth::check() && Auth::user()->client ? Auth::user()->client->notifications()->latest()->take(5)->get() : [] as $notification)
                                    <a class="dropdown-item-custom" href="{{ route('notifications.mark', $notification) }}" style="border-bottom: 1px solid #f0f0f0;">
                                        <div>
                                            <div style="font-size: 0.85rem;">{{ $notification->message }}</div>
                                            <small style="font-size: 0.7rem; color: #a0aec0;">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                @empty
                                    <div style="padding: 1rem; text-align: center; color: #a0aec0;">
                                        Aucune notification
                                    </div>
                                @endforelse
                            </div>
                            @if(Auth::check() && Auth::user()->client && Auth::user()->client->notifications()->count() > 0)
                                <div style="padding: 0.75rem; text-align: center; border-top: 1px solid #e2e8f0;">
                                    <a href="{{ route('notifications.index') }}" style="color: #667eea; text-decoration: none;">Voir toutes les notifications</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="nav-item-custom">
                        <div class="user-avatar">
                            <i class="material-symbols-rounded">account_circle</i>
                        </div>
                        <div class="dropdown-menu-custom" style="right: 0; left: auto;">
                            <div style="padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                                <div><strong>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</strong></div>
                                <small style="color: #a0aec0;">{{ Auth::user()->email }}</small>
                            </div>
                            <a class="dropdown-item-custom" href="{{ route('profile.edit') }}">
                                <i class="material-symbols-rounded">person</i>
                                <span>Mon Profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item-custom" style="width: 100%; background: none; border: none; cursor: pointer;">
                                    <i class="material-symbols-rounded">logout</i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="mobile-sidebar-header">
            <h5 class="mb-0">BidMaster</h5>
            <small>Marketplace d'Enchères</small>
        </div>
        <div class="mobile-sidebar-content">
            @auth
                @role('admin')
                    <a class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="material-symbols-rounded">dashboard</i> Dashboard
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="material-symbols-rounded">people</i> Utilisateurs
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="material-symbols-rounded">category</i> Catégories
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('admin.auctions*') ? 'active' : '' }}" href="{{ route('admin.auctions.index') }}">
                        <i class="material-symbols-rounded">gavel</i> Enchères
                    </a>
                @endrole

                @role('vendeur')
                    <a class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="material-symbols-rounded">dashboard</i> Dashboard
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('annonces.index') ? 'active' : '' }}" href="{{ route('annonces.index') }}">
                        <i class="material-symbols-rounded">inventory_2</i> Mes Annonces
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('annonces.create') ? 'active' : '' }}" href="{{ route('annonces.create') }}">
                        <i class="material-symbols-rounded">add_circle</i> Créer une Annonce
                    </a>
                @endrole

                @role('client')
                    <a class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="material-symbols-rounded">dashboard</i> Dashboard
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('auctions.active') ? 'active' : '' }}" href="{{ route('auctions.active') }}">
                        <i class="material-symbols-rounded">gavel</i> Enchères Actives
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('my.bids') ? 'active' : '' }}" href="{{ route('my.bids') }}">
                        <i class="material-symbols-rounded">history</i> Mes Offres
                    </a>
                    <a class="mobile-nav-item {{ request()->routeIs('my.won') ? 'active' : '' }}" href="{{ route('my.won') }}">
                        <i class="material-symbols-rounded">emoji_events</i> Enchères Gagnées
                    </a>
                @endrole

                <hr>
                <a class="mobile-nav-item" href="{{ route('profile.edit') }}">
                    <i class="material-symbols-rounded">person</i> Mon Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i class="material-symbols-rounded">logout</i> Déconnexion
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeMobileSidebar()"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold">@yield('page-title', 'Tableau de Bord')</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 mt-2">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="text-decoration: none; color: #667eea;">Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

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

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>

    <script>
        // Mobile Sidebar Functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

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

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.notification-badge') && !event.target.closest('#notificationsDropdown')) {
                document.getElementById('notificationsDropdown').style.opacity = '0';
                document.getElementById('notificationsDropdown').style.visibility = 'hidden';
            }
        });

        // Smooth scroll
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