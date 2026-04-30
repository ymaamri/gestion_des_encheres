{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/auctions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Enchères')
@section('page-title', 'Gestion des Enchères')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="admin-auctions">
        {{-- ========== HERO HEADER ========== --}}
        <div class="auctions-hero">
            <div class="hero-overlay"></div>
            <div class="hero-particles">
                <span></span><span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span><span></span>
            </div>
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <h1 class="hero-title">Portail des Enchères</h1>
                <p class="hero-subtitle">Gérez, supervisez et propulsez les enchères de votre marketplace</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-value" id="totalAuctions">0</span>
                        <span class="stat-label">Totales</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-value" id="activeAuctions">0</span>
                        <span class="stat-label">Actives</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-value" id="pendingAuctions">0</span>
                        <span class="stat-label">En attente</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-value" id="totalBids">0</span>
                        <span class="stat-label">Offres</span>
                    </div>
                </div>
            </div>
            <svg class="hero-wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#ffffff" fill-opacity="1"
                    d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,250.7C960,235,1056,181,1152,165.3C1248,149,1344,171,1392,181.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>

        {{-- ========== FILTER & SEARCH BAR ========== --}}
        <div class="filter-bar">
            <div class="filter-bar-inner">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input"
                        placeholder="Rechercher par titre, vendeur, produit...">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-th-large"></i> Tous
                    </button>
                    <button class="filter-btn" data-filter="EN_ATTENTE">
                        <i class="fas fa-clock"></i> En attente
                    </button>
                    <button class="filter-btn" data-filter="ACTIVE">
                        <i class="fas fa-play-circle"></i> Actives
                    </button>
                    <button class="filter-btn" data-filter="CLOTUREE">
                        <i class="fas fa-check-circle"></i> Clôturées
                    </button>
                    <button class="filter-btn" data-filter="BLOQUEE">
                        <i class="fas fa-ban"></i> Bloquées
                    </button>
                </div>
                <select id="statusSelect" class="filter-select">
                    <option value="">Filtrer par statut</option>
                    <option value="EN_ATTENTE">En attente</option>
                    <option value="ACTIVE">Active</option>
                    <option value="CLOTUREE">Clôturée</option>
                    <option value="BLOQUEE">Bloquée</option>
                </select>
            </div>
        </div>

        {{-- ========== AUCTIONS GRID ========== --}}
        <div class="auctions-grid" id="auctionsGrid">
            @forelse($auctions as $annonce)
                @php
                    $photos = $annonce->produit->photos ?? [];
                    $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/400x300';
                    $currentPrice = $annonce->getMontantActuel();
                    $bidCount = $annonce->encheres()->count();
                    $status = $annonce->statut;
                    $statusClass = match ($status) {
                        'EN_ATTENTE' => 'warning',
                        'ACTIVE' => 'success',
                        'CLOTUREE' => 'secondary',
                        'BLOQUEE' => 'danger',
                        default => 'dark'
                    };
                    $statusLabel = match ($status) {
                        'EN_ATTENTE' => 'En attente',
                        'ACTIVE' => 'Active',
                        'CLOTUREE' => 'Clôturée',
                        'BLOQUEE' => 'Bloquée',
                        default => 'Inconnu'
                    };
                    $sellerName = $annonce->vendeur->client->nom ?? 'Vendeur';
                    $sellerRating = $annonce->vendeur->note_moyenne ?? 0;
                @endphp

                <div class="auction-card" data-status="{{ $status }}" data-title="{{ strtolower($annonce->titre) }}"
                    data-seller="{{ strtolower($sellerName) }}" data-product="{{ strtolower($annonce->produit->nom) }}">
                    <div class="auction-card-inner">
                        {{-- Image Overlay --}}
                        <div class="auction-image">
                            <img src="{{ $firstPhoto }}" alt="{{ $annonce->titre }}">
                            <div class="image-overlay"></div>
                            <span class="status-badge status-{{ $statusClass }}">
                                <span class="status-dot"></span>{{ $statusLabel }}
                            </span>
                            <span class="bid-count-badge">
                                <i class="fas fa-gavel"></i> {{ $bidCount }} enchère(s)
                            </span>
                        </div>

                        {{-- Card Body --}}
                        <div class="auction-body">
                            <div class="auction-title">{{ Str::limit($annonce->titre, 40) }}</div>
                            <div class="auction-product-name">{{ $annonce->produit->nom }}</div>

                            <div class="auction-info">
                                <div class="info-item seller-info">
                                    <div class="seller-avatar">
                                        {{ strtoupper(substr($sellerName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="seller-label">Vendeur</span>
                                        <span class="seller-name">{{ $sellerName }}</span>
                                    </div>
                                    <div class="seller-rating">
                                        <i class="fas fa-star"></i> {{ number_format($sellerRating, 1) }}
                                    </div>
                                </div>

                                <div class="info-item price-info">
                                    <span class="price-label">Prix actuel</span>
                                    <span class="price-value">{{ number_format($currentPrice, 2) }} TND</span>
                                </div>

                                <div class="info-item date-info">
                                    <span class="date-label">Fin le</span>
                                    <span
                                        class="date-value">{{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="auction-actions">
                                @if($status === 'EN_ATTENTE')
                                    <form method="POST" action="{{ route('admin.auctions.publish', $annonce) }}"
                                        class="action-form">
                                        @csrf
                                        <button type="submit" class="action-btn publish-btn">
                                            <i class="fas fa-check-circle"></i> Publier
                                        </button>
                                    </form>
                                @endif

                                @if($status === 'ACTIVE')
                                    <form method="POST" action="{{ route('admin.auctions.block', $annonce) }}" class="action-form"
                                        onsubmit="return confirm('Bloquer cette enchère ?')">
                                        @csrf
                                        <button type="submit" class="action-btn block-btn">
                                            <i class="fas fa-ban"></i> Bloquer
                                        </button>
                                    </form>
                                @endif

                                @if($status === 'BLOQUEE')
                                    <form method="POST" action="{{ route('admin.auctions.publish', $annonce) }}"
                                        class="action-form">
                                        @csrf
                                        <button type="submit" class="action-btn unblock-btn">
                                            <i class="fas fa-unlock-alt"></i> Débloquer
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('annonces.show', $annonce) }}" target="_blank" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <!--      <a href="{{ route('admin.auctions.show', $annonce) }}" class="action-btn details-btn">
                                                    <i class="fas fa-info-circle"></i> Détails
                                                </a> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h3>Aucune enchère trouvée</h3>
                    <p>Les enchères apparaîtront ici une fois créées.</p>
                </div>
            @endforelse
        </div>

        {{-- ========== PAGINATION ========== --}}
        @if($auctions->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Affichage de {{ $auctions->firstItem() }} à {{ $auctions->lastItem() }} sur {{ $auctions->total() }}
                    résultats
                </div>
                <nav class="custom-pagination">
                    @if ($auctions->onFirstPage())
                        <span class="page-item disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $auctions->previousPageUrl() }}" class="page-item"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach ($auctions->links()->elements[0] as $page => $url)
                        @if ($page == $auctions->currentPage())
                            <span class="page-item active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($auctions->hasMorePages())
                        <a href="{{ $auctions->nextPageUrl() }}" class="page-item"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-item disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </nav>
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
        /* ========== GLOBAL RESET & VARIABLES ========== */
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-hover: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #fc8181;
            --dark: #1a202c;
            --light: #f7fafc;
            --white: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.15);
            --radius-lg: 28px;
            --radius-md: 20px;
            --radius-sm: 14px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-auctions {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px 40px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* ========== HERO SECTION ========== */
        .auctions-hero {
            position: relative;
            background: var(--gradient-primary);
            border-radius: var(--radius-lg);
            padding: 3rem 2rem 0;
            margin-bottom: -40px;
            overflow: hidden;
            color: white;
            box-shadow: var(--shadow-lg);
            z-index: 2;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .hero-particles span {
            position: absolute;
            display: block;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        .hero-particles span:nth-child(1) {
            width: 8px;
            height: 8px;
            left: 10%;
            top: 20%;
            animation-duration: 12s;
        }

        .hero-particles span:nth-child(2) {
            width: 12px;
            height: 12px;
            left: 20%;
            top: 70%;
            animation-duration: 18s;
        }

        .hero-particles span:nth-child(3) {
            width: 6px;
            height: 6px;
            left: 30%;
            top: 40%;
            animation-duration: 15s;
        }

        .hero-particles span:nth-child(4) {
            width: 10px;
            height: 10px;
            left: 50%;
            top: 10%;
            animation-duration: 20s;
        }

        .hero-particles span:nth-child(5) {
            width: 7px;
            height: 7px;
            left: 60%;
            top: 80%;
            animation-duration: 13s;
        }

        .hero-particles span:nth-child(6) {
            width: 9px;
            height: 9px;
            left: 80%;
            top: 30%;
            animation-duration: 17s;
        }

        .hero-particles span:nth-child(7) {
            width: 5px;
            height: 5px;
            left: 90%;
            top: 60%;
            animation-duration: 16s;
        }

        .hero-particles span:nth-child(8) {
            width: 11px;
            height: 11px;
            left: 15%;
            top: 85%;
            animation-duration: 14s;
        }

        .hero-particles span:nth-child(9) {
            width: 13px;
            height: 13px;
            left: 70%;
            top: 45%;
            animation-duration: 19s;
        }

        .hero-particles span:nth-child(10) {
            width: 4px;
            height: 4px;
            left: 40%;
            top: 55%;
            animation-duration: 11s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.8;
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 150px;
            /* space for wave */
        }

        .hero-icon {
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            width: 90px;
            height: 90px;
            line-height: 90px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: pulse-icon 2s infinite;
        }

        @keyframes pulse-icon {
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
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-md);
            padding: 1.2rem 2rem;
            min-width: 120px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: var(--transition);
        }

        .hero-stat:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.3);
        }

        .hero-stat .stat-value {
            display: block;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .hero-stat .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 0.3rem;
            display: block;
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 2;
        }

        /* ========== FILTER BAR ========== */
        .filter-bar {
            position: relative;
            z-index: 3;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .filter-bar-inner {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .search-wrapper {
            position: relative;
            flex: 1 1 250px;
            min-width: 200px;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            background: white;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: transparent;
            border: 2px solid transparent;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-btn i {
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            background: #f0f2ff;
            color: var(--primary);
        }

        .filter-btn.active {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .filter-select {
            padding: 0.6rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-weight: 500;
            background: white;
            cursor: pointer;
            transition: var(--transition);
            display: none;
            /* hidden on desktop, shown on mobile */
        }

        /* ========== AUCTIONS GRID ========== */
        .auctions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .auction-card {
            position: relative;
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .auction-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .card-shine {
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 100%);
            transform: skewX(-25deg);
            transition: 0.5s;
            z-index: 2;
            pointer-events: none;
        }

        .auction-card:hover .card-shine {
            left: 125%;
        }

        .auction-card-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .auction-image {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .auction-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .auction-card:hover .auction-image img {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 60%);
            z-index: 1;
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            color: #2d3748;
            z-index: 3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-warning .status-dot {
            background: var(--warning);
            box-shadow: 0 0 6px var(--warning);
        }

        .status-success .status-dot {
            background: var(--success);
            box-shadow: 0 0 6px var(--success);
        }

        .status-secondary .status-dot {
            background: #a0aec0;
            box-shadow: 0 0 6px #a0aec0;
        }

        .status-danger .status-dot {
            background: var(--danger);
            box-shadow: 0 0 6px var(--danger);
        }

        .bid-count-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.3rem 0.9rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            z-index: 3;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .auction-body {
            padding: 1.2rem 1.5rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .auction-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
            line-height: 1.3;
        }

        .auction-product-name {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .auction-info {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.9rem;
        }

        .seller-info {
            justify-content: flex-start;
        }

        .seller-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .seller-label,
        .price-label,
        .date-label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .seller-name {
            font-weight: 600;
            color: var(--dark);
        }

        .seller-rating {
            margin-left: auto;
            background: #fefcbf;
            color: #b7791f;
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .price-info,
        .date-info {
            justify-content: space-between;
            background: #f7fafc;
            padding: 0.6rem 1rem;
            border-radius: var(--radius-sm);
        }

        .price-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .date-value {
            font-weight: 600;
            color: #4a5568;
        }

        .auction-actions {
            margin-top: auto;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            border-top: 1px solid #edf2f7;
            padding-top: 1rem;
        }

        .action-form {
            display: contents;
        }

        .action-btn {
            flex: 1 1 auto;
            padding: 0.55rem 0.8rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: var(--transition);
            text-decoration: none;
            background: #f7fafc;
            color: #4a5568;
            white-space: nowrap;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .publish-btn {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 10px rgba(72, 187, 120, 0.3);
        }

        .publish-btn:hover {
            box-shadow: 0 6px 15px rgba(72, 187, 120, 0.5);
        }

        .block-btn {
            background: linear-gradient(135deg, #fc8181, #f56565);
            color: white;
            box-shadow: 0 4px 10px rgba(245, 101, 101, 0.3);
        }

        .block-btn:hover {
            box-shadow: 0 6px 15px rgba(245, 101, 101, 0.5);
        }

        .unblock-btn {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
            box-shadow: 0 4px 10px rgba(237, 137, 54, 0.3);
        }

        .view-btn,
        .details-btn {
            background: #edf2f7;
            color: #4a5568;
        }

        .view-btn:hover,
        .details-btn:hover {
            background: #e2e8f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .empty-icon {
            font-size: 5rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #a0aec0;
        }

        /* ========== PAGINATION ========== */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2.5rem;
            padding: 1rem 1.5rem;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: #718096;
            font-size: 0.9rem;
        }

        .custom-pagination {
            display: flex;
            gap: 0.3rem;
        }

        .page-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
            color: #4a5568;
            text-decoration: none;
            background: #f7fafc;
        }

        .page-item:hover {
            background: var(--gradient-primary);
            color: white;
        }

        .page-item.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        }

        .page-item.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .filter-buttons {
                display: none;
            }

            .filter-select {
                display: block;
                flex: 1;
            }

            .auctions-grid {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-stats {
                gap: 1rem;
            }

            .hero-stat {
                padding: 0.8rem 1.2rem;
                min-width: 100px;
            }

            .auction-actions {
                flex-direction: column;
            }

            .action-btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-content {
                padding-bottom: 60px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .filter-bar-inner {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auction-card {
            animation: fadeInUp 0.5s ease both;
        }

        .auction-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .auction-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .auction-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .auction-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .auction-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .auction-card:nth-child(6) {
            animation-delay: 0.3s;
        }

        .auction-card:nth-child(7) {
            animation-delay: 0.35s;
        }

        .auction-card:nth-child(8) {
            animation-delay: 0.4s;
        }

        .auction-card:nth-child(9) {
            animation-delay: 0.45s;
        }

        .auction-card:nth-child(10) {
            animation-delay: 0.5s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Stats counter animation
            const totalAuctions = {{ $auctions->total() }};
            const activeCount = {{ $auctions->filter(fn($a) => $a->statut === 'ACTIVE')->count() }};
            const pendingCount = {{ $auctions->filter(fn($a) => $a->statut === 'EN_ATTENTE')->count() }};
            const totalBids = {{ $auctions->sum(fn($a) => $a->encheres()->count()) }};

            animateValue('totalAuctions', 0, totalAuctions, 1000);
            animateValue('activeAuctions', 0, activeCount, 1000);
            animateValue('pendingAuctions', 0, pendingCount, 1000);
            animateValue('totalBids', 0, totalBids, 1000);

            function animateValue(id, start, end, duration) {
                const obj = document.getElementById(id);
                if (!obj) return;
                const range = end - start;
                const startTime = performance.now();
                const step = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const current = Math.floor(progress * range + start);
                    obj.textContent = current.toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        obj.textContent = end.toLocaleString();
                    }
                };
                requestAnimationFrame(step);
            }

            // Search & Filter logic
            const searchInput = document.getElementById('searchInput');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const filterSelect = document.getElementById('statusSelect');
            const cards = document.querySelectorAll('.auction-card');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
                cards.forEach(card => {
                    const title = card.dataset.title || '';
                    const seller = card.dataset.seller || '';
                    const product = card.dataset.product || '';
                    const status = card.dataset.status;
                    const matchesSearch = title.includes(searchTerm) || seller.includes(searchTerm) || product.includes(searchTerm);
                    const matchesStatus = activeFilter === 'all' || status === activeFilter;
                    card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', applyFilters);

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filterSelect.value = this.dataset.filter === 'all' ? '' : this.dataset.filter;
                    applyFilters();
                });
            });

            filterSelect.addEventListener('change', function () {
                const val = this.value || 'all';
                filterBtns.forEach(b => b.classList.remove('active'));
                const activeBtn = Array.from(filterBtns).find(b => b.dataset.filter === val);
                if (activeBtn) activeBtn.classList.add('active');
                else if (val === 'all') filterBtns[0].classList.add('active');
                applyFilters();
            });
        });
    </script>
@endpush