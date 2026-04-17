{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/my-bids.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Offres')
@section('page-title', 'Mes Offres')
@section('breadcrumb', 'Mes Offres')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-gradient-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white opacity-8 mb-1">Total des offres</h6>
                                    <h3 class="text-white mb-0">{{ $bids->total() }}</h3>
                                </div>
                                <div>
                                    <i class="material-symbols-rounded text-white" style="font-size: 48px;">gavel</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white opacity-8 mb-1">Offres en tête</h6>
                                    <h3 class="text-white mb-0">{{ $activeBidsCount ?? 0 }}</h3>
                                </div>
                                <div>
                                    <i class="material-symbols-rounded text-white" style="font-size: 48px;">trending_up</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white opacity-8 mb-1">Offres dépassées</h6>
                                    <h3 class="text-white mb-0">{{ $outbidCount ?? 0 }}</h3>
                                </div>
                                <div>
                                    <i class="material-symbols-rounded text-white" style="font-size: 48px;">trending_down</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white opacity-8 mb-1">Enchères gagnées</h6>
                                    <h3 class="text-white mb-0">{{ $wonCount ?? 0 }}</h3>
                                </div>
                                <div>
                                    <i class="material-symbols-rounded text-white" style="font-size: 48px;">emoji_events</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bids Table -->
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="material-symbols-rounded me-1">history</i> Historique de mes enchères
                        </h6>
                        <div class="me-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text text-body">
                                    <i class="material-symbols-rounded">search</i>
                                </span>
                                <input type="text" id="bidSearch" class="form-control" placeholder="Rechercher...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="bidsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Détails</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Position</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bids as $mise)
                                    @php
                                        $isWinning = $mise->annonce->getMontantActuel() == $mise->montant && $mise->annonce->statut == 'ACTIVE';
                                        $highestBid = $mise->annonce->mises()->max('montant');
                                        $rank = $mise->annonce->mises()->where('montant', '>', $mise->montant)->count() + 1;
                                        $totalBids = $mise->annonce->mises()->count();
                                        $productImage = \App\Helpers\ImageHelper::getProductImage($mise->annonce->produit);
                                        $timeLeft = \Carbon\Carbon::parse($mise->annonce->date_fin);
                                        $isEndingSoon = $timeLeft->diffInHours(now()) <= 24 && $mise->annonce->statut == 'ACTIVE';
                                    @endphp
                                    <tr class="bid-row">
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $productImage }}" class="avatar avatar-md me-3 border-radius-lg" alt="{{ $mise->annonce->produit->nom }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ Str::limit($mise->annonce->titre, 40) }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $mise->annonce->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <i class="material-symbols-rounded" style="font-size: 14px;">branding_watermark</i>
                                                    {{ $mise->annonce->produit->marque ?: 'N/A' }}
                                                </p>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="material-symbols-rounded" style="font-size: 14px;">category</i>
                                                    {{ $mise->annonce->produit->categorie->nom ?? 'N/A' }}
                                                </p>
                                                @if($isEndingSoon && $mise->annonce->statut == 'ACTIVE')
                                                    <span class="badge badge-sm bg-gradient-warning mt-1">
                                                        <i class="material-symbols-rounded" style="font-size: 10px;">schedule</i> Fin imminente
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <h6 class="mb-0 text-{{ $isWinning ? 'success' : 'secondary' }} font-weight-bold">
                                                {{ number_format($mise->montant, 2) }} MAD
                                            </h6>
                                            <small class="text-muted">Départ: {{ number_format($mise->annonce->prix_depart, 2) }} MAD</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $mise->created_at->format('d/m/Y H:i:s') }}
                                                <br>
                                                <small class="text-muted">{{ $mise->created_at->diffForHumans() }}</small>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($mise->annonce->statut == 'ACTIVE')
                                                @if($isWinning)
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="material-symbols-rounded" style="font-size: 12px;">check_circle</i> En tête
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-warning">
                                                        <i class="material-symbols-rounded" style="font-size: 12px;">trending_down</i> Dépassé
                                                    </span>
                                                @endif
                                            @elseif($mise->annonce->statut == 'CLOTUREE')
                                                @if($highestBid == $mise->montant)
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="material-symbols-rounded" style="font-size: 12px;">emoji_events</i> Gagnée ! 🏆
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        <i class="material-symbols-rounded" style="font-size: 12px;">close</i> Perdue
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge badge-sm bg-gradient-dark">
                                                    <i class="material-symbols-rounded" style="font-size: 12px;">pending</i> {{ $mise->annonce->statut }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div>
                                                <h6 class="mb-0">{{ $rank }}/{{ $totalBids }}</h6>
                                                <div class="progress mt-1" style="height: 4px; width: 60px; margin: 0 auto;">
                                                    @php
                                                        $positionPercent = ($totalBids - $rank) / $totalBids * 100;
                                                    @endphp
                                                    <div class="progress-bar bg-gradient-{{ $isWinning ? 'success' : 'warning' }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $positionPercent }}%;" 
                                                         aria-valuenow="{{ $positionPercent }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    @if($rank == 1 && $mise->annonce->statut == 'ACTIVE')
                                                        Vous êtes en tête !
                                                    @elseif($rank == 1 && $mise->annonce->statut == 'CLOTUREE')
                                                        Vous avez gagné !
                                                    @elseif($mise->annonce->statut == 'ACTIVE')
                                                        Devancez {{ $rank - 1 }} enchérisseur(s)
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="material-symbols-rounded">more_vert</i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                                    <li>
                                                        <a class="dropdown-item border-radius-md" href="{{ route('annonces.show', $mise->annonce) }}">
                                                            <i class="material-symbols-rounded me-2">visibility</i> Voir l'enchère
                                                        </a>
                                                    </li>
                                                    @if($mise->annonce->statut == 'ACTIVE' && !$isWinning)
                                                        <li>
                                                            <a class="dropdown-item border-radius-md text-success" href="{{ route('annonces.show', $mise->annonce) }}">
                                                                <i class="material-symbols-rounded me-2">gavel</i> Renchérir
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($highestBid == $mise->montant && $mise->annonce->statut == 'CLOTUREE')
                                                        <li>
                                                            <a class="dropdown-item border-radius-md text-info" href="#" onclick="contactSeller({{ $mise->annonce->vendeur->client->user->email }}, '{{ $mise->annonce->titre }}')">
                                                                <i class="material-symbols-rounded me-2">mail</i> Contacter le vendeur
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="material-symbols-rounded" style="font-size: 64px; color: #cbd5e0;">history</i>
                                            <h5 class="mt-3 text-secondary">Aucune offre trouvée</h5>
                                            <p class="text-muted mb-0">Vous n'avez pas encore participé à des enchères.</p>
                                            <p class="text-muted">Découvrez les enchères actives et commencez à enchérir !</p>
                                            <a href="{{ route('auctions.active') }}" class="btn btn-sm bg-gradient-primary mt-3">
                                                <i class="material-symbols-rounded">gavel</i> Explorer les enchères actives
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($bids->count() > 0)
                        <div class="px-3 pt-3">
                            {{ $bids->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips Section -->
            @if($bids->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="material-symbols-rounded me-1">tips_and_updates</i> Conseils pour réussir vos enchères</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-info rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">schedule</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Soyez réactif</h6>
                                        <small class="text-muted">Surveillez la fin des enchères pour ne pas vous faire dépasser</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-success rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">paid</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Fixez votre budget</h6>
                                        <small class="text-muted">Définissez un montant maximum et respectez-le</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-warning rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">analytics</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Analysez la concurrence</h6>
                                        <small class="text-muted">Observez les habitudes des autres enchérisseurs</small>
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
            let rows = document.querySelectorAll('.bid-row');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function contactSeller(email, title) {
            window.location.href = 'mailto:' + email + '?subject=Question about auction: ' + encodeURIComponent(title);
        }

        // Auto-refresh for active bids every 30 seconds
        let hasActiveBids = {{ $bids->contains(function ($bid) {
        return $bid->annonce->statut == 'ACTIVE'; }) ? 'true' : 'false' }};

        if (hasActiveBids) {
            setInterval(function() {
                if (!document.hidden) {
                    location.reload();
                }
            }, 30000);
        }

        // Add tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
@endpush