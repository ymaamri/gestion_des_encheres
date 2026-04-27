{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/bids/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Offres reçues')
@section('page-title', 'Offres reçues sur mes enchères')
@section('breadcrumb', 'Offres reçues')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-gradient-theme text-white rounded-top-4 p-3">
                <h6 class="mb-0 fw-bold">
                    <i class="material-symbols-rounded me-1 align-middle">list_alt</i> Offres placées sur mes annonces
                </h6>
            </div>
            <div class="card-body px-0 pb-2">
                @if($bids->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Annonce</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Enchérisseur</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bids as $bid)
                                    @php
                                        $annonce = $bid->annonce;
                                        $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        $client = $bid->client;
                                    @endphp
                                    <tr class="align-middle">
                                        <td class="px-4">
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $productImage }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 10px;">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm fw-bold text-dark">{{ Str::limit($annonce->titre, 40) }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($client)
                                                <p class="text-xs font-weight-bold mb-0 text-dark">{{ $client->nom }} {{ $client->prenom }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $client->user->email ?? 'Email non disponible' }}</p>
                                            @else
                                                <p class="text-xs text-secondary mb-0">Utilisateur supprimé</p>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-{{ $bid->isWinning ? 'success' : 'secondary' }} text-sm font-weight-bold">
                                                {{ number_format($bid->montant, 2) }} MAD
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $bid->created_at->format('d/m/Y H:i:s') }}
                                                <br><small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($annonce->statut == 'ACTIVE')
                                                @if($bid->isWinning)
                                                    <span class="badge bg-gradient-success px-3 py-1 rounded-pill">En tête 🏆</span>
                                                @else
                                                    <span class="badge bg-gradient-warning px-3 py-1 rounded-pill">Dépassée</span>
                                                @endif
                                            @elseif($annonce->statut == 'CLOTUREE')
                                                @if($bid->isWinning)
                                                    <span class="badge bg-gradient-success px-3 py-1 rounded-pill">Gagnante ✅</span>
                                                @else
                                                    <span class="badge bg-gradient-secondary px-3 py-1 rounded-pill">Perdante</span>
                                                @endif
                                            @else
                                                <span class="badge bg-gradient-secondary px-3 py-1 rounded-pill">{{ $annonce->statut }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-link text-secondary mb-0" data-bs-toggle="tooltip" title="Voir l'enchère">
                                                <i class="material-symbols-rounded">visibility</i>
                                            </a>
                                            @if($bid->isWinning && $annonce->statut == 'CLOTUREE' && $client)
                                                <a href="mailto:{{ $client->user->email }}?subject=Félicitations ! Vous avez gagné l'enchère {{ $annonce->titre }}" class="btn btn-link text-success mb-0" data-bs-toggle="tooltip" title="Contacter le gagnant">
                                                    <i class="material-symbols-rounded">mail</i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 pt-3">
                        {{ $bids->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded" style="font-size: 64px; color: #cbd5e0;">gavel</i>
                        <h5 class="mt-3 text-secondary">Aucune offre reçue</h5>
                        <p class="text-muted">Vous n'avez encore reçu aucune enchère sur vos annonces.</p>
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