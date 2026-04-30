{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/bids/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Offres reçues | BidMaster')
@section('page-title', 'Offres reçues')
@section('breadcrumb', 'Offres reçues')

@section('content')
    <div class="bids-galaxy-container">
        {{-- Cosmic Background Effects --}}
        <div class="nebula-bg">
            <div class="nebula-1"></div>
            <div class="nebula-2"></div>
            <div class="nebula-3"></div>
        </div>

        {{-- Hero Section with Stats --}}
        <div class="hero-bids">
            <div class="hero-bids-glow"></div>
            <div class="hero-bids-content">
                <div class="hero-bids-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <h1 class="hero-bids-title">Offres reçues</h1>
                <p class="hero-bids-subtitle">Suivez l'activité sur vos enchères et interagissez avec les enchérisseurs</p>
                <div class="hero-bids-stats">
                    <div class="hero-stat-card">
                        <span class="hero-stat-number" id="totalOffersCount">0</span>
                        <span class="hero-stat-label">Offres totales</span>
                    </div>
                    <div class="hero-stat-card">
                        <span class="hero-stat-number" id="highestBidValue">0</span>
                        <span class="hero-stat-label">Meilleure offre</span>
                    </div>
                    <div class="hero-stat-card">
                        <span class="hero-stat-number" id="activeAuctionsCount">0</span>
                        <span class="hero-stat-label">Enchères actives</span>
                    </div>
                    <div class="hero-stat-card">
                        <span class="hero-stat-number" id="winningBidsCount">0</span>
                        <span class="hero-stat-label">Offres en tête</span>
                    </div>
                </div>
            </div>
            <div class="hero-wave-separator">
                <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                    <path d="M0,32 C240,0 480,64 720,64 C960,64 1200,0 1440,32 L1440,120 L0,120 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="filter-cosmic-bar">
            <div class="filter-cosmic-inner">
                <div class="search-wrapper-cosmic">
                    <i class="fas fa-search search-icon-cosmic"></i>
                    <input type="text" id="searchBidInput" class="search-input-cosmic"
                        placeholder="Rechercher par enchère, enchérisseur...">
                    <button id="clearSearchCosmicBtn" class="clear-search-cosmic-btn" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="filter-chips-cosmic">
                    <button class="filter-chip-cosmic active" data-filter="all">Toutes les offres</button>
                    <button class="filter-chip-cosmic" data-filter="winning">En tête</button>
                    <button class="filter-chip-cosmic" data-filter="outbid">Dépassées</button>
                    <button class="filter-chip-cosmic" data-filter="closed-winning">Gagnantes</button>
                </div>
            </div>
        </div>

        {{-- Bids Cards Grid --}}
        <div class="bids-cosmic-grid" id="bidsGridContainer">
            @if($bids->count() > 0)
                @foreach($bids as $bid)
                    @php
                        $annonce = $bid->annonce;
                        $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                        $client = $bid->client;
                        $isWinning = $bid->isWinning ?? false;
                        $bidStatus = 'outbid';
                        $statusLabel = 'Dépassée';
                        $statusIcon = 'trending_down';
                        $statusColor = 'var(--warning)';

                        if ($annonce->statut == 'ACTIVE') {
                            if ($isWinning) {
                                $bidStatus = 'winning';
                                $statusLabel = 'En tête';
                                $statusIcon = 'emoji_events';
                                $statusColor = 'var(--success)';
                            } else {
                                $bidStatus = 'outbid';
                                $statusLabel = 'Dépassée';
                                $statusIcon = 'trending_down';
                                $statusColor = 'var(--warning)';
                            }
                        } elseif ($annonce->statut == 'CLOTUREE') {
                            if ($isWinning) {
                                $bidStatus = 'closed-winning';
                                $statusLabel = 'Gagnante';
                                $statusIcon = 'verified';
                                $statusColor = 'var(--success)';
                            } else {
                                $bidStatus = 'closed-lost';
                                $statusLabel = 'Perdante';
                                $statusIcon = 'cancel';
                                $statusColor = 'var(--danger)';
                            }
                        } else {
                            $bidStatus = 'inactive';
                            $statusLabel = $annonce->statut;
                            $statusIcon = 'block';
                            $statusColor = 'var(--gray)';
                        }
                    @endphp
                    <div class="bid-cosmic-card"
                        data-search="{{ strtolower($annonce->titre . ' ' . ($client ? $client->nom . ' ' . $client->prenom : '')) }}"
                        data-status="{{ $bidStatus }}">
                        <div class="bid-card-glow"></div>
                        <div class="bid-card-inner">
                            {{-- Product Image Section --}}
                            <div class="bid-card-image">
                                <img src="{{ $productImage }}" alt="{{ $annonce->titre }}">
                                <div class="bid-image-overlay"></div>
                                <div class="bid-amount-badge">
                                    <span class="bid-amount-value">{{ number_format($bid->montant, 0) }}</span>
                                    <span class="bid-amount-currency">TND</span>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="bid-card-content">
                                <div class="bid-card-header">
                                    <div class="bid-auction-title">
                                        <i class="fas fa-gavel"></i>
                                        <h3>{{ Str::limit($annonce->titre, 45) }}</h3>
                                    </div>
                                    <div class="bid-status-badge"
                                        style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border-left: 3px solid {{ $statusColor }};">
                                        <i class="material-symbols-rounded">{{ $statusIcon }}</i>
                                        <span>{{ $statusLabel }}</span>
                                    </div>
                                </div>

                                <div class="bid-product-meta">
                                    <span class="product-category">
                                        <i class="fas fa-tag"></i> {{ $annonce->produit->categorie->nom ?? 'Non catégorisé' }}
                                    </span>
                                    <span class="product-condition">
                                        <i class="fas fa-clipboard-list"></i>
                                        {{ Str::limit($annonce->produit->etat ?? 'État inconnu', 20) }}
                                    </span>
                                </div>

                                <div class="bidder-info-section">
                                    <div class="bidder-avatar">
                                        {{ $client ? strtoupper(substr($client->nom, 0, 1) . substr($client->prenom, 0, 1)) : '?' }}
                                    </div>
                                    <div class="bidder-details">
                                        <span
                                            class="bidder-name">{{ $client ? $client->nom . ' ' . $client->prenom : 'Compte supprimé' }}</span>
                                        <span
                                            class="bidder-email">{{ $client && $client->user ? $client->user->email : 'Email indisponible' }}</span>
                                    </div>
                                    <div class="bid-date">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ $bid->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>

                                <div class="bid-price-stats">
                                    <div class="price-stat">
                                        <span class="price-stat-label">Prix de départ</span>
                                        <span class="price-stat-value">{{ number_format($annonce->prix_depart, 0) }} TND</span>
                                    </div>
                                    <div class="price-stat-divider"></div>
                                    <div class="price-stat">
                                        <span class="price-stat-label">Offre actuelle</span>
                                        <span class="price-stat-value highlight">{{ number_format($bid->montant, 0) }} TND</span>
                                    </div>
                                    <div class="price-stat-divider"></div>
                                    <div class="price-stat">
                                        <span class="price-stat-label">Fin de l'enchère</span>
                                        <span
                                            class="price-stat-value">{{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <div class="bid-card-actions">
                                    <a href="{{ route('annonces.show', $annonce) }}" class="action-btn-cosmic view-btn">
                                        <i class="fas fa-eye"></i>
                                        <span>Voir l'enchère</span>
                                    </a>
                                    @if($isWinning && $annonce->statut == 'CLOTUREE' && $client && $client->user)
                                        <a href="mailto:{{ $client->user->email }}?subject=Félicitations ! Vous avez gagné l'enchère {{ $annonce->titre }}"
                                            class="action-btn-cosmic contact-btn">
                                            <i class="fas fa-envelope"></i>
                                            <span>Contacter</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-bids-cosmic">
                    <div class="empty-bids-animation">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h3>Aucune offre reçue</h3>
                    <p>Vous n'avez encore reçu aucune enchère sur vos annonces.</p>
                    <a href="{{ route('annonces.create') }}" class="create-auction-btn">
                        <i class="fas fa-plus-circle"></i> Créer une annonce
                    </a>
                </div>
            @endif
        </div>

        {{-- Pagination Cosmic --}}
        @if($bids->count() > 0 && $bids->hasPages())
            <div class="pagination-cosmic-wrapper">
                <div class="pagination-info-cosmic">
                    Affichage de <strong>{{ $bids->firstItem() }}</strong> à <strong>{{ $bids->lastItem() }}</strong> sur
                    <strong>{{ $bids->total() }}</strong> offres
                </div>
                <div class="pagination-cosmic">
                    {{-- Previous --}}
                    @if($bids->onFirstPage())
                        <span class="page-cosmic disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $bids->previousPageUrl() }}" class="page-cosmic"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $currentPage = $bids->currentPage();
                        $lastPage = $bids->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $bids->url(1) }}" class="page-cosmic">1</a>
                        @if($start > 2)
                            <span class="page-cosmic dots">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $currentPage)
                            <span class="page-cosmic active">{{ $i }}</span>
                        @else
                            <a href="{{ $bids->url($i) }}" class="page-cosmic">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)
                            <span class="page-cosmic dots">...</span>
                        @endif
                        <a href="{{ $bids->url($lastPage) }}" class="page-cosmic">{{ $lastPage }}</a>
                    @endif

                    {{-- Next --}}
                    @if($bids->hasMorePages())
                        <a href="{{ $bids->nextPageUrl() }}" class="page-cosmic"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-cosmic disabled"><i class="fas fa-chevron-right"></i></span>
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
        /* ============================================
                                           BIDS GALAXY - PREMIUM CUSTOM DESIGN
                                           Pure CSS, No External Libraries
                                        ============================================ */

        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-hover: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
            --gradient-gold: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --glass-bg: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(102, 126, 234, 0.15);
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 12px 32px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 24px 48px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 32px 64px rgba(0, 0, 0, 0.15);
            --radius-lg: 28px;
            --radius-md: 20px;
            --radius-sm: 14px;
            --transition: all 0.4s cubic-bezier(0.2, 0.95, 0.4, 1.05);
        }

        /* Global Container */
        .bids-galaxy-container {
            position: relative;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Nebula Background Effects */
        .nebula-bg {
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
            left: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.12) 0%, transparent 70%);
            filter: blur(80px);
            animation: nebulaFloat 25s infinite alternate;
        }

        .nebula-2 {
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.1) 0%, transparent 70%);
            filter: blur(100px);
            animation: nebulaFloat 30s infinite alternate-reverse;
        }

        .nebula-3 {
            position: absolute;
            top: 40%;
            left: 30%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
            filter: blur(120px);
            animation: nebulaFloat 20s infinite alternate;
        }

        @keyframes nebulaFloat {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }

            100% {
                transform: translate(5%, 5%) scale(1.2);
                opacity: 0.6;
            }
        }

        /* Hero Section */
        .hero-bids {
            position: relative;
            background: var(--gradient-primary);
            border-radius: 48px;
            margin-bottom: 2rem;
            padding: 3rem 2rem 5rem;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .hero-bids-glow {
            position: absolute;
            top: -30%;
            right: -10%;
            width: 70%;
            height: 150%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-bids-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding-bottom: 30px;
        }

        .hero-bids-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
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

        .hero-bids-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .hero-bids-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-bids-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            padding: 1.2rem 2rem;
            min-width: 140px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: var(--transition);
        }

        .hero-stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.25);
        }

        .hero-stat-number {
            display: block;
            font-size: 2rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 0.3rem;
            display: block;
        }

        .hero-wave-separator {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }

        .hero-wave-separator svg {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Filter Bar */
        .filter-cosmic-bar {
            margin-bottom: 2rem;
            position: relative;
            z-index: 5;
        }

        .filter-cosmic-inner {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 80px;
            padding: 0.6rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-md);
        }

        .search-wrapper-cosmic {
            position: relative;
            flex: 2;
            min-width: 240px;
        }

        .search-icon-cosmic {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1rem;
        }

        .search-input-cosmic {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .search-input-cosmic:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .clear-search-cosmic-btn {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .clear-search-cosmic-btn:hover {
            color: var(--danger);
        }

        .filter-chips-cosmic {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .filter-chip-cosmic {
            padding: 0.55rem 1.6rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.85rem;
            background: transparent;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: var(--transition);
            color: var(--dark);
        }

        .filter-chip-cosmic:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .filter-chip-cosmic.active {
            background: var(--gradient-primary);
            border-color: transparent;
            color: white;
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.4);
        }

        /* Bids Grid */
        .bids-cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        /* Bid Card */
        .bid-cosmic-card {
            position: relative;
            border-radius: var(--radius-lg);
            transition: var(--transition);
            animation: fadeInUp 0.5s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bid-cosmic-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .bid-cosmic-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .bid-cosmic-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .bid-cosmic-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .bid-cosmic-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .bid-cosmic-card:nth-child(6) {
            animation-delay: 0.3s;
        }

        .bid-cosmic-card:hover {
            transform: translateY(-8px);
        }

        .bid-card-glow {
            position: absolute;
            inset: -2px;
            background: var(--gradient-primary);
            border-radius: calc(var(--radius-lg) + 2px);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }

        .bid-cosmic-card:hover .bid-card-glow {
            opacity: 0.4;
        }

        .bid-card-inner {
            position: relative;
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            z-index: 1;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .bid-cosmic-card:hover .bid-card-inner {
            box-shadow: var(--shadow-lg);
        }

        /* Card Image Section */
        .bid-card-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .bid-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .bid-cosmic-card:hover .bid-card-image img {
            transform: scale(1.05);
        }

        .bid-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, transparent 60%);
        }

        .bid-amount-badge {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            display: flex;
            align-items: baseline;
            gap: 4px;
            z-index: 2;
        }

        .bid-amount-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--gradient-gold);
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .bid-amount-currency {
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }

        /* Card Content */
        .bid-card-content {
            padding: 1.5rem;
        }

        .bid-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .bid-auction-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .bid-auction-title i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .bid-auction-title h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .bid-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .bid-status-badge i {
            font-size: 1rem;
        }

        /* Product Meta */
        .bid-product-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.2rem;
            font-size: 0.75rem;
            color: var(--gray);
        }

        .bid-product-meta i {
            margin-right: 0.3rem;
        }

        /* Bidder Info */
        .bidder-info-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--light);
            padding: 0.8rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }

        .bidder-avatar {
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .bidder-details {
            flex: 1;
        }

        .bidder-name {
            font-weight: 700;
            color: var(--dark);
            display: block;
            font-size: 0.9rem;
        }

        .bidder-email {
            font-size: 0.7rem;
            color: var(--gray);
        }

        .bid-date {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            color: var(--gray);
        }

        /* Price Stats */
        .bid-price-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 0.8rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }

        .price-stat {
            text-align: center;
        }

        .price-stat-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--gray);
            display: block;
            margin-bottom: 0.2rem;
        }

        .price-stat-value {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .price-stat-value.highlight {
            color: var(--primary);
            font-size: 1rem;
        }

        .price-stat-divider {
            width: 1px;
            height: 25px;
            background: #e2e8f0;
        }

        /* Card Actions */
        .bid-card-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .action-btn-cosmic {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.7rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: var(--transition);
            background: #f1f5f9;
            color: var(--dark);
            border: none;
            cursor: pointer;
        }

        .action-btn-cosmic.view-btn {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: var(--primary);
        }

        .action-btn-cosmic.view-btn:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.4);
        }

        .action-btn-cosmic.contact-btn {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.1));
            color: var(--success);
        }

        .action-btn-cosmic.contact-btn:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(16, 185, 129, 0.4);
        }

        /* Empty State */
        .empty-bids-cosmic {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border-radius: 56px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .empty-bids-animation {
            font-size: 5rem;
            color: var(--gray);
            margin-bottom: 1rem;
            animation: floatEmpty 3s ease-in-out infinite;
        }

        @keyframes floatEmpty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .empty-bids-cosmic h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .empty-bids-cosmic p {
            color: var(--gray);
            margin-bottom: 1.5rem;
        }

        .create-auction-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 2rem;
            background: var(--gradient-primary);
            color: white;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .create-auction-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.5);
            color: white;
        }

        /* Pagination */
        .pagination-cosmic-wrapper {
            margin-top: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: white;
            padding: 1rem 2rem;
            border-radius: 60px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--glass-border);
        }

        .pagination-info-cosmic {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .pagination-info-cosmic strong {
            color: var(--dark);
        }

        .pagination-cosmic {
            display: flex;
            gap: 0.5rem;
        }

        .page-cosmic {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            border-radius: 30px;
            background: #f1f5f9;
            color: var(--dark);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .page-cosmic:hover:not(.disabled):not(.active) {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
        }

        .page-cosmic.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        }

        .page-cosmic.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-cosmic.dots {
            background: transparent;
            cursor: default;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .bids-galaxy-container {
                padding: 0 1rem 2rem;
            }

            .hero-bids-title {
                font-size: 2.2rem;
            }

            .hero-bids-stats {
                gap: 1rem;
            }

            .hero-stat-card {
                min-width: 100px;
                padding: 0.8rem 1rem;
            }

            .hero-stat-number {
                font-size: 1.5rem;
            }

            .filter-cosmic-inner {
                flex-direction: column;
                border-radius: 40px;
                padding: 1rem;
            }

            .search-wrapper-cosmic {
                width: 100%;
            }

            .filter-chips-cosmic {
                justify-content: center;
            }

            .bids-cosmic-grid {
                grid-template-columns: 1fr;
            }

            .bidder-info-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .bid-price-stats {
                flex-direction: column;
                gap: 0.8rem;
            }

            .price-stat-divider {
                display: none;
            }

            .pagination-cosmic-wrapper {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Animate stats counters
            function animateCounter(elementId, targetValue) {
                const element = document.getElementById(elementId);
                if (!element) return;
                let current = 0;
                const duration = 1000;
                const stepTime = 20;
                const steps = duration / stepTime;
                const increment = targetValue / steps;
                let counter = 0;
                const timer = setInterval(() => {
                    counter++;
                    current = Math.min(Math.floor(increment * counter), targetValue);
                    element.textContent = current.toLocaleString();
                    if (counter >= steps) {
                        element.textContent = targetValue.toLocaleString();
                        clearInterval(timer);
                    }
                }, stepTime);
            }

            // Extract stats from bids collection
            const bidsData = @json($bids->items());
            const totalOffers = {{ $bids->total() }};
            const highestBid = bidsData.reduce((max, bid) => Math.max(max, parseFloat(bid.montant) || 0), 0);
            const activeAuctions = bidsData.filter(bid => bid.annonce?.statut === 'ACTIVE').length;
            const winningBids = bidsData.filter(bid => bid.isWinning && bid.annonce?.statut === 'ACTIVE').length;

            animateCounter('totalOffersCount', totalOffers);
            animateCounter('highestBidValue', highestBid);
            animateCounter('activeAuctionsCount', activeAuctions);
            animateCounter('winningBidsCount', winningBids);

            // Search and filter functionality
            const searchInput = document.getElementById('searchBidInput');
            const clearBtn = document.getElementById('clearSearchCosmicBtn');
            const filterChips = document.querySelectorAll('.filter-chip-cosmic');
            const bidCards = document.querySelectorAll('.bid-cosmic-card');

            let currentFilter = 'all';

            function filterCards() {
                const searchTerm = searchInput.value.toLowerCase();
                bidCards.forEach(card => {
                    const searchData = card.dataset.search || '';
                    const cardStatus = card.dataset.status || '';
                    const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
                    const matchesFilter = currentFilter === 'all' || cardStatus === currentFilter;
                    if (matchesSearch && matchesFilter) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
                if (clearBtn) {
                    clearBtn.style.display = searchInput.value.length > 0 ? 'flex' : 'none';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterCards);
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        filterCards();
                    });
                }
            }

            filterChips.forEach(chip => {
                chip.addEventListener('click', () => {
                    filterChips.forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    currentFilter = chip.dataset.filter;
                    filterCards();
                });
            });
        })();
    </script>
@endpush