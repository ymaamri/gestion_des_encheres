{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/show.blade.php --}}
@extends('layouts.app')

@section('title', $annonce->titre)
@section('page-title', $annonce->titre)
@section('breadcrumb', 'Détails de l\'Enchère')

@section('content')
@php
    // Recalcul des variables nécessaires (elles peuvent ne pas être injectées par le contrôleur)
    $bids = $annonce->encheres()->with('client.user')->latest()->get();
    $currentHighestBid = $annonce->getMontantActuel();
    $userBid = null;
    if(auth()->check() && auth()->user()->client) {
        $userBid = $annonce->getUserBid(auth()->user()->id);
    }
@endphp

<div class="auction-showcase">
    {{-- Fil d'Ariane stylisé maison (optionnel) --}}
    <div class="breadcrumb-custom mb-4 d-flex align-items-center">
        <i class="fas fa-gavel me-2 text-primary"></i>
        <span class="text-muted">Enchère</span>
        <i class="fas fa-chevron-right mx-2 text-muted small"></i>
        <span class="fw-bold text-dark">{{ Str::limit($annonce->titre, 40) }}</span>
        <span class="ms-auto badge bg-gradient-theme text-white">{{ $annonce->statut }}</span>
    </div>

    <div class="row g-4">
        <!-- Colonne Gauche : Médias + Détails -->
        <div class="col-lg-8">
            <!-- Galerie d'images améliorée -->
            <div class="card-custom p-4 mb-4">
                @php
                    $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                @endphp
                <div class="position-relative gallery-container">
                    @if(count($images) > 1)
                        <div id="auctionCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($images as $index => $image)
                                    <button type="button" data-bs-target="#auctionCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner rounded-4 overflow-hidden">
                                @foreach($images as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ $image }}" class="d-block w-100" style="height: 450px; object-fit: contain; background: #f8f9fa;" alt="Image {{ $index + 1 }}">
                                        <div class="carousel-caption d-none d-md-block">
                                            <span class="badge bg-gradient-theme">Image {{ $index + 1 }}/{{ count($images) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#auctionCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-gradient-theme rounded-circle p-4" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#auctionCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-gradient-theme rounded-circle p-4" aria-hidden="true"></span>
                            </button>
                        </div>
                    @elseif(count($images) == 1)
                        <img src="{{ $images[0] }}" class="img-fluid rounded-4 w-100" style="max-height: 450px; object-fit: contain;" alt="Image produit">
                    @else
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="mt-3">Aucune image disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Miniatures (s’affiche uniquement si plusieurs) -->
                @if(count($images) > 1)
                <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                    @foreach($images as $index => $image)
                        <img src="{{ $image }}" data-bs-target="#auctionCarousel" data-bs-slide-to="{{ $index }}" class="img-thumbnail rounded-3 cursor-pointer" style="width: 70px; height: 50px; object-fit: cover; border: 2px solid {{ $index==0 ? '#667eea' : '#dee2e6' }};" alt="minia">
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Détails dans un accordéon stylé -->
            <div class="card-custom p-4 mb-4">
                <ul class="nav nav-pills mb-4" id="auctionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="desc-tab" data-bs-toggle="pill" data-bs-target="#desc" type="button"><i class="fas fa-align-left me-1"></i> Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="pill" data-bs-target="#specs" type="button"><i class="fas fa-clipboard-list me-1"></i> Spécifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seller-tab" data-bs-toggle="pill" data-bs-target="#seller" type="button"><i class="fas fa-user-circle me-1"></i> Vendeur</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="desc">
                        <h5 class="fw-bold mb-3">À propos de l'annonce</h5>
                        <p>{{ $annonce->description ?: 'Aucune description fournie.' }}</p>
                        @if($annonce->produit->description)
                            <h5 class="fw-bold mt-4 mb-3">Description du produit</h5>
                            <p>{{ $annonce->produit->description }}</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="specs">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span class="text-muted"><i class="fas fa-tag me-2 text-primary"></i> Marque :</span>
                                    <strong>{{ $annonce->produit->marque ?? 'Non spécifiée' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted"><i class="fas fa-cube me-2 text-primary"></i> Modèle :</span>
                                    <strong>{{ $annonce->produit->modele ?? 'Non spécifié' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted"><i class="fas fa-layer-group me-2 text-primary"></i> Catégorie :</span>
                                    <strong>{{ $annonce->produit->sousCategorie->categorie->nom ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span class="text-muted"><i class="fas fa-star me-2 text-primary"></i> État :</span>
                                    <span class="badge bg-gradient-theme">
                                        @switch($annonce->produit->etat)
                                            @case('NEUF') Neuf @break
                                            @case('TRES_BON_ETAT') Très bon état @break
                                            @case('BON_ETAT') Bon état @break
                                            @case('ACCEPTABLE') Acceptable @break
                                            @default {{ $annonce->produit->etat }}
                                        @endswitch
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted"><i class="fas fa-coins me-2 text-primary"></i> Prix de départ :</span>
                                    <strong>{{ number_format($annonce->prix_depart, 2) }} TND</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="seller">
                        <div class="d-flex align-items-center mb-4">
                            <div class="seller-avatar">
                                {{ strtoupper(substr($annonce->vendeur->client->nom, 0, 1) . substr($annonce->vendeur->client->prenom, 0, 1)) }}
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0">{{ $annonce->vendeur->client->nom }} {{ $annonce->vendeur->client->prenom }}</h5>
                                <small class="text-muted">Membre depuis {{ $annonce->vendeur->created_at->format('F Y') }}</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded-4 p-3 text-center">
                                    <h3 class="mb-0 fw-bold text-primary">{{ $annonce->vendeur->nombre_ventes }}</h3>
                                    <small>Ventes</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-4 p-3 text-center">
                                    <h3 class="mb-0 fw-bold text-warning">{{ number_format($annonce->vendeur->note_moyenne, 1) }}</h3>
                                    <small>Note</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-warning">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star{{ $i <= round($annonce->vendeur->note_moyenne) ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des enchères modernisé -->
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-history text-primary me-2"></i> Historique des offres</h5>
                    <span class="badge bg-gradient-theme">{{ $bids->count() }} enchère(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-light rounded-3">
                            <tr>
                                <th>Enchérisseur</th>
                                <th class="text-end">Montant</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bids as $enchere)
                            <tr class="{{ $loop->first && $annonce->statut == 'ACTIVE' ? 'table-primary' : '' }}">
                                <td>
                                    @if($enchere->client)
                                        {{ $enchere->client->nom }} {{ $enchere->client->prenom }}
                                        @if($loop->first && $annonce->statut == 'ACTIVE')
                                            <span class="badge bg-gradient-theme ms-1">Leader</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Utilisateur supprimé</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">{{ number_format($enchere->montant, 2) }} TND</td>
                                <td class="text-end text-muted small">{{ \Carbon\Carbon::parse($enchere->date_mise)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-gavel fa-2x text-muted mb-2"></i>
                                    <p>Aucune enchère pour le moment. Soyez le premier !</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Carte d'enchère interactive -->
        <div class="col-lg-4">
            <div class="card-custom sticky-top" style="top: 90px;">
                <div class="card-body p-4">
                    <!-- Prix actuel -->
                    <div class="text-center mb-4">
                        <small class="text-muted text-uppercase fw-bold">Enchère actuelle</small>
                        <h2 class="fw-bold text-gradient">{{ number_format($currentHighestBid, 2) }} TND</h2>
                        @if($annonce->prix_depart < $currentHighestBid)
                            <small class="text-muted">Départ : {{ number_format($annonce->prix_depart, 2) }} TND</small>
                        @endif
                    </div>

                    <!-- Compteur / Statut -->
                    @if($annonce->statut == 'ACTIVE')
                    <div class="bg-purple-light rounded-4 p-3 mb-4 text-center">
                        <div class="countdown-circle mx-auto mb-2" id="countdownContainer">
                            <svg viewBox="0 0 100 100" width="80">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                <circle cx="50" cy="50" r="45" fill="none" stroke="url(#gradient)" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="0" id="progressCircle" transform="rotate(-90 50 50)"/>
                                <defs><linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#667eea"/><stop offset="100%" stop-color="#764ba2"/></linearGradient></defs>
                            </svg>
                            <div class="countdown-text" id="countdown">--:--:--</div>
                        </div>
                        <small class="fw-bold text-primary">Temps restant</small>
                    </div>
                    @elseif($annonce->statut == 'CLOTUREE')
                    <div class="alert alert-success text-center border-0 bg-success-light">
                        <i class="fas fa-check-circle fa-2x"></i>
                        <p class="mb-0 mt-2 fw-bold">Enchère terminée</p>
                    </div>
                    @else
                    <div class="alert alert-warning text-center border-0">
                        <i class="fas fa-clock fa-2x"></i>
                        <p class="mb-0 mt-2">En attente de validation</p>
                    </div>
                    @endif

                    <!-- Formulaire d’enchère -->
                    @if($annonce->statut == 'ACTIVE')
                        @auth
                            @role('client')
                                @if($userBid)
                                    <div class="alert alert-primary border-0 text-center">
                                        Votre offre : <strong>{{ number_format($userBid->montant, 2) }} TND</strong>
                                    </div>
                                @endif
                                <form id="bidForm" method="POST" action="{{ route('bids.place', $annonce) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Votre enchère (TND)</label>
                                        <input type="number" name="montant" class="form-control form-control-lg" 
                                               min="{{ $currentHighestBid + $annonce->montant_mise }}" 
                                               step="1" required placeholder="{{ $currentHighestBid + $annonce->montant_mise }}">
                                        <small class="text-muted">Minimum : {{ number_format($currentHighestBid + $annonce->montant_mise, 2) }} TND</small>
                                    </div>
                                    <button type="submit" class="btn btn-gradient w-100 btn-lg">
                                        <i class="fas fa-gavel me-1"></i> Placer mon enchère
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-lock fa-2x text-muted"></i>
                                    <p class="mt-2">Connectez-vous en tant qu'acheteur.</p>
                                    <button class="btn btn-outline-gradient" data-bs-toggle="modal" data-bs-target="#loginModal">Connexion</button>
                                </div>
                            @endrole
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-lock fa-2x text-muted"></i>
                                <p>Connectez-vous pour enchérir.</p>
                                <button class="btn btn-outline-gradient" data-bs-toggle="modal" data-bs-target="#loginModal">Connexion</button>
                                <a href="{{ route('register') }}" class="btn btn-link">Créer un compte</a>
                            </div>
                        @endif
                    @elseif($annonce->statut == 'CLOTUREE')
                        @php
                            $winningBid = $annonce->encheres()->latest('montant')->first();
                            $userWon = Auth::check() && Auth::user()->client && $winningBid && $winningBid->client_id == Auth::user()->client->id;
                        @endphp
                        @if($userWon)
                            <div class="text-center bg-gradient-theme text-white rounded-4 p-3">
                                <h4>🏆 Félicitations !</h4>
                                <p>Vous avez gagné avec <strong>{{ number_format($winningBid->montant, 2) }} TND</strong></p>
                                <button class="btn btn-light" onclick="contactSeller()">Contacter le vendeur</button>
                            </div>
                        @else
                            <div class="text-center p-3 bg-light rounded-4">
                                <p>Gagnant : {{ $winningBid->client->nom ?? 'N/A' }} ({{ number_format($winningBid->montant ?? 0, 2) }} TND)</p>
                            </div>
                        @endif
                    @endif

                    <!-- Infos supplémentaires -->
                    <hr class="my-4">
                    <div class="d-flex justify-content-between small">
                        <span><i class="far fa-calendar-alt me-1"></i> Début</span>
                        <span>{{ $annonce->date_debut ? \Carbon\Carbon::parse($annonce->date_debut)->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mt-2">
                        <span><i class="far fa-calendar-check me-1"></i> Fin</span>
                        <span>{{ $annonce->date_fin ? \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>
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
    /* Styles additionnels pour la page d'enchère - en harmonie avec le layout existant */
    .auction-showcase .breadcrumb-custom {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .card-custom {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        transition: box-shadow 0.3s;
    }
    .card-custom:hover {
        box-shadow: 0 10px 30px rgba(102,126,234,0.12);
    }
    .text-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .bg-purple-light {
        background: rgba(102,126,234,0.08);
    }
    .bg-success-light {
        background: rgba(16,185,129,0.1);
    }
    .seller-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .countdown-circle {
        position: relative;
        width: 80px;
        height: 80px;
    }
    .countdown-circle svg {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
    }
    .countdown-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 700;
        font-size: 0.9rem;
        color: #667eea;
    }
    .nav-pills .nav-link {
        color: #4a5568;
        border-radius: 10px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
    }
    .nav-pills .nav-link.active {
        background:transparent;
    }

    .bg-gradient-theme {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .table-primary {
        background: rgba(102,126,234,0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Compteur à rebours circulaire
        @if($annonce->statut === 'ACTIVE' && $annonce->date_fin)
        const endDate = new Date('{{ $annonce->date_fin }}').getTime();
        const totalSeconds = Math.floor((endDate - Date.now()) / 1000);
        const circumference = 283; // 2*pi*45
        const circle = document.getElementById('progressCircle');
        const countdownEl = document.getElementById('countdown');

        function updateCountdown() {
            const now = Date.now();
            const distance = endDate - now;
            if (distance < 0) {
                countdownEl.textContent = 'Terminé';
                circle.style.strokeDashoffset = circumference;
                return;
            }
            const hours = Math.floor(distance / (1000*60*60));
            const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
            const seconds = Math.floor((distance % (1000*60)) / 1000);
            countdownEl.textContent = `${hours}h ${minutes}m ${seconds}s`;

            const elapsedRatio = 1 - (distance / (totalSeconds * 1000));
            circle.style.strokeDashoffset = circumference * elapsedRatio;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
        @endif

        // Activer les miniatures Bootstrap
        const thumbnails = document.querySelectorAll('[data-bs-target="#auctionCarousel"]');
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const slideIndex = this.getAttribute('data-bs-slide-to');
                const carousel = document.querySelector('#auctionCarousel');
                const bsCarousel = bootstrap.Carousel.getInstance(carousel);
                bsCarousel.to(parseInt(slideIndex));
            });
        });
    });

    function contactSeller() {
        window.location.href = 'mailto:{{ $annonce->vendeur->client->user->email }}?subject=Question%20enchère%20{{ urlencode($annonce->titre) }}';
    }
</script>
@endpush