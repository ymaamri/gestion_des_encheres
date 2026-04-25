@extends('layouts.app')

@section('title', 'Offres reçues')
@section('page-title', 'Offres reçues sur mes enchères')
@section('breadcrumb', 'Offres reçues')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="material-symbols-rounded me-1">list_alt</i> Offres placées sur mes annonces
                        </h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if($bids->count() > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Enchérisseur</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Montant</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Date</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Statut</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bids as $bid)
                                        @php
                                            $annonce = $bid->annonce;
                                            $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                            $client = $bid->client; // could be null
                                        @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <img src="{{ $productImage }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product" style="width: 40px; height: 40px; object-fit: cover;">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ Str::limit($annonce->titre, 40) }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($client)
                                                            <p class="text-xs font-weight-bold mb-0">{{ $client->nom }} {{ $client->prenom }}</p>
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
                                                                <span class="badge badge-sm bg-gradient-success">En tête 🏆</span>
                                                            @else
                                                                <span class="badge badge-sm bg-gradient-warning">Dépassée</span>
                                                            @endif
                                                        @elseif($annonce->statut == 'CLOTUREE')
                                                            @if($bid->isWinning)
                                                                <span class="badge badge-sm bg-gradient-success">Gagnante ✅</span>
                                                            @else
                                                                <span class="badge badge-sm bg-gradient-secondary">Perdante</span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-secondary">{{ $annonce->statut }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
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
                            <i class="material-symbols-rounded" style="font-size: 64px;">gavel</i>
                            <h5 class="mt-3 text-secondary">Aucune offre reçue</h5>
                            <p class="text-muted">Vous n'avez encore reçu aucune enchère sur vos annonces.</p>
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