{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/won-auctions.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Gagnées')
@section('page-title', 'Enchères Gagnées')
@section('breadcrumb', 'Enchères Gagnées')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Celebration Banner -->
            @if($wonAuctions->count() > 0)
                <div class="card bg-gradient-success mb-4">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="text-white mb-2">
                                    <i class="material-symbols-rounded me-2">emoji_events</i> 
                                    Félicitations !
                                </h3>
                                <p class="text-white opacity-9 mb-0">
                                    Vous avez remporté {{ $wonAuctions->total() }} enchère(s). Contactez les vendeurs pour finaliser vos achats.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="material-symbols-rounded text-white" style="font-size: 80px;">celebration</i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Summary -->
            @if($wonAuctions->count() > 0)
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="text-success mb-0">{{ $wonAuctions->total() }}</h2>
                                <p class="text-muted mb-0">Enchères gagnées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="text-primary mb-0">
                                    {{ number_format($wonAuctions->sum(function($mise) { return $mise->montant; }), 2) }} MAD
                                </h2>
                                <p class="text-muted mb-0">Montant total gagné</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="text-info mb-0">{{ number_format($wonAuctions->avg(function($mise) { return $mise->montant; }), 2) }} MAD</h2>
                                <p class="text-muted mb-0">Moyenne par enchère</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Won Auctions Grid -->
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="material-symbols-rounded me-1">emoji_events</i> Mes Enchères Gagnées 🏆
                        </h6>
                        <div class="me-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text text-body">
                                    <i class="material-symbols-rounded">search</i>
                                </span>
                                <input type="text" id="wonSearch" class="form-control" placeholder="Rechercher...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if($wonAuctions->count() > 0)
                        <div class="row g-4 p-3">
                            @foreach($wonAuctions as $mise)
                                @php
                                    $annonce = $mise->annonce;
                                    $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                                    $firstPhoto = $images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image';
                                    $savedAmount = $annonce->prix_depart - $mise->montant;
                                    $savedPercent = $annonce->prix_depart > 0 ? round(($savedAmount / $annonce->prix_depart) * 100) : 0;
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 won-card">
                                        <div class="position-relative">
                                            <img src="{{ $firstPhoto }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-gradient-success">
                                                    <i class="material-symbols-rounded" style="font-size: 14px;">emoji_events</i> Gagnée !
                                                </span>
                                            </div>
                                            @if($savedPercent > 0)
                                                <div class="position-absolute bottom-0 start-0 m-2">
                                                    <span class="badge bg-gradient-danger">
                                                        <i class="material-symbols-rounded" style="font-size: 14px;">savings</i> -{{ $savedPercent }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title mb-2">{{ Str::limit($annonce->titre, 50) }}</h5>
                                            <p class="card-text text-muted small mb-3">{{ Str::limit($annonce->description, 80) }}</p>
                                            
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Prix de départ</small>
                                                    <strong class="text-secondary">{{ number_format($annonce->prix_depart, 2) }} MAD</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <small class="text-muted d-block">Prix gagnant</small>
                                                    <strong class="text-success h5 mb-0">{{ number_format($mise->montant, 2) }} MAD</strong>
                                                </div>
                                            </div>

                                            @if($savedAmount > 0)
                                                <div class="alert alert-success py-2 mb-3">
                                                    <i class="material-symbols-rounded" style="font-size: 16px;">savings</i>
                                                    Économie : {{ number_format($savedAmount, 2) }} MAD ({{ $savedPercent }}%)
                                                </div>
                                            @endif

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
                                                <div class="col-7">
                                                    <a href="{{ route('annonces.show', $annonce) }}" class="btn bg-gradient-info w-100">
                                                        <i class="material-symbols-rounded">visibility</i> Voir
                                                    </a>
                                                </div>
                                                <div class="col-5">
                                                    <button class="btn bg-gradient-success w-100" onclick="contactSeller('{{ $annonce->vendeur->client->user->email }}', '{{ addslashes($annonce->titre) }}')">
                                                        <i class="material-symbols-rounded">mail</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-3 pt-3">
                            {{ $wonAuctions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">emoji_events</i>
                            <h5 class="mt-3 text-secondary">Aucune enchère gagnée</h5>
                            <p class="text-muted mb-0">Vous n'avez pas encore gagné d'enchères.</p>
                            <p class="text-muted">Participez aux enchères actives pour avoir une chance de gagner !</p>
                            <div class="mt-4">
                                <a href="{{ route('auctions.active') }}" class="btn bg-gradient-primary">
                                    <i class="material-symbols-rounded">gavel</i> Explorer les enchères actives
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips for Winners -->
            @if($wonAuctions->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="material-symbols-rounded me-1">tips_and_updates</i> Prochaines étapes</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-success rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">mail</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">1. Contactez le vendeur</h6>
                                        <small class="text-muted">Utilisez le bouton email pour contacter le vendeur et organiser la livraison</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-info rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">payments</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">2. Finalisez le paiement</h6>
                                        <small class="text-muted">Convenez du mode de paiement avec le vendeur</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-warning rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">local_shipping</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">3. Recevez votre produit</h6>
                                        <small class="text-muted">Organisez la livraison ou la remise en main propre</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="alert alert-info mb-0">
                            <i class="material-symbols-rounded">info</i>
                            <strong>Conseil :</strong> Laissez un avis au vendeur après avoir reçu votre produit pour l'aider à améliorer ses services !
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .won-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .won-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .card-img-top {
        transition: transform 0.5s ease;
    }

    .won-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Search functionality
    document.getElementById('wonSearch')?.addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let cards = document.querySelectorAll('.won-card');
        
        cards.forEach(card => {
            let text = card.textContent.toLowerCase();
            card.closest('.col-md-6, .col-lg-4').style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    function contactSeller(email, title) {
        if (confirm(`Souhaitez-vous contacter le vendeur pour finaliser votre achat "${title}" ?`)) {
            window.location.href = 'mailto:' + email + '?subject=Félicitations ! J\'ai gagné votre enchère : ' + encodeURIComponent(title) + '&body=Bonjour,%0D%0A%0D%0AJ\'ai remporté votre enchère pour le produit : ' + encodeURIComponent(title) + '.%0D%0A%0D%0AJe souhaiterais finaliser cette transaction. Merci de me contacter pour organiser le paiement et la livraison.%0D%0A%0D%0ACordialement.';
        }
    }
    
    // Confetti animation for winners
    @if($wonAuctions->count() > 0 && session('show_confetti', true))
        function showConfetti() {
            const duration = 3 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 1000 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
            }, 250);
        }
        
        // Load confetti library and show animation
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1';
        script.onload = function() {
            showConfetti();
        };
        document.head.appendChild(script);
        
        // Only show confetti once per session
        @php
            session(['show_confetti' => false]);
        @endphp
    @endif
</script>
@endpush