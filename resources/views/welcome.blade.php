{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marketplace d'Enchères - La meilleure plateforme pour enchérir et vendre</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap"
        rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

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

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
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
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .search-input-group {
            position: relative;
        }

        .search-input-group input {
            height: 60px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding-left: 30px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-input-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            height: 50px;
            width: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: scale(1.05);
        }

        .category-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.2);
        }

        .category-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-icon i {
            font-size: 2.5rem;
            color: #667eea;
        }

        .category-card h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .category-card p {
            color: #718096;
            font-size: 0.9rem;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 250px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
        }

        .product-badge.hot {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .product-price {
            color: #667eea;
            font-weight: 800;
            font-size: 1.3rem;
        }

        .product-old-price {
            color: #cbd5e0;
            text-decoration: line-through;
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .bid-count {
            color: #718096;
            font-size: 0.85rem;
        }

        .time-left {
            background: #f7fafc;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            display: inline-block;
        }

        .how-it-works {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step-card {
            text-align: center;
            padding: 2rem;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 1rem;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .rating {
            color: #fbbf24;
            margin-bottom: 1rem;
        }

        .newsletter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
        }

        .footer {
            background: #1a202c;
            color: #cbd5e0;
            padding: 3rem 0 2rem;
        }

        .footer a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .search-section {
                margin-top: 20px;
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-gavel me-2"></i>BidMaster
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">Comment ça marche</a>
                    </li>
                </ul>
                @if (Route::has('login'))
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-gradient">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-outline-gradient">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-gradient">Inscription</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <h1>Enchérissez sur des objets d'exception</h1>
                    <p>Découvrez la plus grande plateforme d'enchères en ligne. Des milliers de produits uniques vous
                        attendent. Enchérissez, gagnez et économisez !</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" id="totalUsers">0</span>
                            <span class="stat-label">Utilisateurs actifs</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="totalAuctions">0</span>
                            <span class="stat-label">Enchères en cours</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="totalBids">0</span>
                            <span class="stat-label">Offres placées</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="floating">
                        <img src="https://cdn-icons-png.flaticon.com/512/10012/10012129.png" alt="Auction"
                            class="img-fluid" style="max-width: 80%;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <div class="container" style="margin-top: -50px;">
        <div class="search-section" data-aos="fade-up">
            <div class="row">
                <div class="col-12">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Rechercher un produit, une marque ou une catégorie...">
                        <button class="search-btn" onclick="searchProducts()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="py-5" id="categories">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Catégories Populaires</h2>
                <p class="text-muted">Explorez nos catégories et trouvez ce qui vous passionne</p>
            </div>
            <div class="row g-4" id="categoriesContainer">
                <!-- Categories will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5 bg-light" id="products">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Produits Tendances</h2>
                <p class="text-muted">Les enchères les plus populaires du moment</p>
            </div>
            <div class="row g-4" id="productsContainer">
                <!-- Products will be loaded here -->
            </div>
            <div class="text-center mt-4">
                <button class="btn-gradient" onclick="loadMoreProducts()" id="loadMoreBtn">
                    <i class="fas fa-sync-alt me-2"></i>Charger plus
                </button>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works py-5" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Comment ça marche ?</h2>
                <p class="opacity-90">Enchérissez en toute simplicité en 3 étapes</p>
            </div>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4>Inscrivez-vous</h4>
                        <p>Créez votre compte gratuitement et commencez à enchérir</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4>Enchérissez</h4>
                        <p>Parcourez les annonces et placez vos offres sur les produits qui vous intéressent</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4>Gagnez & Recevez</h4>
                        <p>Remportez l'enchère et recevez votre produit chez vous</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Ce que nos clients disent</h2>
                <p class="text-muted">Des milliers de clients satisfaits</p>
            </div>
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper" id="testimonialsContainer">
                    <!-- Testimonials will be loaded here -->
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-5">
        <div class="container">
            <div class="newsletter" data-aos="zoom-in">
                <h3 class="fw-bold mb-3">Ne manquez aucune enchère !</h3>
                <p class="mb-4">Recevez les meilleures offres directement dans votre boîte mail</p>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Votre adresse email"
                                id="newsletterEmail">
                            <button class="btn btn-light" onclick="subscribeNewsletter()">
                                <i class="fas fa-paper-plane me-2"></i>S'abonner
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">BidMaster</h5>
                    <p>La plateforme d'enchères n°1 pour acheter et vendre des produits d'occasion et neufs.</p>
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-white mb-3">Liens rapides</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#products">Produits</a></li>
                        <li><a href="#categories">Catégories</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Aide</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Contact</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> contact@bidmaster.com</li>
                        <li><i class="fas fa-phone me-2"></i> +212 5XX XXX XXX</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Casablanca, Maroc</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 BidMaster. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Pass authentication status from Laravel to JavaScript
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Load statistics
        function loadStats() {
            $.ajax({
                url: '/api/stats',
                method: 'GET',
                success: function (data) {
                    animateCounter($('#totalUsers'), data.total_users);
                    animateCounter($('#totalAuctions'), data.active_auctions);
                    animateCounter($('#totalBids'), data.total_bids);
                },
                error: function (xhr) {
                    console.log('Stats error:', xhr);
                }
            });
        }

        // Animate counter
        function animateCounter(element, to) {
            var from = 0;
            var duration = 2000;
            var increment = to / (duration / 16);
            var current = from;
            var timer = setInterval(function () {
                current += increment;
                if (current >= to) {
                    element.text(to);
                    clearInterval(timer);
                } else {
                    element.text(Math.floor(current));
                }
            }, 16);
        }

        // Load categories
        function loadCategories() {
            $.ajax({
                url: '/api/categories',
                method: 'GET',
                success: function (data) {
                    let html = '';
                    data.forEach(category => {
                        html += `
                        <div class="col-md-3 col-6">
                            <div class="category-card" onclick="filterByCategory(${category.id})">
                                <div class="category-icon">
                                    <i class="fas ${category.icon || 'fa-tag'}"></i>
                                </div>
                                <h5>${escapeHtml(category.nom)}</h5>
                                <p>${category.produits_count || 0} produits</p>
                            </div>
                        </div>
                    `;
                    });
                    $('#categoriesContainer').html(html);
                },
                error: function (xhr) {
                    console.log('Categories error:', xhr);
                    $('#categoriesContainer').html('<div class="col-12 text-center"><p>Erreur de chargement des catégories</p></div>');
                }
            });
        }

        let currentPage = 1;
        let isLoading = false;
        let hasMore = true;
        let currentSearch = '';
        let currentCategoryId = null;

        // Helper function to get image URL with fallback
        function getImageUrl(product) {
            if (product.image && product.image !== 'https://via.placeholder.com/300x250?text=No+Image' && product.image !== 'https://via.placeholder.com/300x250?text=Product') {
                return product.image;
            }
            return `https://via.placeholder.com/300x250?text=${encodeURIComponent(product.titre.substring(0, 20))}`;
        }

        // Load products using the main API with search and category filter
        function loadProducts(page = 1, search = '', categoryId = null) {
            if (isLoading) return;
            isLoading = true;

            if (page === 1) {
                $('#productsContainer').html('<div class="text-center py-5"><div class="loading"></div><p class="mt-3">Chargement...</p></div>');
            } else {
                $('#loadMoreBtn').html('<div class="loading"></div> Chargement...');
            }

            let url = '/api/products?page=' + page;
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }
            if (categoryId) {
                url += '&category_id=' + categoryId;
            }

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    let html = '';
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(product => {
                            const timeLeft = getTimeLeft(product.date_fin);
                            const isHot = product.bid_count > 5;
                            const imageUrl = getImageUrl(product);
                            html += `
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="${imageUrl}" alt="${escapeHtml(product.titre)}" onerror="this.src='https://via.placeholder.com/300x250?text=Image+non+disponible'">
                                        <div class="product-badge ${isHot ? 'hot' : ''}">
                                            ${isHot ? '🔥 Tendance' : '🎯 Nouveau'}
                                        </div>
                                    </div>
                                    <div class="product-body">
                                        <h5 class="product-title">${escapeHtml(product.titre)}</h5>
                                        <div class="mb-2">
                                            <span class="product-price">${formatPrice(product.current_price)} MAD</span>
                                            ${product.original_price > product.current_price ? `<span class="product-old-price">${formatPrice(product.original_price)} MAD</span>` : ''}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="bid-count"><i class="fas fa-gavel me-1"></i> ${product.bid_count || 0} enchères</span>
                                            <span class="time-left"><i class="fas fa-clock me-1"></i> ${timeLeft}</span>
                                        </div>
                                        <button class="btn-gradient w-100 mt-2" style="padding: 0.6rem;" onclick="redirectToAuction(${product.id})">
                                            <i class="fas fa-gavel me-2"></i>Participer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        html = '<div class="col-12 text-center py-5"><i class="fas fa-box-open fa-3x text-muted mb-3"></i><h5>Aucun produit trouvé</h5><p class="text-muted">Essayez une autre recherche ou consultez nos catégories.</p></div>';
                    }

                    if (page === 1) {
                        $('#productsContainer').html(html);
                    } else {
                        $('#productsContainer').append(html);
                    }

                    hasMore = response.current_page < response.last_page;
                    if (!hasMore) {
                        $('#loadMoreBtn').prop('disabled', true).html('<i class="fas fa-check me-2"></i>Plus de produits');
                    } else {
                        $('#loadMoreBtn').prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i>Charger plus');
                    }

                    currentPage = response.current_page;
                    isLoading = false;
                },
                error: function (xhr) {
                    console.log('Products error:', xhr);
                    isLoading = false;
                    $('#loadMoreBtn').prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i>Réessayer');
                    if (page === 1) {
                        $('#productsContainer').html('<div class="col-12 text-center py-5"><i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><h5>Erreur de chargement</h5><p class="text-muted">Veuillez réessayer plus tard.</p></div>');
                    }
                }
            });
        }

        // Redirect to auction detail or login page based on authentication status
        function redirectToAuction(auctionId) {
            if (window.isAuthenticated) {
                window.location.href = '/annonces/' + auctionId;
            } else {
                window.location.href = '/login';
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load more products
        function loadMoreProducts() {
            if (!isLoading && hasMore) {
                loadProducts(currentPage + 1, currentSearch, currentCategoryId);
            }
        }

        // Search products (resets pagination and category)
        function searchProducts() {
            currentSearch = $('#searchInput').val();
            currentCategoryId = null;
            currentPage = 1;
            loadProducts(1, currentSearch, null);
        }

        // Filter by category (resets search and pagination)
        function filterByCategory(categoryId) {
            currentCategoryId = categoryId;
            currentSearch = '';
            $('#searchInput').val('');
            currentPage = 1;
            loadProducts(1, '', categoryId);
        }

        // Load testimonials
        function loadTestimonials() {
            const testimonials = [
                { name: "Marie Laurent", rating: 5, text: "Excellente plateforme ! J'ai trouvé des articles rares à des prix imbattables.", avatar: "https://randomuser.me/api/portraits/women/1.jpg" },
                { name: "Thomas Dubois", rating: 5, text: "Service client réactif et processus d'enchères très fluide. Je recommande !", avatar: "https://randomuser.me/api/portraits/men/2.jpg" },
                { name: "Sophie Martin", rating: 4, text: "Très satisfaite de mon achat. La livraison a été rapide et le produit conforme.", avatar: "https://randomuser.me/api/portraits/women/3.jpg" }
            ];

            let html = '';
            testimonials.forEach(t => {
                html += `
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <img src="${t.avatar}" class="testimonial-avatar me-3" alt="${t.name}">
                            <div>
                                <h6 class="mb-0">${t.name}</h6>
                                <div class="rating">
                                    ${'★'.repeat(t.rating)}${'☆'.repeat(5 - t.rating)}
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">"${t.text}"</p>
                    </div>
                </div>
            `;
            });
            $('#testimonialsContainer').html(html);

            new Swiper('.testimonial-swiper', {
                loop: true,
                autoplay: { delay: 5000 },
                pagination: { el: '.swiper-pagination', clickable: true },
                slidesPerView: 1,
                breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
            });
        }

        // Helper functions
        function formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(price);
        }

        function getTimeLeft(date) {
            const diff = new Date(date) - new Date();
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (86400000)) / (3600000));
            if (days > 0) return `${days}j ${hours}h`;
            if (hours > 0) return `${hours}h`;
            return "Bientôt fini";
        }

        function subscribeNewsletter() {
            const email = $('#newsletterEmail').val();
            if (email && email.includes('@')) {
                alert('Merci pour votre inscription ! Vous recevrez nos meilleures offres.');
                $('#newsletterEmail').val('');
            } else {
                alert('Veuillez entrer une adresse email valide.');
            }
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Initialize
        $(document).ready(function () {
            loadStats();
            loadCategories();
            loadProducts(); // loads first page with no filter
            loadTestimonials();

            $('#searchInput').keypress(function (e) {
                if (e.which === 13) searchProducts();
            });
        });
    </script>
</body>

</html>