@extends('layouts.app')

@section('title', 'Mes Ventes')
@section('page-title', 'Historique des ventes')
@section('breadcrumb', 'Ventes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">
                        <i class="material-symbols-rounded me-1">sell</i> Mes ventes réalisées
                    </h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                @if($salesPaginated->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Acheteur</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix de vente</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de clôture</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contact</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesPaginated as $sale)
                                    @php
                                        $productImage = \App\Helpers\ImageHelper::getProductImage($sale->produit);
                                        $winner = $sale->winner;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $productImage }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ Str::limit($sale->titre, 40) }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $sale->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($winner)
                                                <p class="text-xs font-weight-bold mb-0">{{ $winner->nom }} {{ $winner->prenom }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $winner->user->email ?? 'Email non disponible' }}</p>
                                            @else
                                                <p class="text-xs text-secondary mb-0">Aucun enchérisseur</p>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-success text-sm font-weight-bold">{{ number_format($sale->winning_bid_amount, 2) }} MAD</span>
                                            <br><small class="text-muted">Départ : {{ number_format($sale->prix_depart, 2) }} MAD</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $sale->updated_at->format('d/m/Y H:i') }}
                                                <br><small class="text-muted">{{ $sale->updated_at->diffForHumans() }}</small>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($winner)
                                                <a href="mailto:{{ $winner->user->email }}?subject=Félicitations pour votre achat : {{ $sale->titre }}&body=Bonjour, félicitations ! Vous avez remporté mon enchère pour {{ $sale->titre }}. Contactez-moi pour finaliser la transaction." class="btn btn-link text-success mb-0" data-bs-toggle="tooltip" title="Contacter l'acheteur">
                                                    <i class="material-symbols-rounded">mail</i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('annonces.show', $sale) }}" class="btn btn-link text-secondary mb-0" data-bs-toggle="tooltip" title="Voir l'annonce">
                                                <i class="material-symbols-rounded">visibility</i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 pt-3">
                        {{ $salesPaginated->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded" style="font-size: 64px;">sell</i>
                        <h5 class="mt-3 text-secondary">Aucune vente réalisée</h5>
                        <p class="text-muted">Vous n'avez pas encore vendu de produit via les enchères.</p>
                        <a href="{{ route('annonces.create') }}" class="btn bg-gradient-primary mt-2">
                            <i class="material-symbols-rounded">add_circle</i> Créer une annonce
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush