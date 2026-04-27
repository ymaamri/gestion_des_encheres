{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/my-bids.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Offres')
@section('page-title', 'Mes Offres')
@section('breadcrumb', 'Mes Offres')

@push('styles')
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-check:checked + .btn-outline-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }

        .btn-outline-primary {
            border-color: #667eea;
            color: #667eea;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4 g-4">
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 fw-bold text-uppercase small">Total des offres</h6>
                                    <h3 class="text-dark mb-0 fw-bold">{{ $bids->total() }}</h3>
                                </div>
                                <div class="bg-gradient-theme rounded-circle shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 54px; height: 54px;">
                                    <i class="material-symbols-rounded text-white" style="font-size: 28px;">gavel</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 fw-bold text-uppercase small">Offres en tête</h6>
                                    <h3 class="text-dark mb-0 fw-bold">{{ $activeBidsCount ?? 0 }}</h3>
                                </div>
                                <div class="rounded-circle shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 54px; height: 54px; background: linear-gradient(135deg, #4fd1c5 0%, #38b2ac 100%);">
                                    <i class="material-symbols-rounded text-white" style="font-size: 28px;">trending_up</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 fw-bold text-uppercase small">Offres dépassées</h6>
                                    <h3 class="text-dark mb-0 fw-bold">{{ $outbidCount ?? 0 }}</h3>
                                </div>
                                <div class="rounded-circle shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 54px; height: 54px; background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);">
                                    <i class="material-symbols-rounded text-white" style="font-size: 28px;">trending_down</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 fw-bold text-uppercase small">Enchères gagnées</h6>
                                    <h3 class="text-dark mb-0 fw-bold">{{ $wonCount ?? 0 }}</h3>
                                </div>
                                <div class="rounded-circle shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 54px; height: 54px; background: linear-gradient(135deg, #d63384 0%, #c2185b 100%);">
                                    <i class="material-symbols-rounded text-white" style="font-size: 28px;">emoji_events</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="card mb-4 shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="statusFilter" id="filterAll" value="all" checked>
                                <label class="btn btn-outline-primary rounded-3 me-2" for="filterAll">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">list</i> Tous
                                </label>

                                <input type="radio" class="btn-check" name="statusFilter" id="filterLeading" value="leading">
                                <label class="btn btn-outline-primary rounded-3 me-2" for="filterLeading">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">trending_up</i> En tête
                                </label>

                                <input type="radio" class="btn-check" name="statusFilter" id="filterOutbid" value="outbid">
                                <label class="btn btn-outline-primary rounded-3 me-2" for="filterOutbid">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">trending_down</i> Dépassé
                                </label>

                                <input type="radio" class="btn-check" name="statusFilter" id="filterWon" value="won">
                                <label class="btn btn-outline-primary rounded-3 me-2" for="filterWon">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">emoji_events</i> Gagnée
                                </label>

                                <input type="radio" class="btn-check" name="statusFilter" id="filterLost" value="lost">
                                <label class="btn btn-outline-primary rounded-3" for="filterLost">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">close</i> Perdue
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-outline bg-light rounded-3 overflow-hidden border">
                                <span class="input-group-text bg-transparent border-0 px-3">
                                    <i class="material-symbols-rounded text-muted">search</i>
                                </span>
                                <input type="text" id="bidSearch" class="form-control border-0 px-1 py-2 text-dark" placeholder="Rechercher..." style="background: transparent;">
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-gradient rounded-3 px-4" onclick="location.reload()">
                                <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">refresh</i> Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bids Grid -->
            @if($bids->count() > 0)
                <div class="row g-4" id="bidsGrid">
                    @foreach($bids as $mise)
                        @php
                            $isWinning = $mise->annonce->getMontantActuel() == $mise->montant && $mise->annonce->statut == 'ACTIVE';
                            $highestBid = $mise->annonce->encheres()->max('montant');
                            $rank = $mise->annonce->encheres()->where('montant', '>', $mise->montant)->count() + 1;
                            $totalBids = $mise->annonce->encheres()->count();
                            $productImage = \App\Helpers\ImageHelper::getProductImage($mise->annonce->produit);
                            $timeLeft = \Carbon\Carbon::parse($mise->annonce->date_fin);
                            $isEndingSoon = $timeLeft->diffInHours(now()) <= 24 && $mise->annonce->statut == 'ACTIVE';

                            // Determine status for filtering
                            $statusClass = '';
                            if ($mise->annonce->statut == 'ACTIVE') {
                                $statusClass = $isWinning ? 'status-leading' : 'status-outbid';
                            } elseif ($mise->annonce->statut == 'CLOTUREE') {
                                $statusClass = $highestBid == $mise->montant ? 'status-won' : 'status-lost';
                            }
                        @endphp
                        <div class="col-xl-4 col-md-6 bid-card {{ $statusClass }}" data-title="{{ strtolower($mise->annonce->titre) }}" data-product="{{ strtolower($mise->annonce->produit->nom) }}">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="transition: all 0.3s ease;">
                                <!-- Card Image -->
                                <div class="position-relative" style="height: 200px; overflow: hidden;">
                                    <img src="{{ $productImage }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $mise->annonce->produit->nom }}">

                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if($mise->annonce->statut == 'ACTIVE')
                                            @if($isWinning)
                                                <span class="badge" style="background: linear-gradient(135deg, #4fd1c5 0%, #38b2ac 100%); padding: 8px 12px; font-size: 0.85rem;">
                                                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">check_circle</i> En tête 🏆
                                                </span>
                                            @else
                                                <span class="badge" style="background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%); padding: 8px 12px; font-size: 0.85rem;">
                                                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">trending_down</i> Dépassé
                                                </span>
                                            @endif
                                        @elseif($mise->annonce->statut == 'CLOTUREE')
                                            @if($highestBid == $mise->montant)
                                                <span class="badge" style="background: linear-gradient(135deg, #d63384 0%, #c2185b 100%); padding: 8px 12px; font-size: 0.85rem;">
                                                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">emoji_events</i> Gagnée ! 🎉
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" style="padding: 8px 12px; font-size: 0.85rem;">
                                                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">close</i> Perdue
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-gradient-theme" style="padding: 8px 12px; font-size: 0.85rem;">
                                                <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">pending</i> {{ $mise->annonce->statut }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Ending Soon Badge -->
                                    @if($isEndingSoon && $mise->annonce->statut == 'ACTIVE')
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-danger" style="padding: 8px 12px; font-size: 0.85rem; animation: pulse 2s infinite;">
                                                <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">schedule</i> Fin imminente
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.1rem; min-height: 50px;">
                                        {{ Str::limit($mise->annonce->titre, 50) }}
                                    </h5>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1" style="font-size: 0.9rem;">
                                            <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">inventory_2</i>
                                            {{ $mise->annonce->produit->nom }}
                                        </p>
                                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                            <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">branding_watermark</i>
                                            {{ $mise->annonce->produit->marque ?: 'N/A' }} • 
                                            <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">category</i>
                                            {{ $mise->annonce->produit->categorie->nom ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <!-- Price & Position -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                        <div>
                                            <small class="text-muted d-block mb-1">Votre enchère</small>
                                            <h4 class="mb-0 fw-bold" style="color: {{ $isWinning ? '#4fd1c5' : '#667eea' }};">
                                                {{ number_format($mise->montant, 2) }} <small style="font-size: 0.7rem;">MAD</small>
                                            </h4>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block mb-1">Position</small>
                                            <h4 class="mb-0 fw-bold text-dark">
                                                {{ $rank }}<small style="font-size: 0.7rem;">/{{ $totalBids }}</small>
                                            </h4>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Progression</small>
                                            <small class="text-muted">{{ round(($totalBids - $rank + 1) / $totalBids * 100) }}%</small>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 10px;">
                                            @php
                                                $positionPercent = ($totalBids - $rank + 1) / $totalBids * 100;
                                            @endphp
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: {{ $positionPercent }}%; background: {{ $isWinning ? 'linear-gradient(135deg, #4fd1c5 0%, #38b2ac 100%)' : 'linear-gradient(135deg, #f6ad55 0%, #ed8936 100%)' }};" 
                                                 aria-valuenow="{{ $positionPercent }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date -->
                                    <p class="text-muted mb-3" style="font-size: 0.85rem;">
                                        <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">schedule</i>
                                        {{ $mise->created_at->format('d/m/Y H:i') }} • {{ $mise->created_at->diffForHumans() }}
                                    </p>

                                    <!-- Actions -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('annonces.show', $mise->annonce) }}" class="btn btn-outline-gradient flex-fill rounded-3">
                                            <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">visibility</i> Voir
                                        </a>
                                        @if($mise->annonce->statut == 'ACTIVE' && !$isWinning)
                                            <a href="{{ route('annonces.show', $mise->annonce) }}" class="btn btn-gradient flex-fill rounded-3">
                                                <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">gavel</i> Renchérir
                                            </a>
                                        @endif
                                        @if($highestBid == $mise->montant && $mise->annonce->statut == 'CLOTUREE')
                                            <a href="#" onclick="contactSeller('{{ $mise->annonce->vendeur->client->user->email }}', '{{ $mise->annonce->titre }}')" class="btn btn-gradient flex-fill rounded-3">
                                                <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">mail</i> Contacter
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bids->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">history</i>
                    <h5 class="mt-3 text-secondary">Aucune offre trouvée</h5>
                    <p class="text-muted mb-0">Vous n'avez pas encore participé à des enchères.</p>
                    <p class="text-muted">Découvrez les enchères actives et commencez à enchérir !</p>
                    <a href="{{ route('auctions.active') }}" class="btn-gradient d-inline-block text-decoration-none mt-3 rounded-3">
                        <i class="material-symbols-rounded align-middle me-1">gavel</i> Explorer les enchères actives
                    </a>
                </div>
            @endif

            <!-- Tips Section -->
            @if($bids->count() > 0)
                <div class="card mt-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                            <i class="material-symbols-rounded me-2 text-theme fs-4">tips_and_updates</i> Conseils pour réussir vos enchères
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-4 border-0 shadow-sm h-100 align-items-center" style="background-color: #f8f9fa;">
                                    <div class="icon icon-shape bg-white rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 54px; height: 54px;">
                                        <i class="material-symbols-rounded text-primary" style="font-size: 28px;">schedule</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Soyez réactif</h6>
                                        <small class="text-muted d-block lh-sm">Surveillez la fin des enchères pour ne pas vous faire dépasser.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-4 border-0 shadow-sm h-100 align-items-center" style="background-color: #f8f9fa;">
                                    <div class="icon icon-shape bg-white rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 54px; height: 54px;">
                                        <i class="material-symbols-rounded text-success" style="font-size: 28px;">paid</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Fixez votre budget</h6>
                                        <small class="text-muted d-block lh-sm">Définissez un montant maximum et respectez-le strictement.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-4 border-0 shadow-sm h-100 align-items-center" style="background-color: #f8f9fa;">
                                    <div class="icon icon-shape bg-white rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 54px; height: 54px;">
                                        <i class="material-symbols-rounded text-warning" style="font-size: 28px;">analytics</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Analysez la concurrence</h6>
                                        <small class="text-muted d-block lh-sm">Observez les habitudes des autres enchérisseurs pour gagner.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('bidSearch')?.addEventListener('keyup', function() {
            let searchTerm = this.value.toLowerCase();
            let cards = document.querySelectorAll('.bid-card');

            cards.forEach(card => {
                let title = card.getAttribute('data-title');
                let product = card.getAttribute('data-product');
                let text = title + ' ' + product;

                if (text.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter functionality
        document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
            radio.addEventListener('change', function() {
                let filterValue = this.value;
                let cards = document.querySelectorAll('.bid-card');

                cards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = '';
                    } else if (filterValue === 'leading' && card.classList.contains('status-leading')) {
                        card.style.display = '';
                    } else if (filterValue === 'outbid' && card.classList.contains('status-outbid')) {
                        card.style.display = '';
                    } else if (filterValue === 'won' && card.classList.contains('status-won')) {
                        card.style.display = '';
                    } else if (filterValue === 'lost' && card.classList.contains('status-lost')) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        function contactSeller(email, title) {
            window.location.href = 'mailto:' + email + '?subject=Question about auction: ' + encodeURIComponent(title);
        }
    </script>
@endpush