{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auctions/active.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Actives')
@section('page-title', 'Enchères Actives')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Filters Card -->
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="material-symbols-rounded me-1">filter_alt</i> Filtrer les Enchères
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('auctions.active') }}" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Catégorie</label>
                                <select name="categorie" class="form-control" onchange="this.form.submit()">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories ?? [] as $categorie)
                                        <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Prix min (MAD)</label>
                                <input type="number" name="prix_min" class="form-control" value="{{ request('prix_min') }}" step="100" placeholder="Min">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Prix max (MAD)</label>
                                <input type="number" name="prix_max" class="form-control" value="{{ request('prix_max') }}" step="100" placeholder="Max">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">État du produit</label>
                                <select name="etat" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tous les états</option>
                                    <option value="NEUF" {{ request('etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                    <option value="TRES_BON_ETAT" {{ request('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                    <option value="BON_ETAT" {{ request('etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                    <option value="ACCEPTABLE" {{ request('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn bg-gradient-dark w-100">
                                    <i class="material-symbols-rounded">search</i> Appliquer
                                </button>
                            </div>
                        </div>
                        @if(request()->anyFilled(['categorie', 'prix_min', 'prix_max', 'etat']))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('auctions.active') }}" class="text-danger text-sm">
                                        <i class="material-symbols-rounded">clear</i> Réinitialiser les filtres
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="mb-0">
                        <i class="material-symbols-rounded">gavel</i> 
                        {{ $auctions->total() }} enchère(s) active(s) trouvée(s)
                    </h6>
                </div>
                <div>
                    <select class="form-control form-control-sm" id="sortBy" onchange="window.location.href=this.value">
                        <option value="{{ route('auctions.active', array_merge(request()->query(), ['sort' => 'recent'])) }}" {{ request('sort') == 'recent' ? 'selected' : '' }}>
                            Plus récentes
                        </option>
                        <option value="{{ route('auctions.active', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Prix croissant
                        </option>
                        <option value="{{ route('auctions.active', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Prix décroissant
                        </option>
                        <option value="{{ route('auctions.active', array_merge(request()->query(), ['sort' => 'ending_soon'])) }}" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>
                            Fin imminente
                        </option>
                        <option value="{{ route('auctions.active', array_merge(request()->query(), ['sort' => 'most_bids'])) }}" {{ request('sort') == 'most_bids' ? 'selected' : '' }}>
                            Plus d'enchères
                        </option>
                    </select>
                </div>
            </div>

            <!-- Active Auctions Grid -->
            <div class="row">
                @forelse($auctions as $annonce)
                    @php
                        $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                        $firstPhoto = $images[0] ?? 'https://via.placeholder.com/300x200?text=No+Image';
                        $currentBid = $annonce->getMontantActuel();
                        $bidCount = $annonce->mises()->count();
                        $timeLeft = \Carbon\Carbon::parse($annonce->date_fin);
                        $now = \Carbon\Carbon::now();
                        $isEndingSoon = $timeLeft->diffInHours($now) <= 24;
                        $isHot = $bidCount > 10;
                        $percentage = 0;
                        $start = strtotime($annonce->date_debut);
                        $end = strtotime($annonce->date_fin);
                        $current = time();
                        if ($end > $start) {
                            $percentage = min(100, max(0, (($current - $start) / ($end - $start)) * 100));
                        }
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 auction-card">
                            <div class="position-relative">
                                <img src="{{ $firstPhoto }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($isHot)
                                        <span class="badge bg-gradient-danger">
                                            <i class="material-symbols-rounded" style="font-size: 14px;">local_fire_department</i> Tendance
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-dark">
                                            <i class="material-symbols-rounded" style="font-size: 14px;">gavel</i> {{ $bidCount }}
                                        </span>
                                    @endif
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    @if($isEndingSoon)
                                        <span class="badge bg-gradient-warning">
                                            <i class="material-symbols-rounded" style="font-size: 14px;">schedule</i> Bientôt fini !
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-info">
                                            <i class="material-symbols-rounded" style="font-size: 14px;">schedule</i> {{ $timeLeft->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ Str::limit($annonce->titre, 45) }}</h5>
                                    @if($annonce->vendeur->note_moyenne >= 4)
                                        <i class="material-symbols-rounded text-warning">verified</i>
                                    @endif
                                </div>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($annonce->description, 80) }}</p>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Prix de départ</small>
                                        <strong class="text-secondary">{{ number_format($annonce->prix_depart, 2) }} MAD</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Enchère actuelle</small>
                                        <strong class="text-primary h5 mb-0">{{ number_format($currentBid, 2) }} MAD</strong>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Progression</small>
                                        <small class="text-muted">{{ round($percentage) }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-gradient-info" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-gradient-secondary">
                                            <i class="material-symbols-rounded" style="font-size: 12px;">category</i> 
                                            {{ $annonce->produit->categorie->nom ?? 'Général' }}
                                        </span>
                                        <span class="badge bg-gradient-dark ms-1">
                                            <i class="material-symbols-rounded" style="font-size: 12px;">inventory_2</i>
                                            @switch($annonce->produit->etat)
                                                @case('NEUF') Neuf @break
                                                @case('TRES_BON_ETAT') Très bon @break
                                                @case('BON_ETAT') Bon @break
                                                @default Acceptable
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="text-warning small">
                                        <i class="material-symbols-rounded" style="font-size: 14px;">star</i>
                                        {{ number_format($annonce->vendeur->note_moyenne, 1) }}
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <div class="row g-2">
                                    <div class="col-8">
                                        <a href="{{ route('annonces.show', $annonce) }}" class="btn bg-gradient-primary w-100">
                                            <i class="material-symbols-rounded">gavel</i> Participer
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-outline-secondary w-100" onclick="shareAuction({{ $annonce->id }})">
                                            <i class="material-symbols-rounded">share</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">gavel</i>
                                <h5 class="mt-3">Aucune enchère active</h5>
                                <p class="text-muted mb-0">Il n'y a pas d'enchères actives pour le moment.</p>
                                <p class="text-muted">Revenez plus tard pour découvrir de nouvelles opportunités !</p>
                                <button class="btn bg-gradient-primary mt-3" onclick="location.reload()">
                                    <i class="material-symbols-rounded">refresh</i> Actualiser
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($auctions->hasPages())
                <div class="mt-4">
                    {{ $auctions->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-dark text-white">
                    <h5 class="modal-title">Partager cette enchère</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Partagez cette enchère avec vos amis !</p>
                    <div class="input-group mb-3">
                        <input type="text" id="shareLink" class="form-control" readonly>
                        <button class="btn btn-primary" onclick="copyShareLink()">
                            <i class="material-symbols-rounded">content_copy</i> Copier
                        </button>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" id="facebookShare" class="btn btn-outline-primary" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" id="twitterShare" class="btn btn-outline-info" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" id="whatsappShare" class="btn btn-outline-success" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .auction-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .card-img-top {
            transition: transform 0.5s ease;
        }

        .auction-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
        }

        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }
    </style>
@endpush

@push('scripts')
<script>
    let currentAuctionId = null;
    
    function shareAuction(auctionId) {
        currentAuctionId = auctionId;
        const shareUrl = window.location.origin + '/annonces/' + auctionId;
        document.getElementById('shareLink').value = shareUrl;
        
        // Update social media share links
        document.getElementById('facebookShare').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl);
        document.getElementById('twitterShare').href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(shareUrl) + '&text=Découvrez cette enchère exceptionnelle sur BidMaster !';
        document.getElementById('whatsappShare').href = 'https://wa.me/?text=' + encodeURIComponent('Découvrez cette enchère exceptionnelle sur BidMaster : ' + shareUrl);
        
        // Show modal
        var myModal = new bootstrap.Modal(document.getElementById('shareModal'));
        myModal.show();
    }
    
    function copyShareLink() {
        const shareLink = document.getElementById('shareLink');
        shareLink.select();
        shareLink.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show feedback
        const button = event.target.closest('button');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="material-symbols-rounded">check</i> Copié !';
        setTimeout(() => {
            button.innerHTML = originalHtml;
        }, 2000);
    }
    
    // Auto-refresh auctions every 60 seconds
    let autoRefresh = setInterval(function() {
        if (!document.hidden) {
            location.reload();
        }
    }, 60000);
    
    // Stop auto-refresh when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(autoRefresh);
        } else {
            autoRefresh = setInterval(function() {
                location.reload();
            }, 60000);
        }
    });
</script>
@endpush