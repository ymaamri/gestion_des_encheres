{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Annonces | BidMaster')
@section('page-title', 'Mes Annonces')
@section('breadcrumb', 'Annonces')

@section('content')
    <div class="auctions-master-container">
        {{-- AURA BACKGROUND EFFECT --}}
        <div class="aura-bg">
            <div class="aura-1"></div>
            <div class="aura-2"></div>
        </div>

        {{-- HERO PARALLAX SECTION --}}
        <div class="hero-luxury">
            <div class="hero-glow"></div>
            <div class="hero-content-wrap">
                <div class="hero-tagline">
                    <span class="hero-chip">
                        <i class="fas fa-gavel"></i> Marketplace d'exception
                    </span>
                </div>
                <h1 class="hero-main-title">
                    Gérez vos <span class="gradient-text">enchères</span> avec élégance
                </h1>
                <p class="hero-description">
                    Centralisez, suivez et boostez vos ventes. Chaque annonce est une opportunité unique.
                </p>
                <div class="hero-cta-group">
                    <a href="{{ route('annonces.create') }}" class="hero-btn-primary">
                        <i class="fas fa-plus-circle"></i> Nouvelle annonce
                        <span class="btn-shine"></span>
                    </a>
                    <button class="hero-btn-secondary" id="scrollToAuctions">
                        <i class="fas fa-arrow-down"></i> Explorer mes lots
                    </button>
                </div>
            </div>
            <div class="hero-stats-wave">
                <div class="hero-stat-item" data-target="{{ $annonces->total() }}" data-counted="false">
                    <span class="hero-stat-number" id="statTotalAuctions">0</span>
                    <span class="hero-stat-label">annonces totales</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat-item" data-target="{{ $annonces->where('statut', 'ACTIVE')->count() }}"
                    data-counted="false">
                    <span class="hero-stat-number" id="statActiveAuctions">0</span>
                    <span class="hero-stat-label">actives</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat-item" data-target="{{ $annonces->sum(function ($a) {
        return $a->encheres()->count(); }) }}" data-counted="false">
                    <span class="hero-stat-number" id="statTotalBids">0</span>
                    <span class="hero-stat-label">offres reçues</span>
                </div>
            </div>
            <div class="hero-abstract-shape"></div>
        </div>

        {{-- FILTER + SEARCH BAR PREMIUM --}}
        <div class="filter-galactic" id="filterSection">
            <div class="filter-glass-panel">
                <div class="search-orbit">
                    <i class="fas fa-search search-orb-icon"></i>
                    <input type="text" id="searchAuctionInput" class="search-input-cosmic"
                        placeholder="Rechercher titre, produit ou vendeur...">
                    <button id="clearSearchBtnCosmic" class="clear-search-cosmic" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="filter-chips-group">
                    <button class="filter-chip active" data-filter="all">Tous</button>
                    <button class="filter-chip" data-filter="ACTIVE">Actives</button>
                    <button class="filter-chip" data-filter="EN_ATTENTE">En attente</button>
                    <button class="filter-chip" data-filter="CLOTUREE">Clôturées</button>
                    <button class="filter-chip" data-filter="BLOQUEE">Bloquées</button>
                </div>
            </div>
        </div>

        {{-- STATS CARDS GALAXY (premium stats) --}}
        <div class="premium-stats-grid">
            <div class="premium-stat-card">
                <div class="stat-card-icon gradient-1">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-label">Total ventes estimées</span>
                    <span
                        class="stat-card-value">{{ number_format($annonces->where('statut', 'CLOTUREE')->sum('prix_final'), 0) }}
                        TND</span>
                </div>
                <div class="stat-card-bg-shape"></div>
            </div>
            <div class="premium-stat-card">
                <div class="stat-card-icon gradient-2">
                    <i class="fas fa-star-of-life"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-label">Note moyenne vendeur</span>
                    <span
                        class="stat-card-value">{{ number_format(auth()->user()->client?->vendeur?->note_moyenne ?? 0, 1) }}
                        / 5</span>
                </div>
                <div class="stat-card-bg-shape"></div>
            </div>
            <div class="premium-stat-card">
                <div class="stat-card-icon gradient-3">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-label">Enchères gagnées</span>
                    <span class="stat-card-value">{{ auth()->user()->client?->encheresGagnees()->count() ?? 0 }}</span>
                </div>
                <div class="stat-card-bg-shape"></div>
            </div>
        </div>

        {{-- MAIN AUCTIONS GRID COSMIC --}}
        <div class="cosmic-grid-wrapper" id="auctionsGridWrap">
            <div class="cosmic-grid" id="auctionsCosmicGrid">
                @forelse($annonces as $annonce)
                    @php
                        $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                        $firstPhoto = $images[0] ?? 'https://via.placeholder.com/500x350/2d3748/ffffff?text=No+Image';
                        $currentPrice = $annonce->getMontantActuel();
                        $priceIncrease = $currentPrice - $annonce->prix_depart;
                        $increasePercent = $annonce->prix_depart > 0 ? round(($priceIncrease / $annonce->prix_depart) * 100) : 0;
                        $bidCount = $annonce->encheres()->count();
                        $timeLeft = \Carbon\Carbon::parse($annonce->date_fin);
                        $isActive = $annonce->statut === 'ACTIVE' && $timeLeft->isFuture();
                        $statusClass = match ($annonce->statut) {
                            'ACTIVE' => 'status-active',
                            'EN_ATTENTE' => 'status-pending',
                            'CLOTUREE' => 'status-closed',
                            'BLOQUEE' => 'status-blocked',
                            default => 'status-default'
                        };
                        $statusIcon = match ($annonce->statut) {
                            'ACTIVE' => '🔥',
                            'EN_ATTENTE' => '⏳',
                            'CLOTUREE' => '🏆',
                            'BLOQUEE' => '🚫',
                            default => '📦'
                        };
                    @endphp
                    <div class="cosmic-card" data-status="{{ $annonce->statut }}" data-title="{{ strtolower($annonce->titre) }}"
                        data-seller="{{ strtolower($annonce->vendeur->client->nom ?? '') }}" data-id="{{ $annonce->id }}">
                        <div class="card-cosmic-inner">
                            <div class="card-media">
                                <div class="card-img-container">
                                    <img src="{{ $firstPhoto }}" alt="{{ $annonce->titre }}" loading="lazy">
                                    <div class="img-overlay-glow"></div>
                                </div>
                                <div class="card-badge-status {{ $statusClass }}">
                                    <span class="status-emoji">{{ $statusIcon }}</span> {{ $annonce->statut }}
                                </div>
                                @if($increasePercent > 0 && $annonce->statut === 'ACTIVE')
                                    <div class="card-badge-hot">
                                        <i class="fas fa-chart-simple"></i> +{{ $increasePercent }}%
                                    </div>
                                @endif
                                <div class="card-bid-count">
                                    <i class="fas fa-gavel"></i> {{ $bidCount }} enchère(s)
                                </div>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">{{ Str::limit($annonce->titre, 48) }}</h3>
                                <div class="card-meta-row">
                                    <span class="meta-category">
                                        <i class="fas fa-tag"></i> {{ $annonce->produit->categorie->nom ?? 'Non catégorisé' }}
                                    </span>
                                    <span class="meta-cond">
                                        <i class="fas fa-clipboard-list"></i>
                                        {{ Str::limit($annonce->produit->etat ?? 'Bon état', 15) }}
                                    </span>
                                </div>
                                <div class="price-orbit">
                                    <div class="price-start">
                                        <span class="price-label">Départ</span>
                                        <span class="price-value-start">{{ number_format($annonce->prix_depart, 0) }} TND</span>
                                    </div>
                                    <div class="price-current">
                                        <span class="price-label">Actuel</span>
                                        <span class="price-value-current">{{ number_format($currentPrice, 0) }} TND</span>
                                    </div>
                                </div>
                                <div class="time-remaining" data-end="{{ $annonce->date_fin }}"
                                    data-status="{{ $annonce->statut }}">
                                    @if($annonce->statut === 'ACTIVE' && $timeLeft->isFuture())
                                        <i class="far fa-clock"></i>
                                        <span class="time-text">{{ $timeLeft->diffForHumans() }}</span>
                                    @elseif($annonce->statut === 'ACTIVE' && $timeLeft->isPast())
                                        <i class="fas fa-hourglass-end"></i> Terminée
                                    @elseif($annonce->statut === 'EN_ATTENTE')
                                        <i class="fas fa-hourglass-half"></i> En validation
                                    @elseif($annonce->statut === 'CLOTUREE')
                                        <i class="fas fa-check-double"></i> Finalisée
                                    @else
                                        <i class="fas fa-ban"></i> Suspendue
                                    @endif
                                </div>
                                <div class="card-actions-cosmic">
                                    <a href="{{ route('annonces.show', $annonce) }}" class="action-cosmic view">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <div class="dropdown-cosmic">
                                        <button class="action-cosmic more" data-dropdown="dropdown-{{ $annonce->id }}">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-cosmic-menu" id="dropdown-{{ $annonce->id }}">
                                            @if($annonce->encheres()->count() == 0 && !in_array($annonce->statut, ['CLOTUREE', 'BLOQUEE']))
                                                <a href="{{ route('annonces.edit', $annonce) }}" class="dropdown-cosmic-item">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <form method="POST" action="{{ route('annonces.destroy', $annonce) }}"
                                                    onsubmit="return confirm('Supprimer définitivement ?')" style="display:block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-cosmic-item text-danger">
                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                            @if($annonce->statut === 'ACTIVE')
                                                <button onclick="shareAuctionCosmic({{ $annonce->id }})"
                                                    class="dropdown-cosmic-item">
                                                    <i class="fas fa-share-alt"></i> Partager
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-edge-glow"></div>
                    </div>
                @empty
                    <div class="empty-cosmic-state">
                        <div class="empty-orb">
                            <i class="fas fa-store-slash"></i>
                        </div>
                        <h3>Aucune annonce céleste</h3>
                        <p>Votre espace vendeur est vierge. Lancez votre première enchère !</p>
                        <a href="{{ route('annonces.create') }}" class="hero-btn-primary mt-3">✨ Créer une annonce ✨</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- PAGINATION GALACTIC --}}
        @if($annonces->hasPages())
            <div class="pagination-galactic">
                <div class="pagination-info">
                    Affichage de {{ $annonces->firstItem() }} à {{ $annonces->lastItem() }} sur {{ $annonces->total() }}
                    résultats
                </div>
                <div class="pagination-links">
                    {{-- Previous --}}
                    @if($annonces->onFirstPage())
                        <span class="page-link-galactic disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $annonces->previousPageUrl() }}" class="page-link-galactic"><i
                                class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach($annonces->getUrlRange(max(1, $annonces->currentPage() - 2), min($annonces->lastPage(), $annonces->currentPage() + 2)) as $page => $url)
                        @if($page == $annonces->currentPage())
                            <span class="page-link-galactic active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link-galactic">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($annonces->hasMorePages())
                        <a href="{{ $annonces->nextPageUrl() }}" class="page-link-galactic"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-link-galactic disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

        {{-- COSMIC SHARE MODAL (PURE CSS/JS CUSTOM) --}}
        <div id="cosmicShareModal" class="cosmic-modal-overlay">
            <div class="cosmic-modal-container">
                <div class="cosmic-modal-header">
                    <h3><i class="fas fa-share-alt"></i> Rayonner l'annonce</h3>
                    <button class="cosmic-modal-close">&times;</button>
                </div>
                <div class="cosmic-modal-body">
                    <p>Partagez ce lien et attirez plus d'enchérisseurs 🚀</p>
                    <div class="cosmic-share-link-group">
                        <input type="text" id="cosmicShareLink" readonly>
                        <button id="cosmicCopyBtn"><i class="fas fa-copy"></i> Copier</button>
                    </div>
                    <div class="cosmic-social-row">
                        <a href="#" id="cosmicFbShare" target="_blank" class="social-icon fb"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" id="cosmicTwShare" target="_blank" class="social-icon tw"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" id="cosmicWaShare" target="_blank" class="social-icon wa"><i
                                class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* ========== RESET & GLOBAL COSMIC ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .auctions-master-container {
            position: relative;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* AURA BACKGROUND */
        .aura-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
            pointer-events: none;
        }

        .aura-1 {
            position: absolute;
            top: -20%;
            left: -10%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0) 70%);
            filter: blur(80px);
            animation: auroraMove 20s infinite alternate;
        }

        .aura-2 {
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.2) 0%, rgba(102, 126, 234, 0) 70%);
            filter: blur(100px);
            animation: auroraMove 25s infinite alternate-reverse;
        }

        @keyframes auroraMove {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.4;
            }

            100% {
                transform: translate(5%, 5%) scale(1.2);
                opacity: 0.8;
            }
        }

        /* HERO LUXURY */
        .hero-luxury {
            position: relative;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 56px;
            margin-bottom: 3rem;
            padding: 3rem 2.5rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(2px);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .hero-glow {
            position: absolute;
            top: -30%;
            right: -10%;
            width: 60%;
            height: 150%;
            background: linear-gradient(145deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.12));
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
        }

        .hero-content-wrap {
            position: relative;
            z-index: 2;
        }

        .hero-tagline {
            margin-bottom: 1rem;
        }

        .hero-chip {
            background: rgba(102, 126, 234, 0.12);
            padding: 0.4rem 1rem;
            border-radius: 60px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #667eea;
            letter-spacing: -0.2px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        .hero-main-title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            color: #1a202c;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #4a5568;
            max-width: 550px;
            margin-bottom: 2rem;
        }

        .hero-cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            align-items: center;
        }

        .hero-btn-primary {
            position: relative;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 48px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            overflow: hidden;
        }

        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.5);
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }

        .hero-btn-primary:hover .btn-shine {
            left: 100%;
        }

        .hero-btn-secondary {
            background: transparent;
            border: 1px solid #cbd5e0;
            padding: 0.85rem 1.8rem;
            border-radius: 48px;
            font-weight: 600;
            color: #2d3748;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .hero-btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .hero-stats-wave {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 2rem;
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 48px;
            padding: 1rem 2rem;
            width: fit-content;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .hero-stat-item {
            text-align: center;
        }

        .hero-stat-number {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #1a202c, #4a5568);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: #718096;
        }

        .hero-stat-divider {
            width: 1px;
            height: 30px;
            background: #e2e8f0;
        }

        .hero-abstract-shape {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, #667eea10, transparent);
            border-radius: 50%;
            pointer-events: none;
        }

        /* FILTER GALACTIC */
        .filter-galactic {
            margin-bottom: 2.5rem;
        }

        .filter-glass-panel {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 70px;
            padding: 0.8rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border: 1px solid rgba(102, 126, 234, 0.25);
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.05);
        }

        .search-orbit {
            position: relative;
            flex: 2;
            min-width: 240px;
        }

        .search-orb-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
        }

        .search-input-cosmic {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 48px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .search-input-cosmic:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .clear-search-cosmic {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
        }

        .filter-chips-group {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .filter-chip {
            background: transparent;
            padding: 0.5rem 1.4rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            cursor: pointer;
            color: #2d3748;
        }

        .filter-chip.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .filter-chip:hover:not(.active) {
            border-color: #667eea;
            color: #667eea;
        }

        /* PREMIUM STATS */
        .premium-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .premium-stat-card {
            background: white;
            border-radius: 32px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .premium-stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .gradient-1 {
            background: linear-gradient(145deg, #667eea, #764ba2);
        }

        .gradient-2 {
            background: linear-gradient(145deg, #2dce89, #2dcecc);
        }

        .gradient-3 {
            background: linear-gradient(145deg, #f5365c, #f56036);
        }

        .stat-card-content {
            flex: 1;
        }

        .stat-card-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #718096;
            display: block;
            margin-bottom: 0.3rem;
        }

        .stat-card-value {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
        }

        .stat-card-bg-shape {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 110px;
            height: 110px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05), transparent);
            border-radius: 50%;
            z-index: 0;
        }

        /* COSMIC GRID CARDS */
        .cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 2rem;
        }

        .cosmic-card {
            position: relative;
            border-radius: 32px;
            background: white;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            overflow: hidden;
            box-shadow: 0 12px 28px -10px rgba(0, 0, 0, 0.08);
        }

        .cosmic-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 40px -12px rgba(102, 126, 234, 0.35);
        }

        .card-cosmic-inner {
            position: relative;
            z-index: 2;
        }

        .card-media {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .card-img-container {
            width: 100%;
            height: 100%;
        }

        .card-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .cosmic-card:hover .card-img-container img {
            transform: scale(1.05);
        }

        .img-overlay-glow {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, transparent 60%);
            pointer-events: none;
        }

        .card-badge-status {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            padding: 0.35rem 1rem;
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

        .status-active {
            background: linear-gradient(135deg, #2dce89, #2dce89);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffb347, #ff8800);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
        }

        .status-blocked {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
        }

        .card-badge-hot {
            position: absolute;
            bottom: 16px;
            left: 16px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(6px);
            padding: 0.3rem 0.9rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: bold;
            color: #ffd966;
            z-index: 3;
        }

        .card-bid-count {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            padding: 0.3rem 0.9rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 3;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #1a202c;
        }

        .card-meta-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.2rem;
            font-size: 0.75rem;
            color: #718096;
            flex-wrap: wrap;
        }

        .price-orbit {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 0.8rem 1rem;
            border-radius: 24px;
            margin: 1rem 0;
        }

        .price-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #a0aec0;
            display: block;
        }

        .price-value-start {
            font-weight: 600;
            color: #2d3748;
        }

        .price-value-current {
            font-weight: 800;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .time-remaining {
            font-size: 0.8rem;
            color: #4a5568;
            margin: 0.8rem 0;
            background: #edf2f7;
            padding: 0.5rem 1rem;
            border-radius: 32px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .card-actions-cosmic {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .action-cosmic {
            flex: 1;
            text-align: center;
            padding: 0.6rem;
            border-radius: 48px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            background: #f1f5f9;
            color: #334155;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .action-cosmic.view {
            background: linear-gradient(135deg, #667eea10, #764ba210);
            color: #667eea;
        }

        .action-cosmic.view:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
        }

        .action-cosmic.more {
            background: #f1f5f9;
            width: 48px;
            flex: none;
        }

        .dropdown-cosmic {
            position: relative;
        }

        .dropdown-cosmic-menu {
            position: absolute;
            bottom: 110%;
            right: 0;
            background: white;
            min-width: 180px;
            border-radius: 20px;
            box-shadow: 0 20px 35px -12px black;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: 0.2s;
            z-index: 100;
        }

        .dropdown-cosmic-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(-4px);
        }

        .dropdown-cosmic-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1rem;
            border-radius: 14px;
            width: 100%;
            border: none;
            background: none;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: #2d3748;
        }

        .dropdown-cosmic-item:hover {
            background: #f0f2ff;
        }

        .text-danger {
            color: #e53e3e;
        }

        .card-edge-glow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 32px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) inset;
        }

        .cosmic-card:hover .card-edge-glow {
            opacity: 1;
        }

        /* EMPTY STATE */
        .empty-cosmic-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 48px;
            backdrop-filter: blur(12px);
        }

        .empty-orb {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        /* PAGINATION */
        .pagination-galactic {
            margin-top: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 60px;
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .pagination-links {
            display: flex;
            gap: 0.5rem;
        }

        .page-link-galactic {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border-radius: 30px;
            background: #f1f5f9;
            color: #334155;
            font-weight: 500;
            text-decoration: none;
            transition: 0.2s;
        }

        .page-link-galactic.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .page-link-galactic:hover:not(.disabled) {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* CUSTOM MODAL COSMIC */
        .cosmic-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .cosmic-modal-container {
            background: white;
            width: 90%;
            max-width: 500px;
            border-radius: 48px;
            overflow: hidden;
            animation: modalPop 0.3s;
        }

        @keyframes modalPop {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .cosmic-modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 1.2rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
        }

        .cosmic-modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            color: white;
            cursor: pointer;
        }

        .cosmic-modal-body {
            padding: 2rem;
            text-align: center;
        }

        .cosmic-share-link-group {
            display: flex;
            margin: 1rem 0;
            gap: 0.5rem;
        }

        .cosmic-share-link-group input {
            flex: 1;
            padding: 0.8rem;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
        }

        .cosmic-share-link-group button {
            background: #667eea;
            border: none;
            border-radius: 40px;
            padding: 0 1rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .cosmic-social-row {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .social-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #333;
            transition: 0.2s;
            font-size: 1.4rem;
            text-decoration: none;
        }

        .social-icon.fb:hover {
            background: #1877f2;
            color: white;
        }

        .social-icon.tw:hover {
            background: #1da1f2;
            color: white;
        }

        .social-icon.wa:hover {
            background: #25d366;
            color: white;
        }

        @media (max-width: 800px) {
            .auctions-master-container {
                padding: 0 1rem 2rem;
            }

            .hero-main-title {
                font-size: 2.2rem;
            }

            .filter-glass-panel {
                flex-direction: column;
                align-items: stretch;
                border-radius: 32px;
            }

            .cosmic-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Animated stats
            function animateNumber(element, target) {
                if (!element) return;
                let current = 0;
                const step = Math.ceil(target / 50);
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        element.innerText = target;
                        clearInterval(timer);
                    } else {
                        element.innerText = current;
                    }
                }, 20);
            }
            const totalStat = document.getElementById('statTotalAuctions');
            const activeStat = document.getElementById('statActiveAuctions');
            const bidsStat = document.getElementById('statTotalBids');
            if (totalStat) animateNumber(totalStat, {{ $annonces->total() }});
            if (activeStat) animateNumber(activeStat, {{ $annonces->where('statut', 'ACTIVE')->count() }});
            if (bidsStat) animateNumber(bidsStat, {{ $annonces->sum(function ($a) {
        return $a->encheres()->count(); }) }});

            // Search & filter
            const searchField = document.getElementById('searchAuctionInput');
            const clearBtn = document.getElementById('clearSearchBtnCosmic');
            const filterChips = document.querySelectorAll('.filter-chip');
            let currentStatus = 'all';
            const cards = document.querySelectorAll('.cosmic-card');

            function filterCards() {
                const term = searchField.value.toLowerCase();
                cards.forEach(card => {
                    const title = card.dataset.title || '';
                    const seller = card.dataset.seller || '';
                    const status = card.dataset.status;
                    const matchesSearch = title.includes(term) || seller.includes(term);
                    const matchesStatus = currentStatus === 'all' || status === currentStatus;
                    card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
                if (clearBtn) clearBtn.style.display = searchField.value.length > 0 ? 'flex' : 'none';
            }

            if (searchField) {
                searchField.addEventListener('input', filterCards);
                if (clearBtn) clearBtn.addEventListener('click', () => {
                    searchField.value = '';
                    filterCards();
                });
            }
            filterChips.forEach(chip => {
                chip.addEventListener('click', () => {
                    filterChips.forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    currentStatus = chip.dataset.filter;
                    filterCards();
                });
            });

            // Dropdown handling
            document.querySelectorAll('[data-dropdown]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const menuId = btn.dataset.dropdown;
                    const menu = document.getElementById(menuId);
                    if (menu) {
                        document.querySelectorAll('.dropdown-cosmic-menu').forEach(m => {
                            if (m !== menu) m.classList.remove('show');
                        });
                        menu.classList.toggle('show');
                    }
                });
            });
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-cosmic-menu').forEach(m => m.classList.remove('show'));
            });

            // Share modal logic
            const shareModal = document.getElementById('cosmicShareModal');
            const closeModalBtn = shareModal.querySelector('.cosmic-modal-close');
            const copyBtn = document.getElementById('cosmicCopyBtn');
            const shareLinkInput = document.getElementById('cosmicShareLink');
            window.shareAuctionCosmic = function (auctionId) {
                const url = window.location.origin + '/annonces/' + auctionId;
                shareLinkInput.value = url;
                document.getElementById('cosmicFbShare').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
                document.getElementById('cosmicTwShare').href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url) + '&text=Découvrez cette enchère exceptionnelle !';
                document.getElementById('cosmicWaShare').href = 'https://wa.me/?text=' + encodeURIComponent('Enchère incroyable : ' + url);
                shareModal.style.display = 'flex';
            };
            function closeShareModal() { shareModal.style.display = 'none'; }
            closeModalBtn.addEventListener('click', closeShareModal);
            shareModal.addEventListener('click', (e) => { if (e.target === shareModal) closeShareModal(); });
            copyBtn.addEventListener('click', () => {
                shareLinkInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copié!';
                setTimeout(() => copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copier', 2000);
            });

            // Scroll to auctions grid
            document.getElementById('scrollToAuctions')?.addEventListener('click', () => {
                document.getElementById('auctionsGridWrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        })();
    </script>
@endpush