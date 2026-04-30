{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/won-auctions.blade.php --}}
@extends('layouts.app')

@section('title', 'Victoires Épiques | BidMaster')
@section('page-title', 'Trophées')
@section('breadcrumb', 'Enchères gagnées')

@section('content')
    <div class="victory-universe">
        {{-- Cosmic background animated particles --}}
        <div class="cosmic-dust"></div>
        <div class="nebula-drift nebula-1"></div>
        <div class="nebula-drift nebula-2"></div>

        {{-- ========== HERO VICTORY SECTION ========== --}}
        <div class="victory-hero">
            <div class="hero-glow-orb"></div>
            <div class="victory-content">
                <div class="trophy-levitation">
                    <div class="trophy-icon">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30 20L50 8L70 20L68 45L50 92L32 45L30 20Z" fill="url(#trophyGrad)" stroke="#FFD966"
                                stroke-width="1.5" />
                            <path d="M38 30L50 25L62 30L58 48L50 70L42 48L38 30Z" fill="#fff" fill-opacity="0.2" />
                            <circle cx="50" cy="22" r="4" fill="#FFD966" />
                            <defs>
                                <linearGradient id="trophyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FFD966" />
                                    <stop offset="100%" stop-color="#FFB347" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
                <h1 class="victory-title">Trésors <span class="glow-text">conquis</span></h1>
                <p class="victory-subtitle">Chaque victoire raconte une histoire d'audace et de stratégie. Voici votre hall
                    of fame.</p>
                @if($wonAuctions->count() > 0)
                        <div class="hero-stats-compact">
                            <div class="compact-stat">
                                <span class="stat-digit" id="victoryCount">{{ $wonAuctions->count() }}</span>
                                <span class="stat-caption">Trophées</span>
                            </div>
                            <div class="compact-stat">
                                <span class="stat-digit"
                                    id="totalSavings">{{ number_format($wonAuctions->sum(function ($m) {
                    return $m->annonce->prix_depart - $m->montant; }), 0) }}</span>
                                <span class="stat-caption">TND économisés</span>
                            </div>
                            <div class="compact-stat">
                                <span class="stat-digit"
                                    id="bestBid">{{ number_format($wonAuctions->max('montant') ?? 0, 0) }}</span>
                                <span class="stat-caption">Meilleure offre</span>
                            </div>
                        </div>
                @endif
            </div>
            <div class="hero-wave-separator">
                <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                    <path d="M0,32 C240,0 480,64 720,64 C960,64 1200,0 1440,32 L1440,80 L0,80 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        @if($wonAuctions->count() > 0)
            {{-- ========== STATS PANEL GLASS ========== --}}
            <div class="glass-stats-panel">
                <div class="stat-item stat-premium">
                    <span class="stat-icon">🏆</span>
                    <div><span class="stat-num" id="statTotalWon">{{ $wonAuctions->count() }}</span><span
                            class="stat-name">Enchères gagnées</span></div>
                </div>
                <div class="stat-item stat-premium">
                    <span class="stat-icon">💰</span>
                    <div><span class="stat-num"
                            id="statTotalSpent">{{ number_format($wonAuctions->sum('montant'), 0) }}</span><span
                            class="stat-name">MAD investis</span></div>
                </div>
                <div class="stat-item stat-premium">
                    <span class="stat-icon">⚡</span>
                    <div><span class="stat-num"
                            id="statAvgBid">{{ number_format($wonAuctions->avg('montant') ?? 0, 0) }}</span><span
                            class="stat-name">Offre moyenne</span></div>
                </div>
                <div class="stat-item stat-premium">
                    <span class="stat-icon">🎯</span>
                    <div><span class="stat-num"
                            id="statSavedTotal">{{ number_format($wonAuctions->sum(function ($m) {
                return $m->annonce->prix_depart - $m->montant; }), 0) }}</span><span
                            class="stat-name">MAD économisés</span></div>
                </div>
            </div>

            {{-- ========== SEARCH & FILTER ORBIT ========== --}}
            <div class="search-orbit-bar">
                <div class="search-field-cosmic">
                    <i class="fas fa-search search-ic"></i>
                    <input type="text" id="victorySearch" placeholder="Filtrer vos victoires (titre, catégorie, vendeur...)"
                        class="cosmic-input">
                    <button id="clearVictorySearch" class="clear-search-btn-hidden" style="display: none;"><i
                            class="fas fa-times-circle"></i></button>
                </div>
                <div class="pill-filters">
                    <button class="filter-pill active" data-filter="all">Tous</button>
                    <button class="filter-pill" data-filter="high-saving">Économies ++</button>
                    <button class="filter-pill" data-filter="recent">Récentes</button>
                </div>
            </div>

            {{-- ========== VICTORY GRID — CARTES LÉGENDAIRES ========== --}}
            <div class="victory-grid" id="victoryGrid">
                @foreach($wonAuctions as $mise)
                    @php
                        $annonce = $mise->annonce;
                        $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                        $firstPhoto = $images[0] ?? 'https://via.placeholder.com/500x350/2d3748/ffffff?text=Victory';
                        $savedAmount = $annonce->prix_depart - $mise->montant;
                        $savedPercent = $annonce->prix_depart > 0 ? round(($savedAmount / $annonce->prix_depart) * 100) : 0;
                        $sellerRating = $annonce->vendeur->note_moyenne ?? 0;
                        $catName = $annonce->produit->categorie->nom ?? 'Exclusivité';
                        $isRecent = $mise->created_at->diffInDays(now()) <= 7;
                    @endphp
                    <div class="legend-card" data-title="{{ strtolower($annonce->titre) }}" data-cat="{{ strtolower($catName) }}"
                        data-seller="{{ strtolower($annonce->vendeur->client->nom ?? '') }}" data-saving="{{ $savedPercent }}"
                        data-recent="{{ $isRecent ? '1' : '0' }}">
                        <div class="card-aura"></div>
                        <div class="card-inner-legend">
                            <div class="card-media-legend">
                                <div class="media-frame">
                                    <img src="{{ $firstPhoto }}" alt="{{ $annonce->titre }}">
                                    <div class="media-overlay-dream"></div>
                                </div>
                                <div class="victory-crown">
                                    <i class="fas fa-crown"></i>
                                </div>
                                @if($savedPercent > 0)
                                    <div class="savings-flare">
                                        -{{ $savedPercent }}% <span class="flare-spark">✨</span>
                                    </div>
                                @endif
                                <div class="bid-winning-badge">
                                    <i class="fas fa-check-circle"></i> VICTOIRE
                                </div>
                            </div>
                            <div class="card-content-legend">
                                <div class="card-header-legend">
                                    <h3 class="legend-title">{{ Str::limit($annonce->titre, 55) }}</h3>
                                    <div class="rating-stellar">
                                        <i class="fas fa-star"></i> {{ number_format($sellerRating, 1) }}
                                    </div>
                                </div>
                                <div class="legend-cat">{{ $catName }} •
                                    {{ Str::limit($annonce->produit->etat ?? 'État premium', 15) }}</div>
                                <div class="price-duel">
                                    <div class="price-original">
                                        <span class="label">Prix départ</span>
                                        <span class="value-old">{{ number_format($annonce->prix_depart, 0) }} MAD</span>
                                    </div>
                                    <div class="price-victory">
                                        <span class="label">Votre offre</span>
                                        <span class="value-win">{{ number_format($mise->montant, 0) }} MAD</span>
                                    </div>
                                </div>
                                @if($savedAmount > 0)
                                    <div class="eco-savings-banner">
                                        <i class="fas fa-chart-line"></i> Économie de {{ number_format($savedAmount, 0) }} MAD
                                    </div>
                                @endif
                                <div class="seller-contact-row">
                                    <div class="seller-avatar-mini">
                                        {{ strtoupper(substr($annonce->vendeur->client->nom ?? 'V', 0, 1)) }}
                                    </div>
                                    <span class="seller-name-mini">{{ $annonce->vendeur->client->nom ?? 'Vendeur' }}
                                        {{ $annonce->vendeur->client->prenom ?? '' }}</span>
                                    <div class="flex-spacer"></div>
                                    <span class="date-badge"><i class="far fa-calendar-alt"></i>
                                        {{ $mise->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="action-duo">
                                    <a href="{{ route('annonces.show', $annonce) }}" class="action-button view-button">
                                        <i class="fas fa-eye"></i> Explorer
                                    </a>
                                    <button
                                        onclick="contactSeller('{{ $annonce->vendeur->client->user->email ?? '' }}', '{{ addslashes($annonce->titre) }}')"
                                        class="action-button contact-button">
                                        <i class="fas fa-envelope"></i> Contacter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========== CUSTOM PAGINATION (PURE CSS) ========== --}}
            @if($wonAuctions->hasPages())
                <div class="cosmic-pagination-wrapper">
                    <div class="pagination-info">
                        Affichage de {{ $wonAuctions->firstItem() }} à {{ $wonAuctions->lastItem() }} sur
                        {{ $wonAuctions->total() }} victoires
                    </div>
                    <div class="pagination-luxury">
                        {{-- Previous --}}
                        @if($wonAuctions->onFirstPage())
                            <span class="page-luxury disabled"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $wonAuctions->previousPageUrl() }}" class="page-luxury"><i class="fas fa-chevron-left"></i></a>
                        @endif

                        @php
                            $current = $wonAuctions->currentPage();
                            $last = $wonAuctions->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp
                        @if($start > 1)
                            <a href="{{ $wonAuctions->url(1) }}" class="page-luxury">1</a>
                            @if($start > 2)<span class="page-luxury dots">...</span>@endif
                        @endif
                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $current)
                                <span class="page-luxury active">{{ $i }}</span>
                            @else
                                <a href="{{ $wonAuctions->url($i) }}" class="page-luxury">{{ $i }}</a>
                            @endif
                        @endfor
                        @if($end < $last)
                            @if($end < $last - 1)<span class="page-luxury dots">...</span>@endif
                                                <a href="{{ $wonAuctions->url($last) }}" class="page-luxury">{{ $last }}</a>
                        @endif

                                        @if($wonAuctions->hasMorePages())
                                            <a href="{{ $wonAuctions->nextPageUrl() }}" class="page-luxury"><i class="fas fa-chevron-right"></i></a>
                                        @else
                                            <span class="page-luxury disabled"><i class="fas fa-chevron-right"></i></span>
                                        @endif
                                    </div>
                                </div>
            @endif

                        {{-- ========== GUIDE / NEXT STEPS ========== --}}
                        <div class="next-steps-galaxy">
                            <div class="steps-header">
                                <i class="fas fa-sparkle"></i> Prochaines constellations
                            </div>
                            <div class="steps-grid">
                                <div class="step-card"><div class="step-number">01</div><h4>Contactez l'enchanteur</h4><p>Utilisez le bouton email pour finaliser les détails de livraison.</p></div>
                                <div class="step-card"><div class="step-number">02</div><h4>Organisez le paiement</h4><p>Mode sécurisé : virement ou espèce à la remise.</p></div>
                                <div class="step-card"><div class="step-number">03</div><h4>Recevez & évaluez</h4><p>Laissez un avis étoilé pour honorer le vendeur.</p></div>
                            </div>
                        </div>
        @else
                {{-- ========== EMPTY STATE COSMIC ========== --}}
                <div class="empty-victory-orbit">
                    <div class="empty-planet">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2>Aucune victoire céleste</h2>
                    <p>Votre légende ne fait que commencer. Plongez dans les enchères actives et décrochez votre premier trophée.</p>
                    <a href="{{ route('auctions.active') }}" class="action-button view-button mt-4">✨ Explorer les enchères ✨</a>
                </div>
            @endif
        </div>
