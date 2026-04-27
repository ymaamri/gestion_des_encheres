{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auctions/active.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Actives')
@section('page-title', 'Enchères Actives')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Filters Card -->
            <div class="card my-4 shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient-theme text-white border-bottom-0 rounded-top-4 p-3">
                    <h6 class="text-white mb-0 fw-bold">
                        <i class="material-symbols-rounded me-1 align-middle">filter_alt</i> Filtrer les Enchères
                    </h6>
                </div>
                <div class="card-body bg-light rounded-bottom-4 p-4">
                    <form method="GET" action="{{ route('auctions.active') }}" id="filter-form">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-bold mb-1 small">Catégorie</label>
                                <select name="categorie" class="form-select border border-2 p-2 bg-white rounded-3" onchange="this.form.submit()">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories ?? [] as $categorie)
                                        <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-dark fw-bold mb-1 small">Prix min (MAD)</label>
                                <input type="number" name="prix_min" class="form-control border border-2 p-2 bg-white rounded-3" value="{{ request('prix_min') }}" step="100" placeholder="Min">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-dark fw-bold mb-1 small">Prix max (MAD)</label>
                                <input type="number" name="prix_max" class="form-control border border-2 p-2 bg-white rounded-3" value="{{ request('prix_max') }}" step="100" placeholder="Max">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-bold mb-1 small">État du produit</label>
                                <select name="etat" class="form-select border border-2 p-2 bg-white rounded-3" onchange="this.form.submit()">
                                    <option value="">Tous les états</option>
                                    <option value="NEUF" {{ request('etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                    <option value="TRES_BON_ETAT" {{ request('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                    <option value="BON_ETAT" {{ request('etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                    <option value="ACCEPTABLE" {{ request('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary bg-gradient-theme w-100 mb-0 py-2 shadow-sm rounded-3">
                                    <i class="material-symbols-rounded align-middle">search</i> Appliquer
                                </button>
                            </div>
                        </div>
                        @if(request()->anyFilled(['categorie', 'prix_min', 'prix_max', 'etat']))
                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <a href="{{ route('auctions.active') }}" class="btn btn-sm btn-outline-danger mb-0 px-3 py-1 rounded-3">
                                        <i class="material-symbols-rounded align-middle" style="font-size: 16px;">clear</i> Réinitialiser
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm" style="border-left: 4px solid #667eea;">
                <div class="mb-2 mb-md-0">
                    <h6 class="mb-0 text-dark fw-bold d-flex align-items-center">
                        <i class="material-symbols-rounded text-primary me-2">gavel</i> 
                        <span class="text-primary me-1">{{ $auctions->total() }}</span> enchère(s) active(s) trouvée(s)
                    </h6>
                </div>
                <div class="d-flex align-items-center">
                    <label class="mb-0 me-2 text-muted small fw-bold text-nowrap">Trier par :</label>
                    <select class="form-select form-select-sm border border-2 bg-white rounded-3" id="sortBy" onchange="window.location.href=this.value" style="min-width: 160px; cursor: pointer;">
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
                            Plus d'offres
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
                        $bidCount = $annonce->encheres()->count();
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
                        <div class="card h-100 auction-card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $firstPhoto }}" class="card-img-top w-100" style="height: 240px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                
                                <!-- Overlay Gradient for better text readability -->
                                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    @if($isEndingSoon)
                                        <span class="badge bg-warning text-dark border-0 fw-bold px-2 py-1 shadow-sm rounded-pill">
                                            <i class="material-symbols-rounded align-middle" style="font-size: 14px;">schedule</i> Bientôt fini !
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white border-0 fw-bold px-2 py-1 shadow-sm rounded-pill">
                                            <i class="material-symbols-rounded align-middle" style="font-size: 14px;">schedule</i> {{ $timeLeft->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>

                                <div class="position-absolute top-0 end-0 m-3">
                                    @if($isHot)
                                        <span class="badge bg-gradient-danger fw-bold px-2 py-1 shadow-sm rounded-pill">
                                            <i class="material-symbols-rounded align-middle" style="font-size: 14px;">local_fire_department</i> Tendance
                                        </span>
                                    @else
                                        <span class="badge bg-dark bg-opacity-75 text-white fw-bold px-2 py-1 shadow-sm rounded-pill">
                                            <i class="material-symbols-rounded align-middle" style="font-size: 14px;">gavel</i> {{ $bidCount }} offres
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-dark fw-bold mb-0" style="line-height: 1.3;">{{ Str::limit($annonce->titre, 40) }}</h5>
                                    @if($annonce->vendeur->note_moyenne >= 4)
                                        <i class="material-symbols-rounded text-warning ms-1" title="Vendeur recommandé" style="font-size: 20px;">verified</i>
                                    @endif
                                </div>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($annonce->description, 70) }}</p>

                                <div class="d-flex justify-content-between align-items-end mb-3 pb-3 border-bottom border-light">
                                    <div>
                                        <small class="text-muted d-block fw-semibold mb-1">Prix de départ</small>
                                        <span class="text-secondary fw-bold">{{ number_format($annonce->prix_depart, 2) }} MAD</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block fw-semibold mb-1">Enchère actuelle</small>
                                        <span class="text-primary fw-bold fs-5 mb-0">{{ number_format($currentBid, 2) }} MAD</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted fw-bold">Progression</small>
                                        <small class="text-primary fw-bold">{{ round($percentage) }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px; background-color: #f0f2f5; border-radius: 10px;">
                                        <div class="progress-bar bg-gradient-theme" role="progressbar" style="width: {{ $percentage }}%; border-radius: 10px;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-1">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium rounded-3">
                                        <i class="material-symbols-rounded align-middle text-muted" style="font-size: 14px;">category</i> 
                                        {{ $annonce->produit->categorie->nom ?? 'Général' }}
                                    </span>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium rounded-3">
                                        <i class="material-symbols-rounded align-middle text-muted" style="font-size: 14px;">inventory_2</i>
                                        @switch($annonce->produit->etat)
                                            @case('NEUF') Neuf @break
                                            @case('TRES_BON_ETAT') Très bon @break
                                            @case('BON_ETAT') Bon @break
                                            @default Acceptable
                                        @endswitch
                                    </span>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium rounded-3 ms-auto">
                                        <i class="material-symbols-rounded align-middle text-warning" style="font-size: 14px;">star</i>
                                        {{ number_format($annonce->vendeur->note_moyenne, 1) }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                                <div class="row g-2">
                                    <div class="col-9">
                                        <a href="{{ route('annonces.show', $annonce) }}" class="btn bg-gradient-theme w-100 mb-0 py-2 fw-bold text-white shadow-sm d-flex justify-content-center align-items-center rounded-3">
                                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">gavel</i> Placer une offre
                                        </a>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-secondary w-100 mb-0 py-2 d-flex justify-content-center align-items-center rounded-3" onclick="shareAuction({{ $annonce->id }})" title="Partager">
                                            <i class="material-symbols-rounded" style="font-size: 18px;">share</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">gavel</i>
                                <h5 class="mt-3 text-secondary">Aucune enchère active</h5>
                                <p class="text-muted mb-0">Il n'y a pas d'enchères actives pour le moment.</p>
                                <p class="text-muted">Revenez plus tard pour découvrir de nouvelles opportunités !</p>
                                <button class="btn bg-gradient-primary mt-3 rounded-3" onclick="location.reload()">
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
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-gradient-theme text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">Partager cette enchère</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="text-muted">Partagez cette enchère avec vos amis !</p>
                    <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                        <input type="text" id="shareLink" class="form-control border-0 py-3" readonly>
                        <button class="btn px-4 text-white fw-bold" onclick="copyShareLink()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="material-symbols-rounded align-middle">content_copy</i> Copier
                        </button>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="#" id="facebookShare" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center" target="_blank" style="width: 50px; height: 50px;">
                            <i class="fab fa-facebook-f fs-5"></i>
                        </a>
                        <a href="#" id="twitterShare" class="btn btn-outline-info rounded-circle d-flex align-items-center justify-content-center" target="_blank" style="width: 50px; height: 50px;">
                            <i class="fab fa-twitter fs-5"></i>
                        </a>
                        <a href="#" id="whatsappShare" class="btn btn-outline-success rounded-circle d-flex align-items-center justify-content-center" target="_blank" style="width: 50px; height: 50px;">
                            <i class="fab fa-whatsapp fs-5"></i>
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

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
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