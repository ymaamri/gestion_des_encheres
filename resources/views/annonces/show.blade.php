{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/show.blade.php --}}
@extends('layouts.app')

@section('title', $annonce->titre)
@section('page-title', $annonce->titre)
@section('breadcrumb', 'Détails de l\'Enchère')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">{{ $annonce->titre }}</h6>
                </div>
            </div>
            <div class="card-body">
                <!-- Product Images Carousel -->
                @php
                    $photos = $annonce->produit->photos ?? [];
                @endphp
                @if(count($photos) > 0)
                    <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($photos as $index => $photo)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($photo) }}" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Image du produit">
                                </div>
                            @endforeach
                        </div>
                        @if(count($photos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="text-center mb-4 bg-gray-100 rounded p-5">
                        <i class="material-symbols-rounded" style="font-size: 80px;">image</i>
                        <p>Aucune image disponible</p>
                    </div>
                @endif

                <!-- Product Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informations Produit</h6>
                        <table class="table">
                            <tr>
                                <th>Marque :</th>
                                <td>{{ $annonce->produit->marque ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Modèle :</th>
                                <td>{{ $annonce->produit->modele ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>État :</th>
                                <td>
                                    @switch($annonce->produit->etat)
                                        @case('NEUF') Neuf @break
                                        @case('TRES_BON_ETAT') Très Bon État @break
                                        @case('BON_ETAT') Bon État @break
                                        @case('ACCEPTABLE') Acceptable @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>Catégorie :</th>
                                <td>{{ $annonce->produit->categorie->nom ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails de l'Enchère</h6>
                        <table class="table">
                            <tr>
                                <th>Vendeur :</th>
                                <td>{{ $annonce->vendeur->client->nom }} {{ $annonce->vendeur->client->prenom }}</td>
                            </tr>
                            <tr>
                                <th>Date de début :</th>
                                <td>{{ \Carbon\Carbon::parse($annonce->date_debut)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Date de fin :</th>
                                <td>{{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Statut :</th>
                                <td>
                                    @switch($annonce->statut)
                                        @case('ACTIVE')
                                            <span class="badge bg-gradient-success">Active</span>
                                            @break
                                        @case('CLOTUREE')
                                            <span class="badge bg-gradient-secondary">Clôturée</span>
                                            @break
                                        @default
                                            <span class="badge bg-gradient-warning">{{ $annonce->statut }}</span>
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>Description</h6>
                    <p>{{ $annonce->description ?? 'Aucune description fournie.' }}</p>
                </div>

                <div class="mb-4">
                    <h6>Description du Produit</h6>
                    <p>{{ $annonce->produit->description ?? 'Aucune description fournie.' }}</p>
                </div>
            </div>
        </div>

        <!-- Bidding History -->
        <div class="card">
            <div class="card-header">
                <h6>Historique des Enchères</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enchérisseur</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($annonce->mises()->with('client.user')->latest()->get() as $mise)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $mise->client->nom }} {{ $mise->client->prenom }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="text-secondary text-xs font-weight-bold">{{ number_format($mise->montant, 2) }} MAD</span>
                                </td>
                                <td class="align-middle">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $mise->created_at->format('d/m/Y H:i:s') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">Aucune enchère pour le moment. Soyez le premier à enchérir !</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Bid Card -->
        <div class="card">
            <div class="card-header">
                <h6>Placer une Enchère</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="text-primary">{{ number_format($currentHighestBid, 2) }} MAD</h3>
                    <p class="text-muted">Enchère la plus élevée actuelle</p>
                </div>

                @if($annonce->statut === 'ACTIVE')
                    @role('client')
                        <form method="POST" action="{{ route('bids.place', $annonce) }}">
                            @csrf
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Votre Montant (MAD)</label>
                                <input type="number" name="montant" class="form-control" step="1" min="{{ $currentHighestBid + 1 }}" required>
                            </div>
                            <button type="submit" class="btn bg-gradient-primary w-100">
                                <i class="material-symbols-rounded me-1">gavel</i> Placer une Enchère
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Vous devez être connecté en tant qu'acheteur pour placer des enchères.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Se connecter pour enchérir</a>
                    @endrole
                @elseif($annonce->statut === 'CLOTUREE')
                    <div class="alert alert-secondary">
                        Cette enchère est terminée.
                    </div>
                @else
                    <div class="alert alert-warning">
                        Cette enchère n'est pas active.
                    </div>
                @endif

                <hr>

                <div class="text-center">
                    <p class="text-sm">
                        <i class="material-symbols-rounded text-info" style="font-size: 16px;">info</i>
                        Incrément minimum d'enchère : 1 MAD
                    </p>
                </div>
            </div>
        </div>

        <!-- Seller Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6>Informations Vendeur</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-xl bg-gradient-dark border-radius-lg">
                        <i class="material-symbols-rounded text-white">store</i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ $annonce->vendeur->client->nom }} {{ $annonce->vendeur->client->prenom }}</h6>
                        <p class="text-sm text-muted mb-0">Vendeur depuis {{ $annonce->vendeur->created_at->format('M Y') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-sm mb-0">Ventes totales</p>
                        <h6>{{ $annonce->vendeur->nombre_ventes }}</h6>
                    </div>
                    <div class="col-6">
                        <p class="text-sm mb-0">Évaluation</p>
                        <h6>{{ number_format($annonce->vendeur->note_moyenne, 1) }} / 5.0</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection