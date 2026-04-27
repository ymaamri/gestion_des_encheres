{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Annonces')
@section('page-title', 'Mes Annonces')
@section('breadcrumb', 'Annonces')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Banner -->
        <div class="card mb-4 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="card-body py-4 px-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2" style="color: #4a5568; font-weight: 800;">
                            <i class="material-symbols-rounded me-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">inventory_2</i> 
                            Mes Annonces D'Enchères
                        </h3>
                        <p style="color: #718096; font-size: 1.1rem;" class="mb-0">
                            Gérez vos annonces, suivez les enchères et créez de nouvelles opportunités de vente.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('annonces.create') }}" class="btn-gradient d-inline-block text-decoration-none px-4 py-2">
                            <i class="material-symbols-rounded align-middle me-1">add_circle</i> Nouvelle Annonce
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        @if($annonces->count() > 0)
            @php
                $activeCount = $annonces->where('statut', 'ACTIVE')->count();
                $totalBids = $annonces->sum(function($a) { return $a->encheres()->count(); });
            @endphp
            <div class="row mb-4 g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                            <h2 class="mb-2 fw-bold" style="color: #4a5568;">{{ $annonces->total() }}</h2>
                            <p class="text-muted fw-bold text-uppercase small mb-0">Total des annonces</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                            <h2 class="mb-2 fw-bold" style="color: #4a5568;">{{ $activeCount }}</h2>
                            <p class="text-muted fw-bold text-uppercase small mb-0">Annonces actives</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                            <h2 class="mb-2 fw-bold" style="color: #4a5568;">{{ $totalBids }}</h2>
                            <p class="text-muted fw-bold text-uppercase small mb-0">Enchères reçues</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Annonces Grid -->
        <div class="card my-4 shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h5 class="mb-3 mb-md-0 fw-bold d-flex align-items-center" style="color: #4a5568;">
                    <i class="material-symbols-rounded me-2 fs-4" style="color: #667eea;">gavel</i> Liste de Mes Annonces
                </h5>
                <div style="min-width: 250px;">
                    <div class="input-group input-group-outline bg-white rounded-3 overflow-hidden shadow-sm">
                        <span class="input-group-text bg-transparent border-0 px-3">
                            <i class="material-symbols-rounded text-muted">search</i>
                        </span>
                        <input type="text" id="annonceSearch" class="form-control border-0 px-1 py-2 text-dark" placeholder="Rechercher..." style="background: transparent;">
                    </div>
                </div>
            </div>
            <div class="card-body bg-light rounded-bottom-4 p-4">
                @if($annonces->count() > 0)
                    <div class="row g-4 p-3">
                        @foreach($annonces as $annonce)
                            @php
                                $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                                $firstPhoto = $images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image';
                                $currentPrice = $annonce->getMontantActuel();
                                $priceIncrease = $currentPrice - $annonce->prix_depart;
                                $increasePercent = $annonce->prix_depart > 0 ? round(($priceIncrease / $annonce->prix_depart) * 100) : 0;
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 annonce-card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="position-relative">
                                        <img src="{{ $firstPhoto }}" class="card-img-top w-100" style="height: 240px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                        
                                        <!-- Gradient overlay -->
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                            @if($increasePercent > 0)
                                                <span class="badge border-0 fw-bold px-2 py-1 shadow-sm rounded-pill" style="background: rgba(72, 187, 120, 0.9); color: white;">
                                                    <i class="material-symbols-rounded align-middle" style="font-size: 14px;">trending_up</i> +{{ $increasePercent }}%
                                                </span>
                                            @endif
                                        </div>

                                        <div class="position-absolute top-0 end-0 m-3">
                                            @switch($annonce->statut)
                                                @case('ACTIVE')
                                                    <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(72, 187, 120, 0.95); color: white;">
                                                        <i class="material-symbols-rounded align-middle me-1">check_circle</i> ACTIVE
                                                    </span>
                                                    @break
                                                @case('EN_ATTENTE')
                                                    <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(237, 137, 54, 0.95); color: white;">
                                                        <i class="material-symbols-rounded align-middle me-1">pending</i> EN ATTENTE
                                                    </span>
                                                    @break
                                                @case('CLOTUREE')
                                                    <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(113, 128, 150, 0.95); color: white;">
                                                        <i class="material-symbols-rounded align-middle me-1">lock</i> CLÔTURÉE
                                                    </span>
                                                    @break
                                                @case('BLOQUEE')
                                                    <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(245, 101, 101, 0.95); color: white;">
                                                        <i class="material-symbols-rounded align-middle me-1">block</i> BLOQUÉE
                                                    </span>
                                                    @break
                                                @case('ANNULEE')
                                                    <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(160, 174, 192, 0.95); color: white;">
                                                        <i class="material-symbols-rounded align-middle me-1">cancel</i> ANNULÉE
                                                    </span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                    
                                    <div class="card-body p-4">
                                        <h5 class="card-title text-dark fw-bold mb-2" style="line-height: 1.3;">{{ Str::limit($annonce->titre, 40) }}</h5>
                                        <p class="card-text text-muted small mb-4">{{ Str::limit($annonce->description, 70) }}</p>
                                        
                                        <div class="d-flex justify-content-between align-items-end mb-3 pb-3 border-bottom border-light">
                                            <div>
                                                <small class="text-muted d-block fw-semibold mb-1">Prix de départ</small>
                                                <span class="text-secondary fw-bold">{{ number_format($annonce->prix_depart, 2) }} MAD</span>
                                            </div>
                                            <div class="text-end">
                                                <small class="d-block fw-bold mb-1" style="color: #667eea;">Prix actuel</small>
                                                <span class="fw-bold fs-5 mb-0" style="color: #667eea;">{{ number_format($currentPrice, 2) }} MAD</span>
                                            </div>
                                        </div>

                                        @if($annonce->encheres()->count() > 0)
                                            <div class="alert py-2 mb-3 px-3 d-flex align-items-center rounded-3 border-0 fw-semibold" style="background: #e6f2ff; color: #0066cc;">
                                                <i class="material-symbols-rounded me-2" style="font-size: 20px;">gavel</i>
                                                {{ $annonce->encheres()->count() }} enchère(s) reçue(s)
                                            </div>
                                        @else
                                            <div class="alert py-2 mb-3 px-3 d-flex align-items-center rounded-3 border-0 fw-semibold" style="background: #fff3cd; color: #856404;">
                                                <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                                                Aucune enchère pour le moment
                                            </div>
                                        @endif

                                        <div class="d-flex flex-wrap gap-2 mb-1">
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-medium">
                                                <i class="material-symbols-rounded align-middle text-muted" style="font-size: 14px;">category</i> 
                                                {{ $annonce->produit->categorie->nom ?? 'Général' }}
                                            </span>
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-medium">
                                                <i class="material-symbols-rounded align-middle text-muted" style="font-size: 14px;">inventory_2</i>
                                                @switch($annonce->produit->etat)
                                                    @case('NEUF') Neuf @break
                                                    @case('TRES_BON_ETAT') Très bon @break
                                                    @case('BON_ETAT') Bon @break
                                                    @default Acceptable
                                                @endswitch
                                            </span>
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-medium ms-auto">
                                                <i class="material-symbols-rounded align-middle" style="font-size: 14px; color: #667eea;">schedule</i>
                                                {{ \Carbon\Carbon::parse($annonce->date_fin)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <a href="{{ route('annonces.show', $annonce) }}" class="btn-gradient w-100 mb-0 py-2 d-flex justify-content-center align-items-center text-white" style="border-radius: 12px; text-decoration: none;">
                                                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">visibility</i> Voir
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <div class="dropdown w-100">
                                                    <button class="btn-outline-gradient w-100 mb-0 py-2 fw-bold d-flex justify-content-center align-items-center bg-white" style="border-radius: 12px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">more_horiz</i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                                        @if($annonce->encheres()->count() == 0 && !in_array($annonce->statut, ['CLOTUREE', 'BLOQUEE', 'ANNULEE']))
                                                        <li>
                                                            <a class="dropdown-item border-radius-md" href="{{ route('annonces.edit', $annonce) }}">
                                                                <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item border-radius-md text-danger">
                                                                    <i class="material-symbols-rounded me-2">delete</i> Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endif
                                                        @if($annonce->statut === 'ACTIVE')
                                                        <li>
                                                            <a class="dropdown-item border-radius-md" href="#" onclick="shareAuction({{ $annonce->id }})">
                                                                <i class="material-symbols-rounded me-2">share</i> Partager
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($annonces->hasPages())
                    <div class="px-3 pt-3">
                        {{ $annonces->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">inventory_2</i>
                        <h5 class="mt-3 text-secondary">Aucune annonce trouvée</h5>
                        <p class="text-muted mb-0">Vous n'avez pas encore créé d'annonces.</p>
                        <p class="text-muted">Commencez à vendre vos produits dès maintenant !</p>
                        <div class="mt-4">
                            <a href="{{ route('annonces.create') }}" class="btn-gradient d-inline-block text-decoration-none">
                                <i class="material-symbols-rounded align-middle me-1">add_circle</i> Créer votre première annonce
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tips for Sellers -->
        @if($annonces->count() > 0)
            <div class="card mt-4 border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="material-symbols-rounded me-2 text-warning fs-4">tips_and_updates</i> Conseils pour réussir vos ventes
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                    <i class="material-symbols-rounded" style="color: #667eea;">photo_camera</i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #4a5568;">Photos de qualité</h6>
                                    <small class="text-muted d-block lh-sm">Les annonces avec photos ont 85% plus de chances de vendre</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(118, 75, 162, 0.1);">
                                    <i class="material-symbols-rounded" style="color: #764ba2;">description</i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #4a5568;">Description détaillée</h6>
                                    <small class="text-muted d-block lh-sm">Décrivez précisément l'état et les caractéristiques du produit</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(79, 209, 197, 0.1);">
                                    <i class="material-symbols-rounded" style="color: #4fd1c5;">sell</i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #4a5568;">Prix compétitif</h6>
                                    <small class="text-muted d-block lh-sm">Fixez un prix de départ attractif pour attirer plus d'enchérisseurs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4 border-light">
                    <div class="border-0 shadow-sm d-flex align-items-center mb-0 px-4 py-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #667eea !important;">
                        <i class="material-symbols-rounded me-3 fs-4" style="color: #667eea;">info</i>
                        <div class="text-dark">
                            <strong class="d-block mb-1" style="color: #4a5568;">Conseil d'expert :</strong>
                            <span class="text-muted">Répondez rapidement aux questions des acheteurs potentiels pour augmenter vos chances de vente !</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pt-4 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">Partager cette annonce</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="text-muted">Partagez cette annonce avec vos amis !</p>
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
    .annonce-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .annonce-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-img-top {
        transition: transform 0.5s ease;
    }

    .annonce-card:hover .card-img-top {
        transform: scale(1.05);
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
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-outline-gradient {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-gradient:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
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
        document.getElementById('twitterShare').href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(shareUrl) + '&text=Découvrez cette enchère exceptionnelle !';
        document.getElementById('whatsappShare').href = 'https://wa.me/?text=' + encodeURIComponent('Découvrez cette enchère exceptionnelle : ' + shareUrl);
        
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
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="material-symbols-rounded align-middle">check</i> Copié !';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    }
    
    // Search functionality
    document.getElementById('annonceSearch')?.addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let cards = document.querySelectorAll('.annonce-card');
        
        cards.forEach(card => {
            let text = card.textContent.toLowerCase();
            card.closest('.col-md-6, .col-lg-4').style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush