{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/bids/won-auctions.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Gagnées')
@section('page-title', 'Enchères Gagnées')
@section('breadcrumb', 'Enchères Gagnées')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Mes Enchères Gagnées 🏆</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Prix Gagnant</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Date de fin</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Vendeur</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Statut</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wonAuctions as $mise)
                                    @php
                                        $annonce = $mise->annonce;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                @php
                                                    $photos = $annonce->produit->photos ?? [];
                                                    $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/40x40';
                                                @endphp
                                                <div>
                                                    <img src="{{ $firstPhoto }}" class="avatar avatar-sm me-3 border-radius-lg"
                                                        alt="produit">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ Str::limit($annonce->titre, 40) }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm text-success">{{ number_format($mise->montant, 2) }} MAD
                                            </h6>
                                            <small class="text-muted">Prix de départ:
                                                {{ number_format($annonce->prix_depart, 2) }} MAD</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-sm">{{ $annonce->vendeur->client->nom }}
                                                    {{ $annonce->vendeur->client->prenom }}</span>
                                                <small class="text-muted">Note:
                                                    {{ number_format($annonce->vendeur->note_moyenne, 1) }}/5</small>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-gradient-success">À récupérer</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('annonces.show', $annonce) }}"
                                                class="btn btn-link text-secondary mb-0">
                                                <i class="material-symbols-rounded">visibility</i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="material-symbols-rounded" style="font-size: 48px;">emoji_events</i>
                                            <p class="text-secondary mt-2 mb-0">Vous n'avez pas encore gagné d'enchères.</p>
                                            <a href="{{ route('auctions.active') }}"
                                                class="btn btn-sm bg-gradient-primary mt-3">
                                                Participer aux enchères actives
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 pt-3">
                        {{ $wonAuctions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection