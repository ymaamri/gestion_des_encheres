{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Produits | BidMaster')
@section('page-title', 'Gestion des Produits')
@section('breadcrumb', 'Produits')

@section('content')
    @php
        // Compute global stats for seller
        $vendeur = auth()->user()->client?->vendeur;
        $totalProductsStat = \App\Models\Produit::where('vendeur_id', $vendeur?->id)->count();
        $totalAuctionsStat = $vendeur?->annonces()->count() ?? 0;
        $totalBidsStat = $vendeur?->annonces()->withCount('encheres')->get()->sum('encheres_count') ?? 0;
        $avgPriceStat = $vendeur?->annonces()->avg('prix_depart') ?? 0;
    @endphp

    <div class="stellar-products-container">
        {{-- Cosmic Background Layers --}}
        <div class="nebula-mist">
            <div class="mist-1"></div>
            <div class="mist-2"></div>
            <div class="mist-3"></div>
        </div>

        {{-- Hero / Stats Constellation --}}
        <div class="inventory-hero">
            <div class="hero-orb"></div>
            <div class="hero-content-stellar">
                <div class="hero-icon-marker">
                    <i class="fas fa-cube"></i>
                </div>
                <h1 class="hero-glow-title">Catalogue <span class="gradient-flare">Cosmique</span></h1>
                <p class="hero-quote">Gérez votre galaxie de produits avec style & puissance</p>
                <div class="hero-stats-cluster">
                    <div class="stat-cluster-item">
                        <span class="cluster-number" id="statTotalProducts">{{ $totalProductsStat }}</span>
                        <span class="cluster-label">Produits totaux</span>
                    </div>
                    <div class="stat-cluster-divider"></div>
                    <div class="stat-cluster-item">
                        <span class="cluster-number" id="statActiveAuctionsCluster">{{ $totalAuctionsStat }}</span>
                        <span class="cluster-label">Enchères actives</span>
                    </div>
                    <div class="stat-cluster-divider"></div>
                    <div class="stat-cluster-item">
                        <span class="cluster-number" id="statTotalBidsCluster">{{ $totalBidsStat }}</span>
                        <span class="cluster-label">Offres reçues</span>
                    </div>
                    <div class="stat-cluster-divider"></div>
                    <div class="stat-cluster-item">
                        <span class="cluster-number">{{ number_format($avgPriceStat, 0) }} TND</span>
                        <span class="cluster-label">Prix moyen</span>
                    </div>
                </div>
            </div>
            <div class="hero-wave-crater">
                <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                    <path d="M0,64 C240,128 480,0 720,32 C960,64 1200,96 1440,64 L1440,120 L0,120 Z" fill="#ffffff"
                        opacity="0.9"></path>
                </svg>
            </div>
        </div>

        {{-- Action Bar + Search Nebula --}}
        <div class="stellar-action-bar">
            <div class="search-nebula">
                <i class="fas fa-search search-stellar-icon"></i>
                <input type="text" id="stellarSearchInput" class="stellar-search-input"
                    placeholder="Rechercher par nom, marque, catégorie...">
                <button id="clearStellarSearch" class="clear-stellar-btn" style="display: none;"><i
                        class="fas fa-times-circle"></i></button>
            </div>
            <a href="{{ route('seller.products.create') }}" class="stellar-create-btn">
                <i class="fas fa-plus-circle"></i> Nouveau produit
                <span class="btn-cosmic-shine"></span>
            </a>
        </div>

        {{-- Products Cosmic Grid --}}
        <div class="products-cosmic-grid" id="stellarProductsGrid">
            @forelse($products as $product)
                @php
                    $productImage = \App\Helpers\ImageHelper::getProductImage($product);
                    $categoryName = $product->sousCategorie?->categorie?->nom ?? ($product->sousCategorie?->nom ?? 'Non catégorisé');
                    $auctionCount = $product->annonces->count();
                    $etatLabel = match ($product->etat) {
                        'NEUF' => 'Neuf',
                        'TRES_BON_ETAT' => 'Très bon',
                        'BON_ETAT' => 'Bon',
                        default => 'Acceptable'
                    };
                    $etatClass = match ($product->etat) {
                        'NEUF' => 'badge-neuf',
                        'TRES_BON_ETAT' => 'badge-tresbon',
                        'BON_ETAT' => 'badge-bon',
                        default => 'badge-standard'
                    };
                @endphp
                <div class="product-cosmic-card" data-product-name="{{ strtolower($product->nom) }}"
                    data-product-brand="{{ strtolower($product->marque) }}" data-category="{{ strtolower($categoryName) }}">
                    <div class="card-stellar-glow"></div>
                    <div class="card-stellar-inner">
                        <div class="product-visual">
                            <div class="product-img-wrapper">
                                <img src="{{ $productImage }}" alt="{{ $product->nom }}" loading="lazy">
                                <div class="img-shimmer-overlay"></div>
                            </div>
                            <div class="product-badge-state {{ $etatClass }}">
                                <i class="fas fa-star-of-life"></i> {{ $etatLabel }}
                            </div>
                            @if($auctionCount > 0)
                                <div class="product-badge-auctions">
                                    <i class="fas fa-gavel"></i> {{ $auctionCount }} enchère(s)
                                </div>
                            @endif
                        </div>
                        <div class="product-info-stellar">
                            <div class="product-title-wrap">
                                <h3 class="product-title">{{ $product->nom }}</h3>
                                @if($product->marque || $product->modele)
                                    <div class="product-brand-model">
                                        {{ $product->marque }} {{ $product->modele }}
                                    </div>
                                @endif
                            </div>
                            <div class="product-category-chip">
                                <i class="fas fa-tag"></i> {{ $categoryName }}
                            </div>
                            <div class="product-meta-eclipse">
                                <div class="meta-eclipse-item">
                                    <i class="fas fa-chart-line"></i>
                                    <span>{{ $auctionCount }} ventes potentielles</span>
                                </div>
                                <div class="meta-eclipse-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Ajouté le {{ $product->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="product-actions-nebula">
                                <a href="{{ route('seller.products.show', $product) }}" class="action-nebula view-action"
                                    title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                    <span>Explorer</span>
                                </a>
                                <a href="{{ route('seller.products.edit', $product) }}" class="action-nebula edit-action"
                                    title="Modifier produit">
                                    <i class="fas fa-pen-fancy"></i>
                                    <span>Éditer</span>
                                </a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST"
                                    class="delete-product-form"
                                    onsubmit="return confirm('⚠️ Supprimer définitivement ce produit ? Toutes les annonces liées seront affectées.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-nebula delete-action" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                        <span style="padding-right: 5px">Effacer</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-edge-radial"></div>
                </div>
            @empty
                <div class="empty-stellar-universe">
                    <div class="empty-animation-planet">
                        <i class="fas fa-box-open"></i>
                        <div class="planet-ring"></div>
                    </div>
                    <h3>L'univers est vide...</h3>
                    <p>Votre catalogue ne contient aucun produit. Lancez-vous et créez votre première pépite.</p>
                    <a href="{{ route('seller.products.create') }}" class="hero-btn-primary cosmic-void-btn">
                        <i class="fas fa-plus-circle"></i> Créer un produit
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Stellar Pagination --}}
        @if($products->hasPages())
            <div class="stellar-pagination-container">
                <div class="pagination-stats">
                    Affichage de <strong>{{ $products->firstItem() }}</strong> à <strong>{{ $products->lastItem() }}</strong>
                    sur <strong>{{ $products->total() }}</strong> produits
                </div>
                <div class="pagination-stellar-links">
                    {{-- Previous --}}
                    @if($products->onFirstPage())
                        <span class="stellar-page disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="stellar-page"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Page numbers (smart range) --}}
                    @php
                        $current = $products->currentPage();
                        $last = $products->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp
                    @if($start > 1)
                        <a href="{{ $products->url(1) }}" class="stellar-page">1</a>
                        @if($start > 2) <span class="stellar-page dots">...</span> @endif
                    @endif
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $current)
                            <span class="stellar-page active">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="stellar-page">{{ $i }}</a>
                        @endif
                    @endfor
                    @if($end < $last)
                        @if($end < $last - 1) <span class="stellar-page dots">...</span> @endif
                        <a href="{{ $products->url($last) }}" class="stellar-page">{{ $last }}</a>
                    @endif

                    {{-- Next --}}
                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="stellar-page"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="stellar-page disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ========== RESET & GLOBAL VARIABLES (No external libs) ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .stellar-products-container {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-light: #818cf8;
            --primary-dark: #5b21b6;
            --glass-bg: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(102, 126, 234, 0.18);
            --shadow-elevation: 0 25px 40px -12px rgba(0, 0, 0, 0.15);
            --shadow-glow: 0 20px 35px -8px rgba(102, 126, 234, 0.3);
            --radius-xl: 40px;
            --radius-lg: 28px;
            --radius-md: 20px;
            --transition-smooth: all 0.4s cubic-bezier(0.2, 0.95, 0.4, 1.05);
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            position: relative;
            z-index: 2;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* Nebula Background */
        .nebula-mist {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            pointer-events: none;
            overflow: hidden;
        }

        .mist-1,
        .mist-2,
        .mist-3 {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: floatNebula 25s infinite alternate;
        }

        .mist-1 {
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
            top: -20%;
            left: -15%;
        }

        .mist-2 {
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.2), transparent);
            bottom: -10%;
            right: -10%;
            animation-duration: 30s;
            animation-direction: alternate-reverse;
        }

        .mist-3 {
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15), transparent);
            top: 40%;
            left: 30%;
            animation-duration: 20s;
            filter: blur(120px);
        }

        @keyframes floatNebula {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }

            100% {
                transform: translate(4%, 6%) scale(1.2);
                opacity: 0.6;
            }
        }

        /* Hero Section Stellar */
        .inventory-hero {
            position: relative;
            background: var(--primary-gradient);
            border-radius: var(--radius-xl);
            margin-bottom: 2.5rem;
            padding: 3rem 2rem 4rem;
            overflow: hidden;
            box-shadow: var(--shadow-elevation);
            z-index: 3;
        }

        .hero-orb {
            position: absolute;
            top: -80px;
            right: -60px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 240, 0.2), transparent);
            border-radius: 50%;
            filter: blur(50px);
            pointer-events: none;
        }

        .hero-content-stellar {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding-bottom: 70px;
        }

        .hero-icon-marker {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.6rem;
            font-size: 2.4rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: pulseOrbit 2.5s infinite;
        }

        @keyframes pulseOrbit {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
                transform: scale(1);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
                transform: scale(1.02);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
                transform: scale(1);
            }
        }

        .hero-glow-title {
            font-size: 3.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 0.6rem;
        }

        .gradient-flare {
            background: linear-gradient(135deg, #f9f3a0, #ffe6b0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-quote {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .hero-stats-cluster {
            display: flex;
            justify-content: center;
            gap: 2rem;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.12);
            border-radius: 80px;
            padding: 1rem 2rem;
            width: fit-content;
            margin: 0 auto;
            flex-wrap: wrap;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .stat-cluster-item {
            text-align: center;
            padding: 0 1rem;
        }

        .cluster-number {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .cluster-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            opacity: 0.85;
        }

        .stat-cluster-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
            height: 40px;
            margin: auto 0;
        }

        .hero-wave-crater {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }

        /* Action Bar */
        .stellar-action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.2rem;
            margin-bottom: 2.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 70px;
            padding: 0.6rem 1.2rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .search-nebula {
            position: relative;
            flex: 2;
            min-width: 240px;
        }

        .search-stellar-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            z-index: 2;
        }

        .stellar-search-input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }

        .stellar-search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .clear-stellar-btn {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .stellar-create-btn {
            background: var(--primary-gradient);
            padding: 0.7rem 1.8rem;
            border-radius: 60px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.4);
        }

        .stellar-create-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.5);
        }

        .btn-cosmic-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }

        .stellar-create-btn:hover .btn-cosmic-shine {
            left: 100%;
        }

        /* Products Grid - Cosmic Cards */
        .products-cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            margin: 1.5rem 0;
        }

        .product-cosmic-card {
            position: relative;
            border-radius: var(--radius-lg);
            transition: var(--transition-smooth);
            animation: fadeUp 0.6s ease both;
            height: 100%;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-cosmic-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .product-cosmic-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .product-cosmic-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .product-cosmic-card:hover {
            transform: translateY(-8px);
        }

        .card-stellar-glow {
            position: absolute;
            inset: -2px;
            background: var(--primary-gradient);
            border-radius: calc(var(--radius-lg) + 2px);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }

        .product-cosmic-card:hover .card-stellar-glow {
            opacity: 0.4;
        }

        .card-stellar-inner {
            position: relative;
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-cosmic-card:hover .card-stellar-inner {
            box-shadow: var(--shadow-glow);
        }

        .product-visual {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .product-img-wrapper {
            width: 100%;
            height: 100%;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .product-cosmic-card:hover .product-img-wrapper img {
            transform: scale(1.05);
        }

        .img-shimmer-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
            pointer-events: none;
        }

        .product-cosmic-card:hover .img-shimmer-overlay {
            transform: translateX(100%);
        }

        .product-badge-state {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            letter-spacing: 0.3px;
        }

        .badge-neuf {
            background: linear-gradient(135deg, #2dce89, #2dcecc);
        }

        .badge-tresbon {
            background: linear-gradient(135deg, #11cdef, #1171ef);
        }

        .badge-bon {
            background: linear-gradient(135deg, #fb6340, #fbb140);
        }

        .badge-standard {
            background: linear-gradient(135deg, #8965e0, #764ba2);
        }

        .product-badge-auctions {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(8px);
            padding: 0.3rem 1rem;
            border-radius: 60px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #ffd966;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
        }

        .product-info-stellar {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title-wrap {
            margin-bottom: 0.5rem;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .product-brand-model {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        .product-category-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            margin: 0.8rem 0;
            width: fit-content;
        }

        .product-meta-eclipse {
            display: flex;
            gap: 1rem;
            margin: 0.8rem 0;
            flex-wrap: wrap;
        }

        .meta-eclipse-item {
            background: #f8fafc;
            border-radius: 30px;
            padding: 0.4rem 1rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #2c3e66;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-actions-nebula {
            display: flex;
            gap: 0.8rem;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #eef2ff;
        }

        .action-nebula {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.6rem 0.4rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            transition: var(--transition-smooth);
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .view-action {
            background: rgba(102, 126, 234, 0.1);
            color: #4f46e5;
        }

        .view-action:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.3);
        }

        .edit-action {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .edit-action:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
        }

        .delete-action {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            width: 100%;
        }

        .delete-action:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }

        .card-edge-radial {
            position: absolute;
            inset: 0;
            border-radius: var(--radius-lg);
            pointer-events: none;
            opacity: 0;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) inset;
            transition: opacity 0.2s;
        }

        .product-cosmic-card:hover .card-edge-radial {
            opacity: 1;
        }

        /* Empty State Creative */
        .empty-stellar-universe {
            grid-column: 1/-1;
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-radius: var(--radius-xl);
            border: 1px solid var(--glass-border);
        }

        .empty-animation-planet {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            font-size: 4rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .planet-ring {
            position: absolute;
            width: 130%;
            height: 130%;
            border-radius: 50%;
            border: 2px dashed #a5b4fc;
            animation: spinRing 10s linear infinite;
            top: -15%;
            left: -15%;
        }

        @keyframes spinRing {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .cosmic-void-btn {
            display: inline-flex;
            margin-top: 1.5rem;
            background: var(--primary-gradient);
            padding: 0.8rem 2rem;
            border-radius: 60px;
            color: white;
            font-weight: 700;
            text-decoration: none;
        }

        /* Pagination Stellar */
        .stellar-pagination-container {
            margin-top: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.2rem;
            background: white;
            padding: 1rem 2rem;
            border-radius: 80px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.03);
            border: 1px solid #eef2ff;
        }

        .pagination-stats {
            font-size: 0.85rem;
            color: #475569;
        }

        .pagination-stellar-links {
            display: flex;
            gap: 0.5rem;
        }

        .stellar-page {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            border-radius: 60px;
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .stellar-page.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .stellar-page:hover:not(.disabled):not(.active) {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .stellar-page.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .stellar-page.dots {
            background: transparent;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .stellar-products-container {
                padding: 0 1rem 2rem;
            }

            .hero-glow-title {
                font-size: 2rem;
            }

            .hero-stats-cluster {
                gap: 0.5rem;
                padding: 0.8rem 1rem;
            }

            .stat-cluster-item {
                padding: 0 0.5rem;
            }

            .cluster-number {
                font-size: 1.2rem;
            }

            .products-cosmic-grid {
                grid-template-columns: 1fr;
            }

            .stellar-action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .stellar-create-btn {
                justify-content: center;
            }

            .product-actions-nebula {
                flex-wrap: wrap;
            }

            .pagination-stats {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 500px) {
            .hero-stats-cluster {
                flex-direction: column;
                gap: 0.8rem;
                border-radius: 40px;
            }

            .stat-cluster-divider {
                display: none;
            }

            .product-visual {
                height: 200px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // ========== LIVE SEARCH (filter products by name/brand/category) ==========
            const searchInput = document.getElementById('stellarSearchInput');
            const clearBtn = document.getElementById('clearStellarSearch');
            const productCards = document.querySelectorAll('.product-cosmic-card');
            const productsGrid = document.getElementById('stellarProductsGrid');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;
                productCards.forEach(card => {
                    const productName = card.dataset.productName || '';
                    const productBrand = card.dataset.productBrand || '';
                    const category = card.dataset.category || '';
                    const matches = productName.includes(searchTerm) || productBrand.includes(searchTerm) || category.includes(searchTerm);
                    if (matches || searchTerm === '') {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                // Handle empty search results: show a temporary message if needed
                let emptyMsg = document.querySelector('.stellar-no-result');
                if (visibleCount === 0 && productCards.length > 0) {
                    if (!emptyMsg) {
                        const msgDiv = document.createElement('div');
                        msgDiv.className = 'empty-stellar-universe stellar-no-result';
                        msgDiv.innerHTML = `<div class="empty-animation-planet"><i class="fas fa-search"></i></div>
                                                                                                <h3>Aucun produit trouvé</h3>
                                                                                                <p>Essayez un autre mot-clé ou créez un nouveau produit.</p>
                                                                                                <a href="{{ route('seller.products.create') }}" class="cosmic-void-btn">Créer un produit</a>`;
                        productsGrid.appendChild(msgDiv);
                    }
                } else {
                    if (emptyMsg) emptyMsg.remove();
                }
                if (clearBtn) clearBtn.style.display = searchTerm !== '' ? 'flex' : 'none';
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterProducts);
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        filterProducts();
                    });
                }
            }

            // small stats animation (counter effect)
            function animateNumber(elementId, target) {
                const el = document.getElementById(elementId);
                if (!el) return;
                let current = 0;
                const duration = 800;
                const steps = 30;
                const increment = target / steps;
                let step = 0;
                const timer = setInterval(() => {
                    step++;
                    current = Math.min(Math.floor(increment * step), target);
                    el.textContent = current.toLocaleString();
                    if (step >= steps) {
                        el.textContent = target.toLocaleString();
                        clearInterval(timer);
                    }
                }, duration / steps);
            }
            // Optional: animate numbers if they exist on page load
            const totalProdSpan = document.getElementById('statTotalProducts');
            if (totalProdSpan && totalProdSpan.innerText) {
                let targetVal = parseInt(totalProdSpan.innerText);
                if (!isNaN(targetVal)) animateNumber('statTotalProducts', targetVal);
            }
            const totalAucSpan = document.getElementById('statActiveAuctionsCluster');
            if (totalAucSpan && totalAucSpan.innerText) {
                let target = parseInt(totalAucSpan.innerText);
                if (!isNaN(target)) animateNumber('statActiveAuctionsCluster', target);
            }
            const totalBidsSpan = document.getElementById('statTotalBidsCluster');
            if (totalBidsSpan && totalBidsSpan.innerText) {
                let target = parseInt(totalBidsSpan.innerText);
                if (!isNaN(target)) animateNumber('statTotalBidsCluster', target);
            }
        })();
    </script>
@endpush