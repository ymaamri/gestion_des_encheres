{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/my-bids.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Offres | BidMaster')
@section('page-title', 'Mes Offres')
@section('breadcrumb', 'Mes Offres')

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ----- RESET & GLOBAL (Overrides) ----- */
        .bids-master-universe {
            --primary-deep: #667eea;
            --secondary-cosmic: #764ba2;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-gold: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --gradient-winning: linear-gradient(135deg, #38b2ac 0%, #2c7a7a 100%);
            --gradient-outbid: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            --gradient-won: linear-gradient(135deg, #d946ef 0%, #a21caf 100%);
            --dark-slate: #0f172a;
            --soft-gray: #f1f5f9;
            --glass-white: rgba(255, 255, 255, 0.92);
            --shadow-3d: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            --radius-2xl: 2rem;
            --radius-xl: 1.25rem;
            --radius-md: 0.85rem;
            --transition-smooth: all 0.35s cubic-bezier(0.2, 0.95, 0.4, 1.05);
        }

        .bids-master-universe {
            padding: 0 2rem 4rem;
            max-width: 1600px;
            margin: 0 auto;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            position: relative;
            z-index: 2;
        }

        /* immersive animated background */
        .bids-master-universe::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(102, 126, 234, 0.06) 0%, rgba(118, 75, 162, 0.02) 60%);
            z-index: -2;
            pointer-events: none;
            animation: cosmicDrift 28s infinite alternate;
        }

        @keyframes cosmicDrift {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(2%, 3%) rotate(1deg);
            }
        }

        /* stats grid */
        .stats-nebula {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin-bottom: 2.5rem;
        }

        .stat-orb {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            padding: 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(102, 126, 234, 0.25);
            transition: var(--transition-smooth);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
        }

        .stat-orb:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 32px -12px rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.5);
        }

        .stat-info h4 {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            color: #4b5563;
            margin-bottom: 0.4rem;
        }

        .stat-number {
            font-size: 2.6rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
        }

        .stat-icon-bubble {
            width: 54px;
            height: 54px;
            background: var(--gradient-primary);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.4);
        }

        /* filter bar galactic */
        .filter-nebula {
            background: var(--glass-white);
            backdrop-filter: blur(12px);
            border-radius: 80px;
            padding: 0.8rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2.8rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: var(--shadow-3d);
        }

        .search-cosmic {
            position: relative;
            flex: 2;
            min-width: 240px;
        }

        .search-cosmic i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-cosmic input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }

        .search-cosmic input:focus {
            outline: none;
            border-color: var(--primary-deep);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .filter-chip {
            padding: 0.6rem 1.6rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.85rem;
            background: transparent;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            transition: var(--transition-smooth);
            color: #1e293b;
            letter-spacing: -0.2px;
        }

        .filter-chip.active {
            background: var(--gradient-primary);
            border-color: transparent;
            color: white;
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.3);
            transform: scale(0.98);
        }

        .filter-chip:not(.active):hover {
            border-color: var(--primary-deep);
            color: var(--primary-deep);
            transform: translateY(-2px);
        }

        /* ---- card grid ---- */
        .auctions-cosmic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        /* premium card design */
        .bid-super-card {
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 12px 24px -12px rgba(0, 0, 0, 0.08);
            position: relative;
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .bid-super-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 28px 40px -16px rgba(102, 126, 234, 0.35);
        }

        .card-media {
            position: relative;
            height: 210px;
            overflow: hidden;
        }

        .card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .bid-super-card:hover .card-media img {
            transform: scale(1.05);
        }

        .card-overlay-dark {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent 70%);
            pointer-events: none;
        }

        .status-emblem {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            padding: 0.3rem 1rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.7rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            letter-spacing: 0.3px;
        }

        .status-leading {
            background: #38b2ac;
            color: white;
        }

        .status-outbid {
            background: #ed8936;
        }

        .status-won {
            background: #d946ef;
        }

        .status-lost {
            background: #64748b;
        }

        .time-warning {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            background: rgba(220, 38, 38, 0.9);
            backdrop-filter: blur(4px);
            padding: 0.25rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            animation: pulseSoft 1.5s infinite;
        }

        @keyframes pulseSoft {
            0% {
                opacity: 0.8;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.02);
                background: #dc2626;
            }

            100% {
                opacity: 0.8;
                transform: scale(1);
            }
        }

        .card-content {
            padding: 1.6rem;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .product-sub {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .bid-price-panel {
            background: var(--soft-gray);
            border-radius: 1.2rem;
            padding: 1rem;
            margin: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .your-bid h6 {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 1px;
        }

        .your-bid .amount {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .position-badge {
            text-align: right;
        }

        .position-badge .rank-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
        }

        .progress-cosmic {
            background: #e2e8f0;
            border-radius: 40px;
            height: 8px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-fill {
            height: 100%;
            border-radius: 40px;
            background: var(--gradient-primary);
            width: 0%;
            transition: width 0.5s;
        }

        .bid-meta-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.75rem;
            color: #475569;
        }

        .action-group {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .btn-ghost-premium {
            flex: 1;
            text-align: center;
            padding: 0.6rem 0;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            background: #f1f5f9;
            color: #1e293b;
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-ghost-premium i {
            font-size: 1rem;
        }

        .btn-ghost-premium:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 14px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-cosmic {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-cosmic:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a67d8, #6b46a0);
        }

        /* empty state */
        .empty-galactic {
            text-align: center;
            padding: 5rem 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border-radius: 3rem;
            margin: 2rem 0;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .empty-icon {
            font-size: 5rem;
            color: #a0aec0;
            margin-bottom: 1rem;
        }

        /* custom pagination */
        .pagination-custom {
            margin-top: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            padding: 1rem 1.8rem;
            border-radius: 80px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .pagination-links-custom {
            display: flex;
            gap: 0.4rem;
        }

        .page-link-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border-radius: 30px;
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .page-link-custom.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        }

        .page-link-custom:hover:not(.active) {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        /* tips section */
        .tips-section {
            margin-top: 3rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 2rem;
            padding: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 1.2rem;
        }

        .tip-card {
            display: flex;
            gap: 1rem;
            align-items: center;
            background: white;
            border-radius: 1.2rem;
            padding: 1.2rem;
            transition: 0.2s;
        }

        @media (max-width: 780px) {
            .bids-master-universe {
                padding: 0 1rem 2rem;
            }

            .stats-nebula {
                grid-template-columns: 1fr;
            }

            .filter-nebula {
                flex-direction: column;
                border-radius: 2rem;
                align-items: stretch;
            }

            .auctions-cosmic-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bids-master-universe">

        {{-- STATS NEBULA --}}
        <div class="stats-nebula">
            <div class="stat-orb" data-target="{{ $bids->total() }}">
                <div class="stat-info">
                    <h4>Total des offres</h4>
                    <div class="stat-number stat-total">0</div>
                </div>
                <div class="stat-icon-bubble"><i class="fas fa-gavel"></i></div>
            </div>
            <div class="stat-orb" data-target="{{ $activeBidsCount ?? 0 }}">
                <div class="stat-info">
                    <h4>En tête</h4>
                    <div class="stat-number stat-leading">0</div>
                </div>
                <div class="stat-icon-bubble"><i class="fas fa-trophy"></i></div>
            </div>
            <div class="stat-orb" data-target="{{ $outbidCount ?? 0 }}">
                <div class="stat-info">
                    <h4>Dépassées</h4>
                    <div class="stat-number stat-outbid">0</div>
                </div>
                <div class="stat-icon-bubble"><i class="fas fa-chart-line"></i></div>
            </div>
            <div class="stat-orb" data-target="{{ $wonCount ?? 0 }}">
                <div class="stat-info">
                    <h4>Enchères gagnées</h4>
                    <div class="stat-number stat-won">0</div>
                </div>
                <div class="stat-icon-bubble"><i class="fas fa-crown"></i></div>
            </div>
        </div>

        {{-- FILTER + SEARCH --}}
        <div class="filter-nebula">
            <div class="search-cosmic">
                <i class="fas fa-search"></i>
                <input type="text" id="searchBidInput" placeholder="Rechercher titre, produit ...">
            </div>
            <div class="filter-chips" id="filterChipsContainer">
                <button class="filter-chip active" data-filter="all">Toutes</button>
                <button class="filter-chip" data-filter="leading">En tête 🏆</button>
                <button class="filter-chip" data-filter="outbid">Dépassées 📉</button>
                <button class="filter-chip" data-filter="won">Gagnées 🎉</button>
                <button class="filter-chip" data-filter="lost">Perdues 💔</button>
            </div>
        </div>

        {{-- BIDS GRID --}}
        @if($bids->count() > 0)
            <div class="auctions-cosmic-grid" id="bidsSuperGrid">
                @foreach($bids as $mise)
                    @php
                        $isWinning = $mise->annonce->getMontantActuel() == $mise->montant && $mise->annonce->statut == 'ACTIVE';
                        $highestBid = $mise->annonce->encheres()->max('montant');
                        $rank = $mise->annonce->encheres()->where('montant', '>', $mise->montant)->count() + 1;
                        $totalBids = $mise->annonce->encheres()->count();
                        $productImage = \App\Helpers\ImageHelper::getProductImage($mise->annonce->produit);
                        $timeLeft = \Carbon\Carbon::parse($mise->annonce->date_fin);
                        $isEndingSoon = $timeLeft->diffInHours(now()) <= 24 && $mise->annonce->statut == 'ACTIVE';
                        $statusKey = '';
                        $statusLabel = '';
                        $badgeClass = '';

                        if ($mise->annonce->statut == 'ACTIVE') {
                            if ($isWinning) {
                                $statusKey = 'leading';
                                $statusLabel = 'En tête';
                                $badgeClass = 'status-leading';
                            } else {
                                $statusKey = 'outbid';
                                $statusLabel = 'Dépassé';
                                $badgeClass = 'status-outbid';
                            }
                        } elseif ($mise->annonce->statut == 'CLOTUREE') {
                            if ($highestBid == $mise->montant) {
                                $statusKey = 'won';
                                $statusLabel = 'Gagnée !';
                                $badgeClass = 'status-won';
                            } else {
                                $statusKey = 'lost';
                                $statusLabel = 'Perdue';
                                $badgeClass = 'status-lost';
                            }
                        } else {
                            $statusKey = 'other';
                            $statusLabel = $mise->annonce->statut;
                            $badgeClass = 'status-lost';
                        }

                        $percentage = $totalBids > 0 ? round((($totalBids - $rank + 1) / $totalBids) * 100) : 0;
                    @endphp
                    <div class="bid-super-card" data-status="{{ $statusKey }}"
                        data-search="{{ strtolower($mise->annonce->titre . ' ' . $mise->annonce->produit->nom) }}">
                        <div class="card-media">
                            <img src="{{ $productImage }}" alt="{{ $mise->annonce->titre }}">
                            <div class="card-overlay-dark"></div>
                            <div class="status-emblem {{ $badgeClass }}">
                                <i
                                    class="fas {{ $statusKey == 'leading' ? 'fa-check-circle' : ($statusKey == 'outbid' ? 'fa-chart-line' : ($statusKey == 'won' ? 'fa-trophy' : 'fa-times')) }}"></i>
                                {{ $statusLabel }}
                            </div>
                            @if($isEndingSoon && $mise->annonce->statut == 'ACTIVE')
                                <div class="time-warning">
                                    <i class="fas fa-hourglass-half"></i> Fin imminente
                                </div>
                            @endif
                        </div>
                        <div class="card-content">
                            <h3 class="product-title">{{ Str::limit($mise->annonce->titre, 48) }}</h3>
                            <div class="product-sub">
                                <span><i class="fas fa-tag"></i> {{ $mise->annonce->produit->categorie->nom ?? 'N/C' }}</span>
                                <span><i class="fas fa-microchip"></i>
                                    {{ $mise->annonce->produit->marque ?: 'Marque inconnue' }}</span>
                            </div>

                            <div class="bid-price-panel">
                                <div class="your-bid">
                                    <h6>Votre enchère</h6>
                                    <div class="amount">{{ number_format($mise->montant, 0) }} <small>MAD</small></div>
                                </div>
                                <div class="position-badge">
                                    <h6>Position</h6>
                                    <div class="rank-number">{{ $rank }}<span style="font-size:1rem;">/{{ $totalBids }}</span></div>
                                </div>
                            </div>

                            <div class="progress-cosmic">
                                <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                            </div>

                            <div class="bid-meta-footer">
                                <span><i class="far fa-calendar-alt"></i> {{ $mise->created_at->format('d/m/Y H:i') }}</span>
                                <span><i class="fas fa-chart-simple"></i> +{{ $percentage }}% progression</span>
                            </div>

                            <div class="action-group">
                                <a href="{{ route('annonces.show', $mise->annonce) }}" class="btn-ghost-premium">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                @if($mise->annonce->statut == 'ACTIVE' && !$isWinning)
                                    <a href="{{ route('annonces.show', $mise->annonce) }}" class="btn-ghost-premium btn-primary-cosmic">
                                        <i class="fas fa-gavel"></i> Renchérir
                                    </a>
                                @endif
                                @if($highestBid == $mise->montant && $mise->annonce->statut == 'CLOTUREE')
                                    <a href="mailto:{{ $mise->annonce->vendeur->client->user->email ?? '' }}?subject=Félicitations - Enchère gagnée : {{ $mise->annonce->titre }}"
                                        class="btn-ghost-premium btn-primary-cosmic">
                                        <i class="fas fa-envelope"></i> Contacter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION (CUSTOM OVERRIDE) --}}
            @if($bids->hasPages())
                <div class="pagination-custom">
                    <div class="pagination-info">
                        Affichage de {{ $bids->firstItem() }} à {{ $bids->lastItem() }} sur {{ $bids->total() }} offres
                    </div>
                    <div class="pagination-links-custom">
                        {{-- Previous --}}
                        @if($bids->onFirstPage())
                            <span class="page-link-custom disabled"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $bids->previousPageUrl() }}" class="page-link-custom"><i class="fas fa-chevron-left"></i></a>
                        @endif

                        @foreach($bids->getUrlRange(max(1, $bids->currentPage() - 2), min($bids->lastPage(), $bids->currentPage() + 2)) as $page => $url)
                            @if($page == $bids->currentPage())
                                <span class="page-link-custom active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-link-custom">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($bids->hasMorePages())
                            <a href="{{ $bids->nextPageUrl() }}" class="page-link-custom"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="page-link-custom disabled"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-galactic">
                <div class="empty-icon"><i class="fas fa-gavel-slash"></i></div>
                <h3 style="font-weight:800;">Aucune offre déposée</h3>
                <p style="color:#475569;">Votre aventure commence ici ! Enchérissez sur des articles uniques.</p>
                <a href="{{ route('auctions.active') }}" class="btn-ghost-premium btn-primary-cosmic"
                    style="display: inline-flex; width: auto; padding: 0.8rem 2rem; margin-top: 1rem;">
                    <i class="fas fa-fire"></i> Explorer les enchères actives
                </a>
            </div>
        @endif

        {{-- TIPS SECTION (if at least one bid) --}}
        @if($bids->count() > 0)
            <div class="tips-section">
                <h4 style="font-weight:800; display: flex; gap:8px;"><i class="fas fa-lightbulb" style="color:#667eea;"></i>
                    Conseils galactiques pour remporter plus d'enchères</h4>
                <div class="tips-grid">
                    <div class="tip-card"><i class="fas fa-chart-line fa-2x" style="color:#667eea;"></i>
                        <span><strong>Analysez</strong> le rythme des enchères, misez stratégiquement en fin de session.</span>
                    </div>
                    <div class="tip-card"><i class="fas fa-clock fa-2x" style="color:#ed8936;"></i>
                        <span><strong>Anticipez</strong> les 5 dernières minutes, c’est là que tout se joue !</span>
                    </div>
                    <div class="tip-card"><i class="fas fa-gem fa-2x" style="color:#d946ef;"></i> <span><strong>Budget
                                max</strong> : définissez votre limite et respectez-la pour des achats sereins.</span></div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            (function () {
                // ----- numeric animations for stats
                const statNumbers = [
                    { el: document.querySelector('.stat-total'), target: {{ $bids->total() }} },
                    { el: document.querySelector('.stat-leading'), target: {{ $activeBidsCount ?? 0 }} },
                    { el: document.querySelector('.stat-outbid'), target: {{ $outbidCount ?? 0 }} },
                    { el: document.querySelector('.stat-won'), target: {{ $wonCount ?? 0 }} }
                ];
                function animateStat(element, target) {
                    if (!element) return;
                    let current = 0;
                    const duration = 1000;
                    const stepTime = 20;
                    const steps = duration / stepTime;
                    const increment = target / steps;
                    let iterator = 0;
                    const timer = setInterval(() => {
                        iterator++;
                        current = Math.min(Math.floor(increment * iterator), target);
                        element.innerText = current.toLocaleString();
                        if (iterator >= steps) {
                            element.innerText = target.toLocaleString();
                            clearInterval(timer);
                        }
                    }, stepTime);
                }
                statNumbers.forEach(stat => { if (stat.el) animateStat(stat.el, stat.target); });

                // ----- filtering logic
                const searchInput = document.getElementById('searchBidInput');
                const filterChips = document.querySelectorAll('.filter-chip');
                let activeFilter = 'all';
                const cards = document.querySelectorAll('.bid-super-card');

                function filterCards() {
                    const searchTerm = searchInput.value.toLowerCase();
                    cards.forEach(card => {
                        const searchData = card.dataset.search || '';
                        const cardStatus = card.dataset.status || '';
                        const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
                        const matchesFilter = activeFilter === 'all' || cardStatus === activeFilter;
                        card.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
                    });
                }

                if (searchInput) {
                    searchInput.addEventListener('input', filterCards);
                }

                filterChips.forEach(chip => {
                    chip.addEventListener('click', () => {
                        filterChips.forEach(c => c.classList.remove('active'));
                        chip.classList.add('active');
                        activeFilter = chip.dataset.filter;
                        filterCards();
                    });
                });

                // Reset clear style for search (optional)
                const clearBtn = document.createElement('button');
                // not needed but just for style consistency
            })();
    </script>@endpush
@endsection