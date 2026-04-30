{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auctions/active.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Actives | BidMaster')
@section('page-title', 'Enchères Actives')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="active-auctions-universe">
        <div class="cosmic-aura">
            <div class="aura-orb"></div>
            <div class="aura-orb"></div>
        </div>

        <!-- Hero section -->
        <div class="hero-galactic">
            <div class="hero-galactic-bg"></div>
            <div class="hero-content">
                <div class="hero-icon"><i class="fas fa-gavel"></i></div>
                <h1 class="hero-title">Enchères <span
                        style="background: linear-gradient(145deg, #fff, #f0e6ff); -webkit-background-clip: text; background-clip: text; color: transparent;">Cosmiques</span>
                </h1>
                <p class="hero-sub">Le frisson de l'enchère, l'excellence de l'instant</p>
                <div class="hero-stats">
                    <div class="stat-pill"><i class="fas fa-chart-line"></i> {{ $auctions->total() }} lots actifs</div>
                    <div class="stat-pill"><i class="fas fa-fire"></i> Tendance du moment</div>
                </div>
            </div>
        </div>

        <!-- FILTER STICKY FIXED : now sticks perfectly -->
        <div class="filter-nebula">
            <form method="GET" action="{{ route('auctions.active') }}" id="filterForm">
                <div class="filter-layout">
                    <div class="filter-group">
                        <label><i class="fas fa-tag"></i> Catégorie</label>
                        <select name="categorie">
                            <option value="">Toutes</option>
                            @foreach($categories ?? [] as $categorie)
                                <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-coins"></i> Prix min</label>
                        <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="0 TND">
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-coins"></i> Prix max</label>
                        <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Illimité">
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-clipboard-list"></i> État</label>
                        <select name="etat">
                            <option value="">Tous</option>
                            <option value="NEUF" {{ request('etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                            <option value="TRES_BON_ETAT" {{ request('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très bon
                            </option>
                            <option value="BON_ETAT" {{ request('etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon</option>
                            <option value="ACCEPTABLE" {{ request('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-apply"><i class="fas fa-filter"></i> Appliquer</button>
                        @if(request()->anyFilled(['categorie', 'prix_min', 'prix_max', 'etat']))
                            <a href="{{ route('auctions.active') }}" class="btn-reset"
                                style="margin-left: 8px; text-decoration:none;"><i class="fas fa-times"></i> Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Sorting & info -->
        <div class="sorting-orb">
            <div class="total-badge"><i class="fas fa-gem"></i> {{ $auctions->total() }} enchères exceptionnelles</div>
            <div>
                <select id="sortSelect" class="sort-select">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>✨ Les plus récentes</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>💰 Prix croissant
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>💰 Prix décroissant
                    </option>
                    <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>⏳ Fin imminente
                    </option>
                    <option value="most_bids" {{ request('sort') == 'most_bids' ? 'selected' : '' }}>🔥 Plus d'offres</option>
                </select>
            </div>
        </div>

        <!-- CARDS GRID -->
        <div class="auctions-cosmic-grid">
            @forelse($auctions as $annonce)
                @php
                    $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                    $firstPhoto = $images[0] ?? 'https://placehold.co/600x400/e2e8f0/667eea?text=No+Image';
                    $currentBid = $annonce->getMontantActuel();
                    $bidCount = $annonce->encheres()->count();
                    $timeLeft = \Carbon\Carbon::parse($annonce->date_fin);
                    $now = \Carbon\Carbon::now();
                    $isEndingSoon = $timeLeft->diffInHours($now) <= 24;
                    $isHot = $bidCount > 8;
                    $percentage = 0;
                    $start = strtotime($annonce->date_debut);
                    $end = strtotime($annonce->date_fin);
                    if ($end > $start)
                        $percentage = min(100, max(0, ((time() - $start) / ($end - $start)) * 100));
                    $rating = $annonce->vendeur->note_moyenne ?? 0;
                @endphp
                <div class="card-nebula" data-auction-id="{{ $annonce->id }}">
                    <div class="card-media">
                        <img src="{{ $firstPhoto }}" alt="{{ $annonce->titre }}">
                        <div class="media-overlay"></div>
                        <div class="badge-time"><i class="far fa-clock"></i>
                            {{ $isEndingSoon ? '🔥 Fin imminente' : $timeLeft->diffForHumans() }}</div>
                        @if($isHot)
                        <div class="badge-hot"><i class="fas fa-fire"></i> Tendance</div> @endif
                        <div class="bid-count-chip"><i class="fas fa-gavel"></i> {{ $bidCount }} offres</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ Str::limit($annonce->titre, 45) }}</h3>
                        <div class="product-meta">
                            <span><i class="fas fa-tag"></i> {{ $annonce->produit->categorie->nom ?? 'Général' }}</span>
                            <span><i class="fas fa-clipboard-list"></i> {{ $annonce->produit->etat ?? 'État' }}</span>
                        </div>
                        <div class="price-dual">
                            <div class="price-start">
                                <small>Départ</small>
                                <strong>{{ number_format($annonce->prix_depart, 0) }} TND</strong>
                            </div>
                            <div class="price-current">
                                <small>Actuel</small>
                                <strong>{{ number_format($currentBid, 0) }} TND</strong>
                            </div>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <div class="seller-rating">
                            <i class="fas fa-star"></i> {{ number_format($rating, 1) }}/5 &nbsp; • &nbsp; <i
                                class="fas fa-store"></i> {{ $annonce->vendeur->client->nom ?? 'Vendeur' }}
                        </div>
                        <div class="card-footer-actions">
                            <a href="{{ route('annonces.show', $annonce) }}" class="btn-bid"><i class="fas fa-gavel"></i> Placer
                                une offre</a>
                            <button class="btn-share"
                                onclick="openShareModal({{ $annonce->id }}, '{{ addslashes($annonce->titre) }}')"><i
                                    class="fas fa-share-alt"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-grace">
                    <i class="fas fa-meteor" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <h3 style="margin: 1rem 0;">Aucune enchère active</h3>
                    <p>Revenez plus tard, les super enchères arrivent bientôt.</p>
                    <a href="{{ route('auctions.active') }}" style="margin-top:1rem; display:inline-block;" class="btn-apply"><i
                            class="fas fa-sync-alt"></i> Actualiser</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($auctions->hasPages())
            <div class="pagination-star">
                {{ $auctions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- SHARE MODAL -->
    <div id="customShareModal" class="modal-custom">
        <div class="modal-card">
            <div class="modal-header-prem">
                <span><i class="fas fa-share-alt"></i> Rayonner l'enchère</span>
                <button id="closeModalBtn"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body-prem">
                <p style="margin-bottom: 1rem;">Partagez cette perle rare 🚀</p>
                <div class="share-link-group">
                    <input type="text" id="shareUrlInput" readonly>
                    <button id="copyLinkBtn"><i class="fas fa-copy"></i> Copier</button>
                </div>
                <div class="social-icons">
                    <a href="#" id="fbShareBtn" target="_blank" class="social-icon-circle"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" id="twShareBtn" target="_blank" class="social-icon-circle"><i
                            class="fab fa-twitter"></i></a>
                    <a href="#" id="waShareBtn" target="_blank" class="social-icon-circle"><i
                            class="fab fa-whatsapp"></i></a>
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
        /* ----------------------------------------------
                                RESET & GLOBAL LUXURY DESIGN (100% native CSS)
                            ----------------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-soft: #818cf8;
            --secondary: #764ba2;
            --gradient-prime: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-reverse: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --gradient-gold: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-soft: #1e293b;
            --gray-deep: #334155;
            --gray-mid: #64748b;
            --gray-light: #f1f5f9;
            --card-white: #ffffff;
            --shadow-sm: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 20px 35px -12px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --radius-xl: 32px;
            --radius-lg: 24px;
            --radius-md: 18px;
            --transition-butter: all 0.4s cubic-bezier(0.2, 0.95, 0.4, 1.05);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.02) 100%);
            background-attachment: fixed;
        }

        /* ========== PAGE WRAPPER ========== */
        .active-auctions-universe {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.5rem 2rem 4rem;
            position: relative;
            /* FIX: removed overflow-x: hidden which was breaking sticky */
        }

        /* ---------- ANIMATED AURA BACKGROUND ---------- */
        .cosmic-aura {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            pointer-events: none;
            overflow: hidden;
        }

        .aura-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.4;
            animation: floatAura 18s infinite alternate;
        }

        .aura-orb:nth-child(1) {
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3), transparent);
            top: -15%;
            left: -10%;
            animation-duration: 22s;
        }

        .aura-orb:nth-child(2) {
            width: 60vh;
            height: 60vh;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.25), transparent);
            bottom: -10%;
            right: -5%;
            animation-duration: 28s;
            animation-direction: alternate-reverse;
        }

        @keyframes floatAura {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }

            100% {
                transform: translate(5%, 8%) scale(1.2);
                opacity: 0.6;
            }
        }

        /* ========== HERO SECTION ========== */
        .hero-galactic {
            background: var(--gradient-prime);
            border-radius: var(--radius-xl);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .hero-galactic-bg {
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 200" opacity="0.08"><path fill="white" d="M0,96L48,90.7C96,85,192,75,288,80C384,85,480,107,576,117.3C672,128,768,128,864,117.3C960,107,1056,85,1152,80C1248,75,1344,85,1392,90.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/></svg>') repeat-x bottom;
            background-size: cover;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            padding: 2.8rem 2.5rem 2.2rem;
            text-align: center;
            color: white;
        }

        .hero-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 85px;
            height: 85px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            font-size: 2.6rem;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: gentlePulse 2.5s infinite;
        }

        @keyframes gentlePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.3);
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
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .hero-sub {
            font-size: 1.1rem;
            opacity: 0.92;
            max-width: 550px;
            margin: 0 auto 1.2rem;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .stat-pill {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 0.6rem 1.5rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .stat-pill i {
            margin-right: 8px;
        }

        /* ---------- STICKY FILTER NEBULA (FIXED) ---------- */
        .filter-nebula {
            position: block;
            z-index: 40;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border-radius: 70px;
            padding: 0.9rem 1.6rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .filter-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1 1 180px;
        }

        .filter-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-mid);
            margin-bottom: 6px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 40px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-weight: 500;
            transition: 0.2s;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .btn-apply {
            background: var(--gradient-prime);
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-butter);
            box-shadow: 0 5px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
            box-shadow: 0 12px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-reset {
            background: transparent;
            border: 1.5px solid #cbd5e1;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-reset:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* sorting bar */
        .sorting-orb {
            background: white;
            border-radius: 60px;
            padding: 0.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid #eef2ff;
        }

        .total-badge {
            font-weight: 600;
            background: #f0f4ff;
            padding: 0.4rem 1rem;
            border-radius: 40px;
            color: var(--primary);
        }

        .sort-select {
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            background: white;
            font-weight: 500;
            cursor: pointer;
        }

        /* ========== CARDS GRID ========== */
        .auctions-cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .card-nebula {
            background: var(--card-white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition-butter);
            box-shadow: var(--shadow-sm);
            position: relative;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .card-nebula:hover {
            transform: translateY(-12px);
            box-shadow: var(--shadow-xl);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .card-media {
            position: relative;
            height: 260px;
            overflow: hidden;
        }

        .card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .card-nebula:hover .card-media img {
            transform: scale(1.05);
        }

        .media-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
        }

        .badge-time {
            position: absolute;
            bottom: 18px;
            left: 18px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-hot {
            position: absolute;
            top: 18px;
            right: 18px;
            background: var(--gradient-gold);
            padding: 0.3rem 0.9rem;
            border-radius: 40px;
            font-weight: 800;
            font-size: 0.7rem;
            color: #2d2a1e;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .bid-count-chip {
            position: absolute;
            top: 18px;
            left: 18px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            padding: 0.3rem 0.9rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.7rem;
            color: white;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .product-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-mid);
            margin-bottom: 1rem;
        }

        .price-dual {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 0.8rem 1rem;
            border-radius: 20px;
            margin: 1rem 0;
        }

        .price-start small,
        .price-current small {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .price-start strong,
        .price-current strong {
            font-size: 1.2rem;
            font-weight: 800;
        }

        .price-current strong {
            background: var(--gradient-prime);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .progress-bar-custom {
            height: 8px;
            background: #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            margin: 0.7rem 0 1rem;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: var(--gradient-prime);
            border-radius: 20px;
            transition: width 0.45s ease;
        }

        .card-footer-actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .btn-bid {
            flex: 2;
            background: var(--gradient-prime);
            border: none;
            padding: 0.8rem;
            border-radius: 40px;
            color: white;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-bid:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.4);
        }

        .btn-share {
            background: #f1f5f9;
            border: none;
            width: 48px;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            color: #475569;
        }

        .btn-share:hover {
            background: #e2e8f0;
            transform: scale(1.02);
            color: var(--primary);
        }

        .seller-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            margin-top: 12px;
            color: #f59e0b;
        }

        /* pagination */
        .pagination-star {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-star .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
            flex-wrap: wrap;
        }

        .pagination-star .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            height: 44px;
            background: white;
            border-radius: 60px;
            font-weight: 600;
            color: var(--dark-soft);
            text-decoration: none;
            transition: 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .pagination-star .page-item.active .page-link {
            background: var(--gradient-prime);
            color: white;
            border: none;
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.4);
        }

        .pagination-star .page-item .page-link:hover:not(.active) {
            background: #f1f5f9;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* SHARE MODAL */
        .modal-custom {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(12px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-card {
            background: white;
            max-width: 480px;
            width: 90%;
            border-radius: 48px;
            overflow: hidden;
            animation: floatIn 0.3s;
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(30px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header-prem {
            background: var(--gradient-prime);
            padding: 1rem 1.8rem;
            color: white;
            display: flex;
            justify-content: space-between;
            font-weight: 700;
        }

        .modal-header-prem button {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
        }

        .modal-body-prem {
            padding: 2rem;
            text-align: center;
        }

        .share-link-group {
            display: flex;
            margin: 1rem 0;
            gap: 8px;
        }

        .share-link-group input {
            flex: 1;
            padding: 0.9rem;
            border-radius: 60px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            font-weight: 500;
        }

        .share-link-group button {
            background: var(--gradient-prime);
            border: none;
            padding: 0 1.5rem;
            border-radius: 60px;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.8rem;
        }

        .social-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            transition: 0.2s;
            color: #1e293b;
            text-decoration: none;
        }

        .social-icon-circle:hover {
            transform: translateY(-5px);
            background: var(--gradient-prime);
            color: white;
        }

        @media (max-width: 780px) {
            .active-auctions-universe {
                padding: 0.8rem 1rem 2rem;
            }

            .filter-layout {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-nebula {
                border-radius: 32px;
                top: 70px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .auctions-cosmic-grid {
                grid-template-columns: 1fr;
            }
        }

        .empty-grace {
            text-align: center;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border-radius: 56px;
            padding: 4rem;
            margin: 2rem 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Sorting redirect
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function () {
                    let currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', this.value);
                    window.location.href = currentUrl.toString();
                });
            }

            // AUTO REFRESH every 60 sec (only when visible)
            let refreshInterval;
            function startAutoRefresh() {
                refreshInterval = setInterval(() => {
                    if (!document.hidden) {
                        let url = new URL(window.location.href);
                        window.location.href = url.toString();
                    }
                }, 60000);
            }
            function stopAutoRefresh() { if (refreshInterval) clearInterval(refreshInterval); }
            startAutoRefresh();
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stopAutoRefresh();
                else startAutoRefresh();
            });

            // SHARE MODAL logic
            const modal = document.getElementById('customShareModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const shareUrlInput = document.getElementById('shareUrlInput');
            const copyBtn = document.getElementById('copyLinkBtn');
            const fbBtn = document.getElementById('fbShareBtn');
            const twBtn = document.getElementById('twShareBtn');
            const waBtn = document.getElementById('waShareBtn');
            let currentAuctionUrl = '';

            window.openShareModal = function (auctionId, title) {
                const url = window.location.origin + '/annonces/' + auctionId;
                currentAuctionUrl = url;
                shareUrlInput.value = url;
                const encoded = encodeURIComponent(url);
                const text = encodeURIComponent(`🔥 Découvre cette enchère sensationnelle: ${title} sur BidMaster !`);
                fbBtn.href = `https://www.facebook.com/sharer/sharer.php?u=${encoded}`;
                twBtn.href = `https://twitter.com/intent/tweet?text=${text}&url=${encoded}`;
                waBtn.href = `https://wa.me/?text=${text}%20${encoded}`;
                modal.style.display = 'flex';
            };
            function closeModal() { modal.style.display = 'none'; }
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            if (copyBtn) {
                copyBtn.addEventListener('click', () => {
                    shareUrlInput.select();
                    navigator.clipboard.writeText(shareUrlInput.value).then(() => {
                        copyBtn.innerHTML = '<i class="fas fa-check"></i> Copié !';
                        setTimeout(() => copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copier', 2000);
                    }).catch(() => alert("copie manuelle"));
                });
            }

            // progress fill animation
            const progressBars = document.querySelectorAll('.progress-fill');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const width = el.style.width;
                        if (width && width !== '0%') {
                            el.style.transition = 'width 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1)';
                        }
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.1 });
            progressBars.forEach(bar => observer.observe(bar));
        })();
    </script>
@endpush