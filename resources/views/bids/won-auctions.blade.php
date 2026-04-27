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
                <div class="card mb-4 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="card-body py-4 px-5">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2" style="color: #4a5568; font-weight: 800;">
                                    <i class="material-symbols-rounded me-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">emoji_events</i> 
                                    Félicitations !
                                </h3>
                                <p style="color: #718096; font-size: 1.1rem;" class="mb-0">
                                    Vous avez remporté {{ $wonAuctions->count() }} enchère(s). Contactez les vendeurs pour finaliser vos achats.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="material-symbols-rounded" style="font-size: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; opacity: 0.8;">celebration</i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Summary -->
            @if($wonAuctions->count() > 0)
                <div class="row mb-4 g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                                <h2 class="mb-2 fw-bold" style="color: #4a5568;">{{ $wonAuctions->count() }}</h2>
                                <p class="text-muted fw-bold text-uppercase small mb-0">Enchères gagnées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                                <h2 class="mb-2 fw-bold" style="color: #4a5568;">
                                    {{ number_format($wonAuctions->sum(function($mise) { return $mise->montant; }), 2) }} MAD
                                </h2>
                                <p class="text-muted fw-bold text-uppercase small mb-0">Montant total investi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                                <h2 class="mb-2 fw-bold" style="color: #4a5568;">{{ number_format($wonAuctions->avg(function($mise) { return $mise->montant; }), 2) }} MAD</h2>
                                <p class="text-muted fw-bold text-uppercase small mb-0">Moyenne par enchère</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Won Auctions Grid -->
            <div class="card my-4 shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <h5 class="mb-3 mb-md-0 fw-bold d-flex align-items-center" style="color: #4a5568;">
                        <i class="material-symbols-rounded me-2 fs-4" style="color: #f6ad55;">emoji_events</i> Mes Enchères Gagnées
                    </h5>
                    <div style="min-width: 250px;">
                        <div class="input-group input-group-outline bg-white rounded-3 overflow-hidden shadow-sm">
                            <span class="input-group-text bg-transparent border-0 px-3">
                                <i class="material-symbols-rounded text-muted">search</i>
                            </span>
                            <input type="text" id="wonSearch" class="form-control border-0 px-1 py-2 text-dark" placeholder="Rechercher..." style="background: transparent;">
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light rounded-bottom-4 p-4">
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
                                    <div class="card h-100 won-card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="position-relative">
                                            <img src="{{ $firstPhoto }}" class="card-img-top w-100" style="height: 240px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                            
                                            <!-- Gradient overlay -->
                                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                                @if($savedPercent > 0)
                                                    <span class="badge border-0 fw-bold px-2 py-1 shadow-sm rounded-pill" style="background: rgba(245, 101, 101, 0.9); color: white;">
                                                        <i class="material-symbols-rounded align-middle" style="font-size: 14px;">savings</i> -{{ $savedPercent }}%
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge fw-bold px-3 py-2 shadow-sm rounded-pill fs-6 border border-2 border-white" style="background: rgba(255, 255, 255, 0.95); color: #38a169;">
                                                    <i class="material-symbols-rounded align-middle me-1">emoji_events</i> GAGNÉE
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body p-4">
                                            <h5 class="card-title text-dark fw-bold mb-2" style="line-height: 1.3;">{{ Str::limit($annonce->titre, 40) }}</h5>
                                            <p class="card-text text-muted small mb-4">{{ Str::limit($annonce->description, 70) }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-end mb-3 pb-3 border-bottom border-light">
                                                <div>
                                                    <small class="text-muted d-block fw-semibold mb-1">Prix de départ</small>
                                                    <span class="text-secondary fw-bold text-decoration-line-through">{{ number_format($annonce->prix_depart, 2) }} MAD</span>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-success d-block fw-bold mb-1">Votre offre gagnante</small>
                                                    <span class="text-success fw-bold fs-5 mb-0">{{ number_format($mise->montant, 2) }} MAD</span>
                                                </div>
                                            </div>

                                            @if($savedAmount > 0)
                                                <div class="alert py-2 mb-3 px-3 d-flex align-items-center rounded-3 border-0 fw-semibold" style="background: #f0fdf4; color: #166534;">
                                                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">savings</i>
                                                    Vous avez économisé {{ number_format($savedAmount, 2) }} MAD !
                                                </div>
                                            @endif

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
                                                <div class="col-8">
                                                    <a href="{{ route('annonces.show', $annonce) }}" class="btn-gradient w-100 mb-0 py-2 d-flex justify-content-center align-items-center text-white rounded-3" style="text-decoration: none;">
                                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">visibility</i> Voir l'annonce
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn-outline-gradient w-100 mb-0 py-2 fw-bold d-flex justify-content-center align-items-center bg-white rounded-3" onclick="contactSeller('{{ $annonce->vendeur->client->user->email }}', '{{ addslashes($annonce->titre) }}')" title="Contacter le vendeur">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">mail</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination (if applicable) -->
                        @if(method_exists($wonAuctions, 'links'))
                            <div class="px-3 pt-3">
                                {{ $wonAuctions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">emoji_events</i>
                            <h5 class="mt-3 text-secondary">Aucune enchère gagnée</h5>
                            <p class="text-muted mb-0">Vous n'avez pas encore gagné d'enchères.</p>
                            <p class="text-muted">Participez aux enchères actives pour avoir une chance de gagner !</p>
                            <div class="mt-4">
                                <a href="{{ route('auctions.active') }}" class="btn-gradient d-inline-block text-decoration-none rounded-3">
                                    <i class="material-symbols-rounded align-middle me-1">gavel</i> Explorer les enchères actives
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips for Winners -->
            @if($wonAuctions->count() > 0)
                <div class="card mt-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="material-symbols-rounded me-2 text-warning fs-4">tips_and_updates</i> Prochaines étapes
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                    <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                        <i class="material-symbols-rounded" style="color: #667eea;">mail</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: #4a5568;">1. Contactez le vendeur</h6>
                                        <small class="text-muted d-block lh-sm">Utilisez le bouton email pour contacter le vendeur et organiser la livraison</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                    <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(118, 75, 162, 0.1);">
                                        <i class="material-symbols-rounded" style="color: #764ba2;">payments</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: #4a5568;">2. Finalisez le paiement</h6>
                                        <small class="text-muted d-block lh-sm">Convenez du mode de paiement avec le vendeur</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex p-3 rounded-3 border h-100 align-items-center" style="background: #f8f9fa;">
                                    <div class="icon icon-shape rounded-circle shadow-sm me-3 flex-shrink-0 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: rgba(79, 209, 197, 0.1);">
                                        <i class="material-symbols-rounded" style="color: #4fd1c5;">local_shipping</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: #4a5568;">3. Recevez votre produit</h6>
                                        <small class="text-muted d-block lh-sm">Organisez la livraison ou la remise en main propre</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4 border-light">
                        <div class="border-0 shadow-sm d-flex align-items-center mb-0 px-4 py-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #667eea !important;">
                            <i class="material-symbols-rounded me-3 fs-4" style="color: #667eea;">info</i>
                            <div class="text-dark">
                                <strong class="d-block mb-1" style="color: #4a5568;">Conseil d'expert :</strong>
                                <span class="text-muted">Laissez un avis au vendeur après avoir reçu votre produit pour l'aider à améliorer ses services !</span>
                            </div>
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
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    .btn-outline-gradient {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        font-weight: 600;
        transition: all 0.2s ease;
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