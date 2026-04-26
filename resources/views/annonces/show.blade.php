{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/show.blade.php --}}
@extends('layouts.app')

@section('title', $annonce->titre)
@section('page-title', $annonce->titre)
@section('breadcrumb', 'Détails de l\'Enchère')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Product Images Carousel -->
        <div class="card mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Galerie Photos</h6>
                </div>
            </div>
            <div class="card-body">
                @php
                    $images = \App\Helpers\ImageHelper::getProductImages($annonce->produit);
                @endphp
                
                @if(count($images) > 1)
                    <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($images as $index => $image)
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ $image }}" class="d-block w-100" style="height: 450px; object-fit: contain; background: #f5f5f5;" alt="Image du produit {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                @elseif(count($images) == 1)
                    <div class="text-center mb-4">
                        <img src="{{ $images[0] }}" class="img-fluid rounded" style="max-height: 450px; object-fit: contain;" alt="Image du produit">
                    </div>
                @else
                    <div class="text-center mb-4 bg-gray-100 rounded p-5">
                        <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">image_not_supported</i>
                        <p class="mt-3 text-muted">Aucune image disponible pour ce produit</p>
                    </div>
                @endif

                <!-- Product Details Tabs -->
                <ul class="nav nav-tabs mt-4" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                            <i class="material-symbols-rounded me-1">description</i> Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                            <i class="material-symbols-rounded me-1">info</i> Détails produit
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seller-tab" data-bs-toggle="tab" data-bs-target="#seller" type="button" role="tab">
                            <i class="material-symbols-rounded me-1">store</i> Vendeur
                        </button>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div class="p-3">
                            <h5>À propos de cette annonce</h5>
                            <p>{{ $annonce->description ?: 'Aucune description fournie pour cette annonce.' }}</p>
                            
                            <h5 class="mt-3">Description du produit</h5>
                            <p>{{ $annonce->produit->description ?: 'Aucune description fournie pour ce produit.' }}</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="details" role="tabpanel">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th><i class="material-symbols-rounded">branding_watermark</i> Marque :</th>
                                            <td>{{ $annonce->produit->marque ?? 'Non spécifiée' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="material-symbols-rounded">model_training</i> Modèle :</th>
                                            <td>{{ $annonce->produit->modele ?? 'Non spécifié' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="material-symbols-rounded">category</i> Catégorie :</th>
                                            <td>{{ $annonce->produit->sousCategorie->categorie->nom ?? 'Non catégorisé' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th><i class="material-symbols-rounded">inventory_2</i> État :</th>
                                            <td>
                                                @switch($annonce->produit->etat)
                                                    @case('NEUF')
                                                        <span class="badge bg-gradient-success">Neuf</span>
                                                        @break
                                                    @case('TRES_BON_ETAT')
                                                        <span class="badge bg-gradient-info">Très Bon État</span>
                                                        @break
                                                    @case('BON_ETAT')
                                                        <span class="badge bg-gradient-primary">Bon État</span>
                                                        @break
                                                    @case('ACCEPTABLE')
                                                        <span class="badge bg-gradient-warning">Acceptable</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-gradient-secondary">{{ $annonce->produit->etat }}</span>
                                                @endswitch
                                              </td>
                                        </tr>
                                        <tr>
                                            <th><i class="material-symbols-rounded">sell</i> Prix de départ :</th>
                                            <td><strong>{{ number_format($annonce->prix_depart, 2) }} MAD</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="seller" role="tabpanel">
                        <div class="p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-xl bg-gradient-dark border-radius-lg d-flex align-items-center justify-content-center">
                                    <i class="material-symbols-rounded text-white" style="font-size: 48px;">store</i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">{{ $annonce->vendeur->client->nom }} {{ $annonce->vendeur->client->prenom }}</h5>
                                    <p class="text-sm text-muted mb-0">Membre depuis {{ $annonce->vendeur->created_at->format('F Y') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center p-3">
                                            <h3 class="mb-0">{{ $annonce->vendeur->nombre_ventes }}</h3>
                                            <small class="text-muted">Ventes totales</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center p-3">
                                            <h3 class="mb-0 text-warning">{{ number_format($annonce->vendeur->note_moyenne, 1) }} / 5</h3>
                                            <small class="text-muted">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="material-symbols-rounded" style="font-size: 14px; color: {{ $i <= round($annonce->vendeur->note_moyenne) ? '#ffc107' : '#dee2e6' }}">star</i>
                                                @endfor
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bidding History -->
        <div class="card">
            <div class="card-header">
                <h6><i class="material-symbols-rounded me-1">history</i> Historique des Enchères</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enchérisseur</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $bids = $annonce->encheres()->with('client.user')->latest()->get();
                            @endphp
                            @forelse($bids as $enchere)
                            <tr class="{{ $loop->first ? 'bg-gradient-light' : '' }}">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">
                                                @if($enchere->client)
                                                    {{ $enchere->client->nom }} {{ $enchere->client->prenom }}
                                                @else
                                                    <span class="text-muted">Utilisateur supprimé</span>
                                                @endif
                                                @if($loop->first && $annonce->statut == 'ACTIVE')
                                                    <span class="badge badge-sm bg-gradient-success ms-2">Leader 🏆</span>
                                                @endif
                                            </h6>
                                            @if($enchere->client && $enchere->client->user)
                                                <p class="text-xs text-secondary mb-0">{{ $enchere->client->user->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-{{ $loop->first ? 'success' : 'secondary' }} text-sm font-weight-bold">
                                        {{ number_format($enchere->montant, 2) }} MAD
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ \Carbon\Carbon::parse($enchere->date_mise)->format('d/m/Y H:i:s') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($enchere->date_mise)->diffForHumans() }}</small>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="material-symbols-rounded" style="font-size: 48px;">gavel</i>
                                    <p class="mt-2 mb-0">Aucune enchère pour le moment.</p>
                                    <small class="text-muted">Soyez le premier à enchérir !</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bids->count() > 0)
                <div class="px-3 py-2 bg-light">
                    <small class="text-muted">
                        <i class="material-symbols-rounded" style="font-size: 14px;">info</i>
                        Total: {{ $bids->count() }} enchère(s) placée(s)
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Bid Card -->
        <div class="card position-sticky" style="top: 20px;">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="mb-0 text-white">Participer à l'enchère</h6>
            </div>
            <div class="card-body">
                <!-- Current Price -->
                <div class="text-center mb-4">
                    <small class="text-muted">Enchère actuelle</small>
                    <h2 class="text-primary mb-0">{{ number_format($annonce->getMontantActuel(), 2) }} MAD</h2>
                    @if($annonce->prix_depart < $annonce->getMontantActuel())
                        <small class="text-muted">Départ: {{ number_format($annonce->prix_depart, 2) }} MAD</small>
                    @endif
                </div>

                <!-- Auction Status -->
                <div class="alert alert-{{ $annonce->statut == 'ACTIVE' ? 'success' : ($annonce->statut == 'CLOTUREE' ? 'secondary' : 'warning') }} text-center mb-3">
                    @if($annonce->statut == 'ACTIVE')
                        <i class="material-symbols-rounded">schedule</i>
                        <strong>Temps restant:</strong>
                        <div id="countdown" class="h4 mb-0"></div>
                    @elseif($annonce->statut == 'CLOTUREE')
                        <i class="material-symbols-rounded">check_circle</i>
                        <strong>Enchère terminée</strong>
                    @else
                        <i class="material-symbols-rounded">pending</i>
                        <strong>En attente de validation</strong>
                    @endif
                </div>

                <!-- Bid Form -->
                @if($annonce->statut === 'ACTIVE')
                    @auth
                        @role('client')
                            @php
                                $currentHighestBid = $annonce->getMontantActuel();
                                $userBid = $annonce->getUserBid(Auth::id());
                            @endphp
                            @if($userBid)
                                <div class="alert alert-info mb-3">
                                    <i class="material-symbols-rounded">info</i>
                                    Votre dernière enchère: <strong>{{ number_format($userBid->montant, 2) }} MAD</strong>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('bids.place', $annonce) }}" id="bidForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Votre enchère (MAD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MAD</span>
                                        <input type="number" name="montant" class="form-control" step="1" min="{{ $currentHighestBid + $annonce->montant_mise }}" id="bidAmount" required>
                                    </div>
                                    <small class="text-muted">Enchère minimale: {{ number_format($currentHighestBid + $annonce->montant_mise, 2) }} MAD</small>
                                </div>
                                <button type="submit" class="btn bg-gradient-primary w-100" id="submitBid">
                                    <i class="material-symbols-rounded me-1">gavel</i> Placer mon enchère
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="material-symbols-rounded">warning</i>
                                Vous devez être connecté en tant qu'acheteur pour enchérir.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                <i class="material-symbols-rounded me-1">login</i> Se connecter
                            </a>
                        @endrole
                    @else
                        <div class="alert alert-warning">
                            <i class="material-symbols-rounded">lock</i>
                            Connectez-vous pour participer à cette enchère.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                            <i class="material-symbols-rounded me-1">login</i> Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-link w-100 mt-2">
                            Créer un compte
                        </a>
                    @endif
                @elseif($annonce->statut === 'CLOTUREE')
                    @php
                        $winningBid = $annonce->encheres()->latest('montant')->first();
                        $userWon = Auth::check() && Auth::user()->client && $winningBid && $winningBid->client_id == Auth::user()->client->id;
                    @endphp
                    @if($userWon)
                        <div class="alert alert-success text-center">
                            <i class="material-symbols-rounded" style="font-size: 48px;">emoji_events</i>
                            <h5 class="mt-2">Félicitations !</h5>
                            <p>Vous avez remporté cette enchère avec {{ number_format($winningBid->montant, 2) }} MAD</p>
                            <button class="btn btn-success mt-2" onclick="contactSeller()">
                                <i class="material-symbols-rounded me-1">mail</i> Contacter le vendeur
                            </button>
                        </div>
                    @else
                        <div class="alert alert-secondary text-center">
                            <i class="material-symbols-rounded">check_circle</i>
                            <p class="mb-0 mt-2">Cette enchère est terminée.</p>
                            @if($winningBid)
                                <small>Gagnée par 
                                    @if($winningBid->client)
                                        {{ $winningBid->client->nom }}
                                    @else
                                        un utilisateur supprimé
                                    @endif
                                    avec {{ number_format($winningBid->montant, 2) }} MAD
                                </small>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning text-center">
                        <i class="material-symbols-rounded">pending</i>
                        <p class="mb-0 mt-2">Cette enchère n'est pas encore active.</p>
                    </div>
                @endif

                <hr>

                <!-- Auction Info -->
                <div class="mt-3">
                    <h6 class="text-uppercase text-secondary">Informations</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="material-symbols-rounded">event</i> Début:</span>
                        <span>{{ $annonce->date_debut ? \Carbon\Carbon::parse($annonce->date_debut)->format('d/m/Y H:i') : 'Non définie' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="material-symbols-rounded">event_busy</i> Fin:</span>
                        <span>{{ $annonce->date_fin ? \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') : 'Non définie' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="material-symbols-rounded">gavel</i> Total enchères:</span>
                        <span class="badge bg-gradient-info">{{ $annonce->encheres()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Countdown Timer
    @if($annonce->statut === 'ACTIVE' && $annonce->date_fin)
        function updateCountdown() {
            // Use the date_fin attribute directly (Carbon instance will be cast to string)
            const endDate = new Date('{{ $annonce->date_fin }}').getTime();
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = 'Terminée';
                location.reload();
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (86400000)) / (3600000));
            const minutes = Math.floor((distance % (3600000)) / (60000));
            const seconds = Math.floor((distance % 60000) / 1000);

            let countdownText = '';
            if (days > 0) countdownText += days + 'j ';
            countdownText += hours + 'h ' + minutes + 'm ' + seconds + 's';
            
            document.getElementById('countdown').innerHTML = countdownText;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    @endif

    // Bid form validation
    document.getElementById('bidForm')?.addEventListener('submit', function(e) {
        const bidAmount = document.getElementById('bidAmount').value;
        const minBid = {{ $currentHighestBid + $annonce->montant_mise }};
        
        if (bidAmount < minBid) {
            e.preventDefault();
            alert('Votre enchère doit être d\'au moins ' + minBid + ' MAD');
        }
    });

    function contactSeller() {
        window.location.href = 'mailto:{{ $annonce->vendeur->client->user->email }}?subject=Question about auction: {{ $annonce->titre }}';
    }

    // Auto-refresh bid history every 30 seconds for active auctions
    @if($annonce->statut === 'ACTIVE')
        setInterval(function() {
            location.reload();
        }, 30000);
    @endif
</script>
@endpush