@endsection

@push('styles')
    <style>
    /* ==============================================
       VICTORY UNIVERSE - PURE CSS MASTERPIECE
       No external CSS libraries, 100% custom craft
    ================================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800;14..32,900&display=swap');

    :root {
        --primary-aurora: #667eea;
        --primary-deep: #764ba2;
        --gradient-cosmic: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-gold: linear-gradient(135deg, #FFD966, #FFB347);
        --success-glow: #10b981;
        --danger-ember: #ef4444;
        --dark-void: #0f172a;
        --glass-white: rgba(255, 255, 255, 0.92);
        --shadow-king: 0 25px 45px -12px rgba(0,0,0,0.25);
        --radius-epic: 2rem;
        --transition-magic: all 0.4s cubic-bezier(0.2, 0.95, 0.4, 1.1);
    }

    .victory-universe {
        position: relative;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        overflow-x: hidden;
        background: #fbfdff;
    }

    /* particle background */
    .cosmic-dust {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: radial-gradient(circle at 20% 40%, rgba(102,126,234,0.05) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
    }
    .nebula-drift {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.25;
        pointer-events: none;
        z-index: 0;
    }
    .nebula-1 { width: 60vw; height: 60vw; background: radial-gradient(circle, #667eea, transparent); top: -20vh; left: -10vw; animation: floatNebula 28s infinite alternate; }
    .nebula-2 { width: 50vw; height: 50vw; background: radial-gradient(circle, #764ba2, transparent); bottom: -10vh; right: -10vw; animation: floatNebula2 35s infinite alternate; }
    @keyframes floatNebula { 0%{ transform: translate(0,0) scale(1); opacity: 0.2; } 100%{ transform: translate(5%, 5%) scale(1.2); opacity: 0.4; } }
    @keyframes floatNebula2 { 0%{ transform: translate(0,0) scale(1); opacity: 0.15; } 100%{ transform: translate(-5%, 5%) scale(1.15); opacity: 0.35; } }

    /* HERO SECTION */
    .victory-hero {
        position: relative;
        background: var(--gradient-cosmic);
        border-radius: 3rem;
        padding: 3rem 2rem 5rem;
        margin-bottom: 2.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-king);
        z-index: 2;
    }
    .hero-glow-orb {
        position: absolute;
        width: 80%;
        height: 80%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        top: -20%;
        right: -20%;
        border-radius: 50%;
        pointer-events: none;
    }
    .victory-content {
        position: relative;
        text-align: center;
        z-index: 2;
    }
    .trophy-levitation {
        animation: floatTrophy 3s ease-in-out infinite;
        margin-bottom: 1rem;
    }
    .trophy-icon svg {
        width: 80px;
        height: 80px;
        filter: drop-shadow(0 8px 20px rgba(0,0,0,0.2));
    }
    @keyframes floatTrophy {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }
    .victory-title {
        font-size: 3.2rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }
    .glow-text {
        background: linear-gradient(135deg, #FFE5B4, #FFD966);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .victory-subtitle {
        color: rgba(255,255,255,0.9);
        max-width: 500px;
        margin: 0 auto 1.5rem;
        font-weight: 400;
    }
    .hero-stats-compact {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    .compact-stat {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border-radius: 60px;
        padding: 0.5rem 1.5rem;
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }
    .stat-digit {
        font-size: 2rem;
        font-weight: 800;
        color: #FFD966;
    }
    .stat-caption {
        font-weight: 500;
        color: white;
    }
    .hero-wave-separator {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        line-height: 0;
    }
    .hero-wave-separator svg {
        width: 100%;
        height: auto;
    }
    /* Glass stats panel */
    .glass-stats-panel {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(12px);
        border-radius: 3rem;
        padding: 1.2rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid rgba(102,126,234,0.2);
        z-index: 3;
        position: relative;
    }
    .stat-premium {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        font-size: 2rem;
    }
    .stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        background: var(--gradient-cosmic);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: block;
        line-height: 1;
    }
    .stat-name {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #4b5563;
        letter-spacing: 0.5px;
    }
    /* search orbit */
    .search-orbit-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1.2rem;
        margin-bottom: 2.5rem;
    }
    .search-field-cosmic {
        position: relative;
        flex: 2;
        min-width: 260px;
    }
    .search-ic {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .cosmic-input {
        width: 100%;
        padding: 0.9rem 1rem 0.9rem 2.8rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 80px;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .cosmic-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.2);
        outline: none;
    }
    .clear-search-btn-hidden {
        position: absolute;
        right: 1rem;
        top: 50%;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        transform: translateY(-50%);
    }
    .pill-filters {
        display: flex;
        gap: 0.8rem;
    }
    .filter-pill {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1.4rem;
        border-radius: 60px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }
    .filter-pill.active {
        background: var(--gradient-cosmic);
        border-color: transparent;
        color: white;
        box-shadow: 0 5px 12px rgba(102,126,234,0.3);
    }
    /* Victory Grid */
    .victory-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    .legend-card {
        position: relative;
        transition: var(--transition-magic);
        border-radius: 2rem;
    }
    .card-aura {
        position: absolute;
        inset: -2px;
        background: var(--gradient-cosmic);
        border-radius: 2rem;
        opacity: 0;
        transition: 0.4s;
        z-index: 0;
    }
    .legend-card:hover .card-aura {
        opacity: 0.5;
    }
    .card-inner-legend {
        position: relative;
        background: white;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 12px 30px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s;
        z-index: 2;
    }
    .legend-card:hover .card-inner-legend {
        transform: translateY(-6px);
    }
    .card-media-legend {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .media-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s;
    }
    .legend-card:hover .media-frame img {
        transform: scale(1.04);
    }
    .media-overlay-dream {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
    }
    .victory-crown {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(6px);
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        color: #FFD966;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .savings-flare {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        background: #10b981dd;
        backdrop-filter: blur(4px);
        padding: 0.3rem 1rem;
        border-radius: 40px;
        font-weight: 800;
        font-size: 0.8rem;
        color: white;
        display: flex;
        gap: 0.2rem;
    }
    .bid-winning-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--gradient-cosmic);
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.7rem;
        color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .card-content-legend {
        padding: 1.5rem;
    }
    .card-header-legend {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.5rem;
    }
    .legend-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
    }
    .rating-stellar {
        background: #fef3c7;
        padding: 0.2rem 0.6rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: bold;
        color: #b45309;
    }
    .legend-cat {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        border-bottom: 1px dashed #e2e8f0;
        display: inline-block;
    }
    .price-duel {
        display: flex;
        justify-content: space-between;
        background: #f8fafc;
        border-radius: 1.2rem;
        padding: 0.8rem 1rem;
        margin: 1rem 0;
    }
    .price-original .label, .price-victory .label {
        font-size: 0.65rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .value-old {
        text-decoration: line-through;
        color: #475569;
    }
    .value-win {
        font-weight: 800;
        font-size: 1.2rem;
        background: var(--gradient-cosmic);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .eco-savings-banner {
        background: #e6f7ec;
        padding: 0.5rem;
        border-radius: 50px;
        text-align: center;
        font-size: 0.7rem;
        font-weight: bold;
        color: #2c7a4b;
        margin-bottom: 1rem;
    }
    .seller-contact-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 1rem 0;
    }
    .seller-avatar-mini {
        width: 32px;
        height: 32px;
        background: var(--gradient-cosmic);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
    .seller-name-mini {
        font-weight: 600;
        color: #1e293b;
    }
    .flex-spacer {
        flex: 1;
    }
    .date-badge {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    .action-duo {
        display: flex;
        gap: 0.8rem;
        margin-top: 0.5rem;
    }
    .action-button {
        flex: 1;
        text-align: center;
        padding: 0.7rem;
        border-radius: 60px;
        font-weight: 700;
        transition: 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .view-button {
        background: #f1f5f9;
        color: #334155;
    }
    .view-button:hover {
        background: var(--gradient-cosmic);
        color: white;
        transform: translateY(-2px);
    }
    .contact-button {
        background: #e0f2fe;
        color: #0284c7;
    }
    .contact-button:hover {
        background: #0ea5e9;
        color: white;
        transform: translateY(-2px);
    }
    /* Pagination */
    .cosmic-pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background: white;
        padding: 1rem 2rem;
        border-radius: 5rem;
        margin-top: 2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    }
    .pagination-luxury {
        display: flex;
        gap: 0.5rem;
    }
    .page-luxury {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }
    .page-luxury.active {
        background: var(--gradient-cosmic);
        color: white;
    }
    .page-luxury:hover:not(.disabled) {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    .disabled {
        opacity: 0.4;
        pointer-events: none;
    }
    /* Next steps */
    .next-steps-galaxy {
        margin-top: 3rem;
        background: white;
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    }
    .steps-header {
        font-size: 1.5rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 1.5rem;
        background: var(--gradient-cosmic);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    .step-card {
        background: #f9fafb;
        border-radius: 1.8rem;
        padding: 1.5rem;
        transition: 0.2s;
        text-align: center;
    }
    .step-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #cbd5e1;
    }
    .step-card h4 {
        margin: 0.5rem 0;
        font-weight: 700;
    }
    /* empty state */
    .empty-victory-orbit {
        text-align: center;
        padding: 5rem 2rem;
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(20px);
        border-radius: 3rem;
        margin-top: 2rem;
    }
    .empty-planet {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }
    @media (max-width: 780px) {
        .victory-universe { padding: 0 1rem 2rem; }
        .victory-title { font-size: 2.2rem; }
        .glass-stats-panel { flex-direction: column; }
        .victory-grid { grid-template-columns: 1fr; }
    }
    </style>
@endpush

@push('scripts')
    <script>
        (function(){
            // search & filter logic
            const searchInput = document.getElementById('victorySearch');
            const clearBtn = document.getElementById('clearVictorySearch');
            const filterPills = document.querySelectorAll('.filter-pill');
            let activeFilter = 'all';
            const cards = document.querySelectorAll('.legend-card');

            function filterCards() {
                const term = searchInput.value.toLowerCase();
                cards.forEach(card => {
                    const title = card.dataset.title || '';
                    const cat = card.dataset.cat || '';
                    const seller = card.dataset.seller || '';
                    const savingPercent = parseInt(card.dataset.saving) || 0;
                    const isRecent = card.dataset.recent === '1';
                    let matchesSearch = title.includes(term) || cat.includes(term) || seller.includes(term);
                    let matchesFilter = true;
                    if(activeFilter === 'high-saving') matchesFilter = savingPercent >= 15;
                    else if(activeFilter === 'recent') matchesFilter = isRecent;
                    else matchesFilter = true;
                    card.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
                });
                if(clearBtn) clearBtn.style.display = searchInput.value.length > 0 ? 'flex' : 'none';
            }
            if(searchInput) {
                searchInput.addEventListener('input', filterCards);
                if(clearBtn) clearBtn.addEventListener('click', () => { searchInput.value = ''; filterCards(); });
            }
            filterPills.forEach(pill => {
                pill.addEventListener('click', () => {
                    filterPills.forEach(p => p.classList.remove('active'));
                    pill.classList.add('active');
                    activeFilter = pill.dataset.filter;
                    filterCards();
                });
            });
            // animate stats numbers (simple)
            const statNumbers = document.querySelectorAll('.stat-num');
            statNumbers.forEach(el => {
                const finalVal = parseInt(el.innerText.replace(/[^0-9-]/g, '')) || 0;
                if(finalVal > 0) {
                    let current = 0;
                    const step = Math.ceil(finalVal / 40);
                    const timer = setInterval(() => {
                        current += step;
                        if(current >= finalVal) {
                            el.innerText = finalVal.toLocaleString();
                            clearInterval(timer);
                        } else { el.innerText = current.toLocaleString(); }
                    }, 20);
                }
            });
        })();

        function contactSeller(email, title) {
            if(email && confirm(`Contacter le vendeur pour finaliser "${title}" ?`)) {
                window.location.href = 'mailto:' + email + '?subject=Félicitations ! J\'ai gagné votre enchère : ' + encodeURIComponent(title) + '&body=Bonjour, j\'ai remporté votre enchère. Je souhaite finaliser la transaction.';
            } else if(!email) alert('Email du vendeur non disponible.');
        }

        // confetti on load (once) using canvas confetti CDN
        @if($wonAuctions->count() > 0 && session('show_confetti', true))
            const scriptConf = document.createElement('script');
            scriptConf.src = "https://cdn.jsdelivr.net/npm/canvas-confetti@1";
            scriptConf.onload = () => {
                canvasConfetti({ particleCount: 180, spread: 80, origin: { y: 0.6 }, colors: ['#667eea', '#764ba2', '#FFD966'] });
                setTimeout(() => canvasConfetti({ particleCount: 100, spread: 120, origin: { y: 0.5, x: 0.2 } }), 300);
                setTimeout(() => canvasConfetti({ particleCount: 100, spread: 120, origin: { y: 0.5, x: 0.8 } }), 500);
            };
            document.head.appendChild(scriptConf);
            @php session(['show_confetti' => false]); @endphp
        @endif
    </script>
@endpush