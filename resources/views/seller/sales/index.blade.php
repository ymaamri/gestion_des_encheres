{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/sales/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Galerie des Ventes | BidMaster')
@section('page-title', 'Mes Ventes')
@section('breadcrumb', 'Ventes')

@section('content')
    @php
        use App\Helpers\ImageHelper;
        $seller = auth()->user()?->client?->vendeur;
        $closedAuctions = $seller ? $seller->annonces()->where('statut', 'CLOTUREE')->get() : collect();

        $totalSalesCount = $closedAuctions->count();
        $totalRevenue = $closedAuctions->sum(function ($a) {
            $winningBid = $a->getHighestBid();
            return $winningBid ? $winningBid->montant : ($a->prix_final ?? $a->prix_depart);
        });
        $averagePrice = $totalSalesCount > 0 ? $totalRevenue / $totalSalesCount : 0;
        $productsSold = $closedAuctions->unique('produit_id')->count();

        $bestSale = $closedAuctions->sortByDesc(function ($a) {
            $winningBid = $a->getHighestBid();
            return $winningBid ? $winningBid->montant : 0;
        })->first();
        $bestAmount = $bestSale ? ($bestSale->getHighestBid()?->montant ?? $bestSale->prix_depart) : 0;
    @endphp

    <div class="cosmic-sales-container">
        {{-- 🌌 Galactic Background --}}
        <div class="galactic-bg">
            <div class="nebula-1"></div>
            <div class="nebula-2"></div>
            <div class="nebula-3"></div>
            <div class="star-field"></div>
        </div>

        {{-- ✨ Hero Section with Stats --}}
        <div class="sales-hero">
            <div class="hero-glow"></div>
            <div class="hero-content">
                <div class="hero-icon-wrapper">
                    <div class="hero-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <h1 class="hero-title">Triomphe des ventes</h1>
                <p class="hero-subtitle">Chaque enchère remportée est une nouvelle victoire. Célébrez votre succès.</p>

                <div class="stats-galactic-grid">
                    <div class="stat-galactic-card">
                        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-info">
                            <span class="stat-value" data-target="{{ $totalRevenue }}"
                                data-suffix=" TND">{{ number_format($totalRevenue, 0) }} TND</span>
                            <span class="stat-label">Chiffre d'affaires</span>
                        </div>
                    </div>
                    <div class="stat-galactic-card">
                        <div class="stat-icon"><i class="fas fa-gavel"></i></div>
                        <div class="stat-info">
                            <span class="stat-value" data-target="{{ $totalSalesCount }}">{{ $totalSalesCount }}</span>
                            <span class="stat-label">Ventes réalisées</span>
                        </div>
                    </div>
                    <div class="stat-galactic-card">
                        <div class="stat-icon"><i class="fas fa-chart-simple"></i></div>
                        <div class="stat-info">
                            <span class="stat-value" data-target="{{ $averagePrice }}" data-prefix="TND "
                                data-decimals="0">{{ number_format($averagePrice, 0) }} TND</span>
                            <span class="stat-label">Prix moyen</span>
                        </div>
                    </div>
                    <div class="stat-galactic-card">
                        <div class="stat-icon"><i class="fas fa-crown"></i></div>
                        <div class="stat-info">
                            <span class="stat-value" data-target="{{ $bestAmount }}"
                                data-prefix="TND ">{{ number_format($bestAmount, 0) }} TND</span>
                            <span class="stat-label">Meilleure vente</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-wave">
                <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                    <path d="M0,32 C240,0 480,64 720,64 C960,64 1200,0 1440,32 L1440,120 L0,120 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        {{-- 🔥 Sales Grid Section Title --}}
        <div class="section-header-cosmic">
            <div class="title-badge">
                <i class="fas fa-sparkle"></i> Catalogue des réussites
            </div>
            <h2 class="section-title">Trésors adjugés</h2>
            <p class="section-desc">Retrouvez l'ensemble de vos objets vendus aux enchères ainsi que les acheteurs gagnants.
            </p>
        </div>

        {{-- 🃏 Sales Cards Grid --}}
        <div class="sales-cosmic-grid" id="salesGrid">
            @forelse($salesPaginated as $sale)
                @php
                    $product = $sale->produit;
                    $productImage = ImageHelper::getProductImage($product);
                    $winner = $sale->winner ?? null;
                    $winningAmount = $sale->winning_bid_amount ?? ($sale->getHighestBid()?->montant ?? $sale->prix_final ?? $sale->prix_depart);
                    $endDate = \Carbon\Carbon::parse($sale->date_fin);
                    $categoryName = $product->categorie->nom ?? $product->sousCategorie?->categorie?->nom ?? 'Non catégorisé';
                @endphp
                <div class="sale-cosmic-card" data-id="{{ $sale->id }}">
                    <div class="card-orb-glow"></div>
                    <div class="card-inner">
                        <div class="card-media">
                            <div class="media-wrapper">
                                <img src="{{ $productImage }}" alt="{{ $sale->titre }}" loading="lazy">
                                <div class="media-overlay"></div>
                                <div class="sale-badge">
                                    <i class="fas fa-check-circle"></i> Vendu
                                </div>
                                <div class="price-float">
                                    <span class="price-float-value">{{ number_format($winningAmount, 0) }}</span>
                                    <span class="price-float-currency">TND</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-details">
                            <div class="card-header-meta">
                                <span class="category-chip">
                                    <i class="fas fa-tag"></i> {{ $categoryName }}
                                </span>
                                <span class="date-chip">
                                    <i class="fas fa-calendar-check"></i> {{ $endDate->format('d/m/Y') }}
                                </span>
                            </div>
                            <h3 class="card-title">{{ Str::limit($sale->titre, 55) }}</h3>
                            <p class="card-subtitle">{{ Str::limit($product->nom ?? 'Produit', 40) }}</p>

                            <div class="winner-section">
                                <div class="winner-avatar">
                                    {{ $winner ? strtoupper(substr($winner->nom, 0, 1) . substr($winner->prenom, 0, 1)) : '?' }}
                                </div>
                                <div class="winner-info">
                                    <span class="winner-label">Acquéreur</span>
                                    <span
                                        class="winner-name">{{ $winner ? $winner->nom . ' ' . $winner->prenom : 'Compte client supprimé' }}</span>
                                    @if($winner && $winner->user)
                                        <span class="winner-email">{{ $winner->user->email }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="bid-stats">
                                <div class="bid-stat-item">
                                    <span class="bid-stat-label">Prix de départ</span>
                                    <span class="bid-stat-value">{{ number_format($sale->prix_depart, 0) }} TND</span>
                                </div>
                                <div class="bid-stat-divider"></div>
                                <div class="bid-stat-item highlight">
                                    <span class="bid-stat-label">Prix final</span>
                                    <span class="bid-stat-value final">{{ number_format($winningAmount, 0) }} TND</span>
                                </div>
                                <div class="bid-stat-divider"></div>
                                <div class="bid-stat-item">
                                    <span class="bid-stat-label">Enchères</span>
                                    <span class="bid-stat-value">{{ $sale->encheres()->count() }}</span>
                                </div>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('annonces.show', $sale) }}" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i> Voir l'annonce
                                </a>
                                @if($winner && $winner->user)
                                    <a href="mailto:{{ $winner->user->email }}?subject=Félicitations! Vous avez remporté {{ $sale->titre }}&body=Bonjour, félicitations pour votre achat ! Voici les détails pour finaliser la transaction concernant l'objet : {{ $sale->titre }}."
                                        class="action-btn contact-btn">
                                        <i class="fas fa-paper-plane"></i> Contacter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-edge-shine"></div>
                </div>
            @empty
                <div class="empty-state-cosmic">
                    <div class="empty-state-icon">
                        <i class="fas fa-store-slash"></i>
                    </div>
                    <h3>Aucune vente enregistrée</h3>
                    <p>Votre espace vendeur n'a pas encore célébré de victoire. Lancez vos enchères dès maintenant.</p>
                    <a href="{{ route('annonces.create') }}" class="empty-cta-btn">
                        <i class="fas fa-plus-circle"></i> Créer une annonce
                    </a>
                </div>
            @endforelse
        </div>

        {{-- 🌟 Pagination Cosmic --}}
        @if($salesPaginated->hasPages())
            <div class="pagination-cosmic-wrapper">
                <div class="pagination-stats">
                    <i class="fas fa-chart-pie"></i> {{ $salesPaginated->firstItem() }} – {{ $salesPaginated->lastItem() }} sur
                    {{ $salesPaginated->total() }} ventes
                </div>
                <div class="pagination-cosmic">
                    {{-- Previous --}}
                    @if($salesPaginated->onFirstPage())
                        <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $salesPaginated->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @php
                        $current = $salesPaginated->currentPage();
                        $last = $salesPaginated->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $salesPaginated->url(1) }}" class="page-link">1</a>
                        @if($start > 2)
                            <span class="page-link dots">⋯</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $current)
                            <span class="page-link active">{{ $i }}</span>
                        @else
                            <a href="{{ $salesPaginated->url($i) }}" class="page-link">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="page-link dots">⋯</span>
                        @endif
                        <a href="{{ $salesPaginated->url($last) }}" class="page-link">{{ $last }}</a>
                    @endif

                    {{-- Next --}}
                    @if($salesPaginated->hasMorePages())
                        <a href="{{ $salesPaginated->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
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
        /* ------------------------------------------------------------
                                                RESET & GLOBAL COSMIC STYLES - PURE NATIVE CSS
                                                ------------------------------------------------------------ */
        .cosmic-sales-container {
            position: relative;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow-x: hidden;
            isolation: isolate;
        }

        /* ========== NEBULA & STAR BACKGROUND ========== */
        .galactic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            pointer-events: none;
            overflow: hidden;
        }

        .nebula-1 {
            position: absolute;
            top: -20%;
            left: -15%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.18) 0%, rgba(118, 75, 162, 0) 80%);
            filter: blur(90px);
            animation: floatNebula 28s infinite alternate;
        }

        .nebula-2 {
            position: absolute;
            bottom: -15%;
            right: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.2) 0%, rgba(102, 126, 234, 0) 80%);
            filter: blur(100px);
            animation: floatNebula 32s infinite alternate-reverse;
        }

        .nebula-3 {
            position: absolute;
            top: 40%;
            left: 20%;
            width: 45%;
            height: 45%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 80%);
            filter: blur(120px);
            animation: floatNebula 25s infinite alternate;
        }

        .star-field {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 15% 30%, rgba(255, 255, 240, 0.7) 1px, transparent 1px),
                radial-gradient(1px 1px at 72% 18%, rgba(255, 255, 210, 0.6) 1px, transparent 1px),
                radial-gradient(3px 3px at 45% 85%, rgba(255, 255, 200, 0.3) 1px, transparent 1px),
                radial-gradient(1px 1px at 88% 44%, rgba(255, 245, 200, 0.8) 1px, transparent 1px),
                radial-gradient(2px 2px at 33% 67%, rgba(255, 255, 220, 0.5) 1px, transparent 1px);
            background-size: 200px 200px;
            background-repeat: repeat;
            opacity: 0.6;
            animation: starTwinkle 5s infinite alternate;
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

        @keyframes starTwinkle {
            0% {
                opacity: 0.4;
            }

            100% {
                opacity: 0.8;
            }
        }

        /* ========== HERO SECTION PREMIUM ========== */
        .sales-hero {
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
            border-radius: 56px;
            margin-bottom: 4rem;
            padding: 3rem 2rem 4rem;
            overflow: hidden;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(2px);
        }

        .hero-glow {
            position: absolute;
            top: -30%;
            right: -10%;
            width: 60%;
            height: 150%;
            background: radial-gradient(circle, rgba(255, 255, 245, 0.25) 0%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding-bottom: 50px;
        }

        .hero-icon-wrapper {
            display: inline-flex;
            margin-bottom: 1.5rem;
        }

        .hero-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.35);
            animation: pulseIcon 2s infinite;
        }

        @keyframes pulseIcon {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 550px;
            margin: 0 auto 2rem;
        }

        .stats-galactic-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.8rem;
            margin-top: 1.5rem;
        }

        .stat-galactic-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 1rem 2rem;
            min-width: 160px;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .stat-galactic-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .stat-icon {
            font-size: 2rem;
            color: white;
            opacity: 0.9;
        }

        .stat-info {
            text-align: left;
        }

        .stat-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .hero-wave svg {
            display: block;
            width: 100%;
            height: auto;
        }

        /* ========== SECTION HEADER ========== */
        .section-header-cosmic {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .title-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(102, 126, 234, 0.12);
            padding: 0.4rem 1rem;
            border-radius: 60px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #667eea;
            backdrop-filter: blur(4px);
            margin-bottom: 0.8rem;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #2d3748);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .section-desc {
            color: #5e6f8d;
            max-width: 500px;
            margin: 0 auto;
        }

        /* ========== SALES COSMIC GRID ========== */
        .sales-cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .sale-cosmic-card {
            position: relative;
            border-radius: 36px;
            transition: all 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            animation: fadeSlideUp 0.6s ease both;
        }

        .sale-cosmic-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .sale-cosmic-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .sale-cosmic-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .sale-cosmic-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .sale-cosmic-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .sale-cosmic-card:nth-child(6) {
            animation-delay: 0.3s;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-orb-glow {
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 38px;
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }

        .sale-cosmic-card:hover .card-orb-glow {
            opacity: 0.35;
        }

        .card-inner {
            position: relative;
            background: white;
            border-radius: 36px;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12);
            transition: all 0.4s;
        }

        .sale-cosmic-card:hover .card-inner {
            transform: translateY(-8px);
            box-shadow: 0 28px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Media Area */
        .card-media {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .media-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .media-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s;
        }

        .sale-cosmic-card:hover .media-wrapper img {
            transform: scale(1.06);
        }

        .media-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 60%);
        }

        .sale-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            z-index: 2;
        }

        .price-float {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(12px);
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            display: flex;
            align-items: baseline;
            gap: 4px;
            z-index: 2;
            font-weight: 800;
        }

        .price-float-value {
            font-size: 1.4rem;
            background: linear-gradient(135deg, #FFD966, #FFB347);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .price-float-currency {
            font-size: 0.7rem;
            color: #FFD966;
            font-weight: 600;
        }

        /* Card Details */
        .card-details {
            padding: 1.5rem;
        }

        .card-header-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }

        .category-chip {
            font-size: 0.7rem;
            font-weight: 600;
            background: #f0f2ff;
            padding: 0.2rem 1rem;
            border-radius: 30px;
            color: #667eea;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .date-chip {
            font-size: 0.7rem;
            color: #7c8ba0;
            background: #f1f5f9;
            padding: 0.2rem 1rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.2rem;
            line-height: 1.3;
        }

        .card-subtitle {
            font-size: 0.8rem;
            color: #5e6f8d;
            margin-bottom: 1rem;
        }

        /* Winner section */
        .winner-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #f8fafc;
            padding: 0.8rem;
            border-radius: 24px;
            margin: 1rem 0;
        }

        .winner-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .winner-info {
            flex: 1;
        }

        .winner-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #94a3b8;
            display: block;
        }

        .winner-name {
            font-weight: 700;
            color: #1e293b;
            display: block;
            font-size: 0.9rem;
        }

        .winner-email {
            font-size: 0.7rem;
            color: #5e6f8d;
            word-break: break-all;
        }

        /* Bid stats */
        .bid-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            border: 1px solid #eef2ff;
            border-radius: 60px;
            padding: 0.5rem 1rem;
            margin-bottom: 1.4rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .bid-stat-item {
            text-align: center;
            flex: 1;
        }

        .bid-stat-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            display: block;
        }

        .bid-stat-value {
            font-weight: 700;
            color: #334155;
            font-size: 0.9rem;
        }

        .bid-stat-value.final {
            color: #667eea;
            font-size: 1rem;
            font-weight: 800;
        }

        .bid-stat-divider {
            width: 1px;
            height: 25px;
            background: #e2e8f0;
        }

        /* Card Actions */
        .card-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .action-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.7rem 0.5rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }

        .view-btn {
            background: #f1f5f9;
            color: #334155;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.3);
        }

        .contact-btn {
            background: #eef2ff;
            color: #667eea;
        }

        .contact-btn:hover {
            background: #10b981;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
        }

        .card-edge-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.45) 100%);
            transform: skewX(-25deg);
            transition: left 0.75s;
            pointer-events: none;
            z-index: 2;
        }

        .sale-cosmic-card:hover .card-edge-shine {
            left: 150%;
        }

        /* ========== EMPTY STATE COSMIC ========== */
        .empty-state-cosmic {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-radius: 56px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .empty-state-icon {
            font-size: 5rem;
            color: #a0aec0;
            margin-bottom: 1rem;
            animation: levitate 3s infinite;
        }

        @keyframes levitate {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .empty-state-cosmic h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f2937;
        }

        .empty-state-cosmic p {
            color: #5b6e8c;
            margin-bottom: 1.8rem;
        }

        .empty-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 0.8rem 2rem;
            border-radius: 60px;
            color: white;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .empty-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(102, 126, 234, 0.5);
        }

        /* ========== PAGINATION COSMIC ========== */
        .pagination-cosmic-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            padding: 1rem 2rem;
            border-radius: 80px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .pagination-stats {
            font-size: 0.85rem;
            font-weight: 500;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-cosmic {
            display: flex;
            gap: 0.4rem;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            border-radius: 30px;
            background: #f1f5f9;
            color: #1f2937;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .page-link:hover:not(.disabled):not(.dots) {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .page-link.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-link.dots {
            background: transparent;
            pointer-events: none;
            font-weight: 500;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 900px) {
            .cosmic-sales-container {
                padding: 0 1rem 2rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .stats-galactic-grid {
                gap: 1rem;
            }

            .stat-galactic-card {
                padding: 0.8rem 1rem;
                min-width: 130px;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .sales-cosmic-grid {
                grid-template-columns: 1fr;
            }

            .card-actions {
                flex-direction: column;
            }

            .pagination-cosmic-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .bid-stats {
                flex-wrap: wrap;
                gap: 0.5rem;
                border-radius: 28px;
            }

            .bid-stat-divider {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .stat-galactic-card {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Animated counters for stats
            const statValues = document.querySelectorAll('.stat-value');
            const animateNumber = (el) => {
                const target = parseFloat(el.getAttribute('data-target'));
                if (isNaN(target)) return;
                const decimals = el.getAttribute('data-decimals') ? parseInt(el.getAttribute('data-decimals')) : 0;
                const prefix = el.getAttribute('data-prefix') || '';
                const suffix = el.getAttribute('data-suffix') || '';
                let current = 0;
                const step = Math.ceil(target / 50);
                const interval = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        let formatted = target.toFixed(decimals);
                        if (decimals === 0) formatted = Math.floor(target).toString();
                        el.innerHTML = `${prefix}${formatted}${suffix}`;
                        clearInterval(interval);
                    } else {
                        let formatted = current.toFixed(decimals);
                        if (decimals === 0) formatted = Math.floor(current).toString();
                        el.innerHTML = `${prefix}${formatted}${suffix}`;
                    }
                }, 20);
            };
            statValues.forEach(animateNumber);

            // Optional: subtle parallax or hover (just for presence)
            const cards = document.querySelectorAll('.sale-cosmic-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    // nothing heavy, just visual
                });
            });
        })();
    </script>
@endpush