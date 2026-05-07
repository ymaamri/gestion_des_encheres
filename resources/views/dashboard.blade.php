{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="dash-wrapper">
        @auth
            @role('admin')
            {{-- ============ ADMIN DASHBOARD ============ --}}
            <div class="dash-hero admin-hero">
                <div class="hero-bg-shapes">
                    <span class="shape shape-1"></span>
                    <span class="shape shape-2"></span>
                    <span class="shape shape-3"></span>
                    <span class="shape shape-4"></span>
                    <span class="shape shape-5"></span>
                </div>
                <div class="hero-grid">
                    <div class="hero-text">
                        <div class="greeting-badge"><i class="fas fa-crown"></i> Administrateur</div>
                        <h1 class="hero-title">Bonjour, <span class="name-highlight">{{ Auth::user()->nom }}</span> 👋</h1>
                        <p class="hero-desc">Surveillez l’écosystème d’enchères en un clin d’œil.</p>
                        <div class="hero-actions">
                            <a href="{{ route('admin.users.index') }}" class="btn-glass"><i class="fas fa-users"></i>
                                Utilisateurs</a>
                            <a href="{{ route('admin.auctions.index') }}" class="btn-glass"><i class="fas fa-gavel"></i>
                                Enchères</a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="orb orb-1"></div>
                        <div class="orb orb-2"></div>
                        <div class="orb orb-3"></div>
                        <div class="chart-pulse">
                            <span class="pulse-bar" style="height:70%"></span>
                            <span class="pulse-bar" style="height:40%"></span>
                            <span class="pulse-bar" style="height:90%"></span>
                            <span class="pulse-bar" style="height:60%"></span>
                            <span class="pulse-bar" style="height:30%"></span>
                        </div>
                    </div>
                </div>
                <svg class="hero-wave" viewBox="0 0 1440 160" preserveAspectRatio="none">
                    <path fill="#f8fafc"
                        d="M0,64L48,80C96,96,192,128,288,138.7C384,149,480,139,576,122.7C672,107,768,85,864,80C960,75,1056,85,1152,90.7C1248,96,1344,96,1392,96L1440,96L1440,320L0,320Z" />
                </svg>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #667eea"><i class="fas fa-users"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Utilisateurs</span>
                        <span class="stat-value" data-target="{{ $stats['total_users'] ?? 0 }}">0</span>
                        <span class="stat-sub">+12% ce mois</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #48bb78"><i class="fas fa-gavel"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Enchères totales</span>
                        <span class="stat-value" data-target="{{ $stats['total_auctions'] ?? 0 }}">0</span>
                        <span class="stat-sub">{{ $stats['active_auctions'] ?? 0 }} actives</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #ed8936"><i class="fas fa-hand-holding-usd"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Offres placées</span>
                        <span class="stat-value" data-target="{{ $stats['total_bids'] ?? 0 }}">0</span>
                        <span class="stat-sub">Volume ~{{ number_format(($stats['total_bids'] ?? 0) * 1000) }} TND</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #f56565"><i class="fas fa-tags"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Catégories</span>
                        <span class="stat-value" data-target="{{ \App\Models\Categorie::count() }}">0</span>
                        <span class="stat-sub">{{ \App\Models\SousCategorie::count() }} sous-catégories</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
            </div>

            <div class="dash-panels">
                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-users"></i> Derniers inscrits</h2>
                        <a href="{{ route('admin.users.index') }}" class="link-underline">Voir tout</a>
                    </div>
                    <ul class="custom-list">
                        @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <li class="list-item">
                                <div class="user-avatar" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    {{ strtoupper(substr($user->nom, 0, 1)) }}
                                </div>
                                <div class="item-body">
                                    <strong>{{ $user->nom }} {{ $user->prenom }}</strong>
                                    <small>{{ $user->email }}</small>
                                </div>
                                <span
                                    class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'vendeur' ? 'warning' : 'info') }}">{{ ucfirst($user->role) }}</span>
                                <span class="time" title="{{ $user->created_at }}">{{ $user->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-gavel"></i> Dernières enchères</h2>
                        <a href="{{ route('admin.auctions.index') }}" class="link-underline">Voir tout</a>
                    </div>
                    <ul class="custom-list">
                        @foreach(\App\Models\Annonce::with('produit')->latest()->take(5)->get() as $annonce)
                            @php
                                $img = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                            @endphp
                            <li class="list-item">
                                <img src="{{ $img }}" class="item-img" alt="produit">
                                <div class="item-body">
                                    <strong>{{ Str::limit($annonce->titre, 25) }}</strong>
                                    <small>{{ number_format($annonce->getMontantActuel(), 2) }} TND</small>
                                </div>
                                <span
                                    class="badge badge-{{ $annonce->statut == 'ACTIVE' ? 'success' : ($annonce->statut == 'CLOTUREE' ? 'secondary' : 'warning') }}">{{ $annonce->statut }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @endrole

            @role('vendeur')
            {{-- ============ SELLER DASHBOARD ============ --}}
            <div class="dash-hero seller-hero">
                <div class="hero-bg-shapes">
                    <span class="shape shape-1"></span>
                    <span class="shape shape-2"></span>
                    <span class="shape shape-3"></span>
                    <span class="shape shape-4"></span>
                </div>
                <div class="hero-grid">
                    <div class="hero-text">
                        <div class="greeting-badge"><i class="fas fa-store-alt"></i> Vendeur</div>
                        <h1 class="hero-title">Bonjour, <span class="name-highlight">{{ Auth::user()->nom }}</span> 🛒</h1>
                        <p class="hero-desc">Vos annonces performent. Continuez sur votre lancée.</p>
                        <div class="hero-actions">
                            <a href="{{ route('annonces.create') }}" class="btn-glass"><i class="fas fa-plus-circle"></i>
                                Nouvelle annonce</a>
                            <a href="{{ route('annonces.index') }}" class="btn-glass"><i class="fas fa-list-ul"></i> Mes
                                annonces</a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="seller-gauge">
                            <div class="gauge-label">Note</div>
                            <div class="gauge-value">{{ number_format($stats['rating'] ?? 0, 1) }}</div>
                            <div class="gauge-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"
                                        style="color: {{ $i <= round($stats['rating'] ?? 0) ? '#f6c23e' : '#cbd5e0' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <svg class="hero-wave" viewBox="0 0 1440 160" preserveAspectRatio="none">
                    <path fill="#f8fafc"
                        d="M0,64L48,80C96,96,192,128,288,138.7C384,149,480,139,576,122.7C672,107,768,85,864,80C960,75,1056,85,1152,90.7C1248,96,1344,96,1392,96L1440,96L1440,320L0,320Z" />
                </svg>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #667eea"><i class="fas fa-box-open"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Mes annonces</span>
                        <span class="stat-value" data-target="{{ $stats['total_listings'] ?? 0 }}">0</span>
                        <span class="stat-sub">{{ $stats['active_listings'] ?? 0 }} actives</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #48bb78"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Ventes réalisées</span>
                        <span class="stat-value" data-target="{{ $stats['total_sales'] ?? 0 }}">0</span>
                        <span class="stat-sub">Ce mois-ci</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #f6c23e"><i class="fas fa-star"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Note moyenne</span>
                        <span class="stat-value" data-target="{{ $stats['rating'] ?? 0 }}">0</span>
                        <span class="stat-sub">/5</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #f56565"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Chiffre d'affaires</span>
                        <span class="stat-value" data-target="{{ ($stats['total_sales'] ?? 0) * 1000 }}">0</span>
                        <span class="stat-sub">Mois en cours</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
            </div>

            <div class="dash-panels">
                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-clock"></i> Mes annonces récentes</h2>
                        <a href="{{ route('annonces.index') }}" class="link-underline">Gérer</a>
                    </div>
                    <ul class="custom-list">
                        @php
                            $sellerAuctions = Auth::user()->client->vendeur->annonces()->with('produit', 'encheres')->latest()->take(5)->get();
                        @endphp
                        @forelse($sellerAuctions as $annonce)
                            @php
                                $img = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                            @endphp
                            <li class="list-item">
                                <img src="{{ $img }}" class="item-img" alt="produit">
                                <div class="item-body">
                                    <strong>{{ Str::limit($annonce->titre, 30) }}</strong>
                                    <small>{{ $annonce->produit->nom }}</small>
                                </div>
                                <span
                                    class="badge badge-{{ $annonce->statut == 'ACTIVE' ? 'success' : ($annonce->statut == 'EN_ATTENTE' ? 'warning' : ($annonce->statut == 'CLOTUREE' ? 'secondary' : 'danger')) }}">{{ $annonce->statut }}</span>
                                <span class="time">{{ $annonce->encheres()->count() }} offre(s)</span>
                            </li>
                        @empty
                            <li class="list-item empty">Aucune annonce pour le moment</li>
                        @endforelse
                    </ul>
                </div>
                <div class="panel tips-panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-lightbulb"></i> Astuces</h2>
                    </div>
                    <div class="tips-grid">
                        <div class="tip-card">
                            <i class="fas fa-camera-retro"></i>
                            <strong>Photos nettes</strong>
                            <p>+85% de chances avec des visuels clairs</p>
                        </div>
                        <div class="tip-card">
                            <i class="fas fa-tag"></i>
                            <strong>Prix attractif</strong>
                            <p>Un prix de départ compétitif attire les offres</p>
                        </div>
                        <div class="tip-card">
                            <i class="fas fa-align-left"></i>
                            <strong>Description précise</strong>
                            <p>Détaillez l'état pour rassurer les acheteurs</p>
                        </div>
                    </div>
                </div>
            </div>
            @endrole

            @role('client')
            {{-- ============ CLIENT DASHBOARD ============ --}}
            <div class="dash-hero client-hero">
                <div class="hero-bg-shapes">
                    <span class="shape shape-1"></span>
                    <span class="shape shape-2"></span>
                    <span class="shape shape-3"></span>
                </div>
                <div class="hero-grid">
                    <div class="hero-text">
                        <div class="greeting-badge"><i class="fas fa-user-check"></i> Enchérisseur</div>
                        <h1 class="hero-title">Bonjour, <span class="name-highlight">{{ Auth::user()->nom }}</span> 🎯</h1>
                        <p class="hero-desc">Prêt à remporter les plus belles offres ?</p>
                        <div class="hero-actions">
                            <a href="{{ route('auctions.active') }}" class="btn-glass"><i class="fas fa-search"></i> Enchères
                                actives</a>
                            <a href="{{ route('my.bids') }}" class="btn-glass"><i class="fas fa-gavel"></i> Mes offres</a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="bid-animation">
                            <span class="bid-bubble"></span>
                            <span class="bid-bubble"></span>
                            <span class="bid-bubble"></span>
                            <span class="bid-hammer"><i class="fas fa-gavel"></i></span>
                        </div>
                    </div>
                </div>
                <svg class="hero-wave" viewBox="0 0 1440 160" preserveAspectRatio="none">
                    <path fill="#f8fafc"
                        d="M0,64L48,80C96,96,192,128,288,138.7C384,149,480,139,576,122.7C672,107,768,85,864,80C960,75,1056,85,1152,90.7C1248,96,1344,96,1392,96L1440,96L1440,320L0,320Z" />
                </svg>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #667eea"><i class="fas fa-hand-paper"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Mes offres</span>
                        <span class="stat-value" data-target="{{ $stats['total_bids'] ?? 0 }}">0</span>
                        <span class="stat-sub">{{ $stats['active_bids'] ?? 0 }} en cours</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #48bb78"><i class="fas fa-trophy"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Enchères gagnées</span>
                        <span class="stat-value" data-target="{{ $stats['won_auctions'] ?? 0 }}">0</span>
                        <span class="stat-sub">Félicitations !</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #f6c23e"><i class="fas fa-wallet"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Mon solde</span>
                        <span class="stat-value" data-target="{{ $stats['balance'] ?? 0 }}">0</span>
                        <span class="stat-sub">TND</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="--icon-bg: #f56565"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Taux de réussite</span>
                        <span class="stat-value"
                            data-target="{{ $stats['total_bids'] > 0 ? round(($stats['won_auctions'] / $stats['total_bids']) * 100) : 0 }}">0</span>
                        <span class="stat-sub">%</span>
                    </div>
                    <div class="stat-glow"></div>
                </div>
            </div>

            <div class="dash-panels">
                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-fire"></i> Enchères actives</h2>
                        <a href="{{ route('auctions.active') }}" class="link-underline">Explorer</a>
                    </div>
                    <div class="auction-mini-grid">
                        @php
                            $activeAuctions = \App\Models\Annonce::with('produit')
                                ->where('statut', 'ACTIVE')
                                ->where('date_fin', '>', now())
                                ->orderBy('created_at', 'desc')
                                ->limit(6)
                                ->get();
                        @endphp
                        @forelse($activeAuctions as $auction)
                            @php
                                $img = \App\Helpers\ImageHelper::getProductImage($auction->produit);
                                $timeLeft = \Carbon\Carbon::parse($auction->date_fin);
                                $isEndingSoon = $timeLeft->diffInHours(now()) <= 24;
                            @endphp
                            <a href="{{ route('annonces.show', $auction) }}" class="auction-mini-card">
                                <div class="card-img-wrapper">
                                    <img src="{{ $img }}" alt="{{ $auction->titre }}">
                                    @if($isEndingSoon)<span class="soon-badge">Bientôt fini</span>@endif
                                </div>
                                <div class="card-content">
                                    <h4>{{ Str::limit($auction->titre, 30) }}</h4>
                                    <div class="price-row">
                                        <span class="current">{{ number_format($auction->getMontantActuel(), 2) }} TND</span>
                                        <span class="bids"><i class="fas fa-gavel"></i> {{ $auction->encheres()->count() }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="empty-auctions">Aucune enchère active pour l'instant.</div>
                        @endforelse
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-history"></i> Mon activité récente</h2>
                    </div>
                    <ul class="custom-list">
                        @php
                            $myBids = Auth::user()->client->encheres()->with('annonce')->latest()->take(5)->get();
                        @endphp
                        @forelse($myBids as $bid)
                            <li class="list-item">
                                <div class="item-body">
                                    <strong>{{ $bid->annonce->titre ?? 'Enchère supprimée' }}</strong>
                                    <small>Mon offre : {{ number_format($bid->montant, 2) }} TND</small>
                                </div>
                                <span class="badge badge-info">{{ $bid->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="list-item empty">Aucune activité récente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endrole
        @endauth
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* ============================================================
                                                       DASHBOARD X-TREME - PUR CSS NATIF, ÉBLOUISSEMENT GARANTI
                                                       ============================================================ */
        :root {
            --g-start: #667eea;
            --g-end: #764ba2;
            --gradient: linear-gradient(135deg, var(--g-start), var(--g-end));
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow-card: 0 20px 45px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 30px 60px rgba(0, 0, 0, 0.15);
            --radius-xl: 32px;
            --radius-lg: 24px;
            --radius-md: 18px;
            --transition: 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --font: 'Inter', system-ui, sans-serif;
            --bg-page: #f8fafc;
        }

        .dash-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px 40px;
            font-family: var(--font);
            color: #1e293b;
            background: var(--bg-page);
        }

        /* ========== HERO ========== */
        .dash-hero {
            position: relative;
            background: var(--gradient);
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem 0;
            margin-bottom: 3rem;
            overflow: hidden;
            color: white;
            box-shadow: 0 25px 50px -12px rgba(102, 126, 234, 0.4);
            z-index: 2;
        }

        .hero-bg-shapes span {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            animation: floatShape 20s infinite alternate;
        }

        .shape-1 {
            width: 120px;
            height: 120px;
            top: -30px;
            left: 10%;
            animation-duration: 18s;
        }

        .shape-2 {
            width: 80px;
            height: 80px;
            top: 20%;
            right: 15%;
            animation-duration: 22s;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            bottom: -80px;
            right: 5%;
            animation-duration: 25s;
        }

        .shape-4 {
            width: 60px;
            height: 60px;
            bottom: 30%;
            left: 5%;
            animation-duration: 16s;
        }

        .shape-5 {
            width: 100px;
            height: 100px;
            top: 10%;
            right: 40%;
            animation-duration: 19s;
        }

        @keyframes floatShape {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            100% {
                transform: translate(30px, -40px) rotate(25deg) scale(1.2);
            }
        }

        .hero-grid {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            padding-bottom: 120px;
            /* pour la vague */
        }

        .hero-text {
            max-width: 600px;
        }

        .greeting-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.8rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .name-highlight {
            background: linear-gradient(to right, #fff9c4, #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 1.1rem;
            opacity: 0.92;
            margin-bottom: 2rem;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: white;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .hero-visual {
            position: relative;
            width: 280px;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.05));
            animation: orbit 10s infinite linear;
        }

        .orb-1 {
            width: 120px;
            height: 120px;
            animation-duration: 12s;
        }

        .orb-2 {
            width: 80px;
            height: 80px;
            top: 20%;
            right: 10%;
            animation-duration: 8s;
            animation-delay: -4s;
        }

        .orb-3 {
            width: 50px;
            height: 50px;
            bottom: 20%;
            left: 10%;
            animation-duration: 6s;
        }

        @keyframes orbit {
            0% {
                transform: rotate(0deg) translateX(30px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(30px) rotate(-360deg);
            }
        }

        .chart-pulse {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 100px;
            width: 150px;
        }

        .pulse-bar {
            width: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px 10px 0 0;
            animation: pulseBar 1.8s ease-in-out infinite;
        }

        .pulse-bar:nth-child(2) {
            animation-delay: 0.2s;
        }

        .pulse-bar:nth-child(3) {
            animation-delay: 0.4s;
        }

        .pulse-bar:nth-child(4) {
            animation-delay: 0.6s;
        }

        .pulse-bar:nth-child(5) {
            animation-delay: 0.8s;
        }

        @keyframes pulseBar {

            0%,
            100% {
                transform: scaleY(0.6);
                opacity: 0.5;
            }

            50% {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        .seller-gauge {
            text-align: center;
            color: white;
        }

        .gauge-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .gauge-value {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .gauge-stars {
            margin-top: 0.5rem;
            font-size: 1.3rem;
        }

        .bid-animation {
            position: relative;
            width: 150px;
            height: 150px;
        }

        .bid-bubble {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: bubbleUp 2.5s infinite;
        }

        .bid-bubble:nth-child(1) {
            left: 20%;
            animation-delay: 0s;
        }

        .bid-bubble:nth-child(2) {
            left: 50%;
            animation-delay: 0.8s;
        }

        .bid-bubble:nth-child(3) {
            left: 70%;
            animation-delay: 1.6s;
        }

        @keyframes bubbleUp {
            0% {
                bottom: 0;
                opacity: 1;
                transform: scale(1);
            }

            100% {
                bottom: 100%;
                opacity: 0;
                transform: scale(0.5);
            }
        }

        .bid-hammer {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2.5rem;
            color: white;
            animation: hammerBounce 1.5s infinite;
        }

        @keyframes hammerBounce {

            0%,
            100% {
                transform: translateX(-50%) translateY(0);
            }

            50% {
                transform: translateX(-50%) translateY(-10px);
            }
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }

        /* ========== STATS GRID ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin-top: -20px;
            position: relative;
            z-index: 3;
            padding: 0 0 2.5rem;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 1.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--glass-border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .stat-card:hover {
            transform: translateY(-8px) rotateX(2deg) scale(1.02);
            box-shadow: var(--shadow-hover);
            border-color: rgba(102, 126, 234, 0.4);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--icon-bg);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
        }

        .stat-value {
            display: block;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.1;
            margin: 0.15rem 0 0.3rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-sub {
            font-size: 0.8rem;
            color: #475569;
            font-weight: 500;
        }

        .stat-glow {
            position: absolute;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent 70%);
            top: -30px;
            right: -30px;
            border-radius: 50%;
            transition: opacity 0.3s;
            opacity: 0;
        }

        .stat-card:hover .stat-glow {
            opacity: 1;
        }

        /* ========== PANELS ========== */
        .dash-panels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
            gap: 2rem;
            margin-top: 0.5rem;
        }

        .panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(255, 255, 255, 0.6);
            overflow: hidden;
            transition: var(--transition);
        }

        .panel:hover {
            box-shadow: var(--shadow-hover);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.3rem 1.8rem;
            border-bottom: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        .panel-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #1e293b;
        }

        .link-underline {
            color: var(--g-start);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .link-underline:hover {
            text-decoration: underline;
        }

        .custom-list {
            list-style: none;
            padding: 0.8rem 0;
            margin: 0;
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.8rem;
            transition: background 0.2s;
            border-bottom: 1px solid #f1f5f9;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background: #f8fafc;
        }

        .user-avatar,
        .item-img {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            object-fit: cover;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .item-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .item-body strong {
            font-size: 0.92rem;
            color: #0f172a;
        }

        .item-body small {
            font-size: 0.78rem;
            color: #64748b;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
            white-space: nowrap;
        }

        .badge-danger {
            background: #f56565;
        }

        .badge-warning {
            background: #ed8936;
        }

        .badge-info {
            background: #4299e1;
        }

        .badge-success {
            background: #48bb78;
        }

        .badge-secondary {
            background: #a0aec0;
        }

        .time {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-left: auto;
            white-space: nowrap;
        }

        .empty {
            justify-content: center;
            padding: 2rem;
            color: #94a3b8;
            font-style: italic;
        }

        .tips-panel .panel-header {
            background: #fefce8;
        }

        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
        }

        .tip-card {
            background: white;
            border-radius: 18px;
            padding: 1.3rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: var(--transition);
        }

        .tip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .tip-card i {
            font-size: 1.8rem;
            color: var(--g-start);
            margin-bottom: 0.6rem;
        }

        .tip-card strong {
            display: block;
            margin-bottom: 0.3rem;
        }

        .tip-card p {
            font-size: 0.8rem;
            color: #475569;
            margin: 0;
        }

        /* Auction mini cards for client */
        .auction-mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.2rem;
            padding: 1.5rem;
        }

        .auction-mini-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .auction-mini-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-img-wrapper {
            position: relative;
            height: 140px;
            overflow: hidden;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .soon-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #f6c23e;
            color: #1e293b;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        .card-content {
            padding: 0.9rem 1rem;
        }

        .card-content h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        .current {
            font-weight: 700;
            color: var(--g-start);
        }

        .bids {
            color: #64748b;
        }

        .empty-auctions {
            grid-column: 1 / -1;
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-grid {
                flex-direction: column;
                text-align: center;
            }

            .hero-visual {
                margin-top: 2rem;
            }

            .dash-panels {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate stat values on scroll into view
            const statValues = document.querySelectorAll('.stat-value[data-target]');
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseFloat(el.getAttribute('data-target'));
                        animateValue(el, 0, target, 1200);
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            statValues.forEach(el => observer.observe(el));

            function animateValue(el, start, end, duration) {
                const range = end - start;
                const startTime = performance.now();
                const step = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const current = progress * range + start;
                    el.textContent = Number.isInteger(end) ? Math.floor(current).toLocaleString() : current.toFixed(1);
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = Number.isInteger(end) ? end.toLocaleString() : end.toFixed(1);
                    }
                };
                requestAnimationFrame(step);
            }
        });
    </script>
@endpush