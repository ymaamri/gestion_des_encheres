{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/my-bids.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Offres')
@section('page-title', 'Mes Offres')
@section('breadcrumb', 'Mes Offres')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Historique de mes enchères</h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut Enchère</th>
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
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            @php
                                                $photos = $mise->annonce->produit->photos ?? [];
                                                $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/40x40';
                                            @endphp
                                            <div>
                                                <img src="{{ $firstPhoto }}" class="avatar avatar-sm me-3 border-radius-lg" alt="produit">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ Str::limit($mise->annonce->titre, 40) }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $mise->annonce->produit->nom }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm {{ $isWinning ? 'text-success' : '' }}">
                                            {{ number_format($mise->montant, 2) }} MAD
                                        </h6>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $mise->created_at->format('d/m/Y H:i:s') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($mise->annonce->statut == 'ACTIVE')
                                            @if($isWinning)
                                                <span class="badge badge-sm bg-gradient-success">En tête</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-warning">Dépassé</span>
                                            @endif
                                        @elseif($mise->annonce->statut == 'CLOTUREE')
                                            @if($highestBid == $mise->montant)
                                                <span class="badge badge-sm bg-gradient-success">Gagnée 🏆</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">Perdue</span>
                                            @endif
                                        @else
                                            <span class="badge badge-sm bg-gradient-dark">{{ $mise->annonce->statut }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $rank }}/{{ $mise->annonce->mises()->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('annonces.show', $mise->annonce) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="material-symbols-rounded" style="font-size: 48px;">history</i>
                                        <p class="text-secondary mt-2 mb-0">Vous n'avez pas encore participé à des enchères.</p>
                                        <a href="{{ route('auctions.active') }}" class="btn btn-sm bg-gradient-primary mt-3">
                                            Explorer les enchères actives
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $bids->links() }}
                </div>
            </div>
        </div>
        
        <!-- Statistics Summary -->
        @if($bids->count() > 0)
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-primary">{{ $bids->count() }}</h3>
                            <p class="text-muted mb-0">Total des offres</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-success">{{ $activeBidsCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Offres en tête</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-warning">{{ $outbidCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Offres dépassées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-success">{{ $wonCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Enchères gagnées</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection