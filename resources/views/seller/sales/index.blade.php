{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/sales/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Ventes')
@section('page-title', 'Historique des ventes')
@section('breadcrumb', 'Ventes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-gradient-theme text-white rounded-top-4 p-3">
                <h6 class="mb-0 fw-bold">
                    <i class="material-symbols-rounded me-1 align-middle">sell</i> Mes ventes réalisées
                </h6>
            </div>
            <div class="card-body px-0 pb-2">
                @if($salesPaginated->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Produit</th>
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
                                    <tr class="align-middle">
                                        <td class="px-4">
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $productImage }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 10px;">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm fw-bold text-dark">{{ Str::limit($sale->titre, 40) }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $sale->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($winner)
                                                <p class="text-xs font-weight-bold mb-0 text-dark">{{ $winner->nom }} {{ $winner->prenom }}</p>
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
                                        <td class="align-middle text-center">
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
                        <i class="material-symbols-rounded" style="font-size: 64px; color: #cbd5e0;">sell</i>
                        <h5 class="mt-3 text-secondary">Aucune vente réalisée</h5>
                        <p class="text-muted">Vous n'avez pas encore vendu de produit via les enchères.</p>
                        <a href="{{ route('annonces.create') }}" class="btn btn-gradient mt-2 rounded-3">
                            <i class="material-symbols-rounded align-middle me-1">add_circle</i> Créer une annonce
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    .bg-gradient-theme {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .table thead th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush