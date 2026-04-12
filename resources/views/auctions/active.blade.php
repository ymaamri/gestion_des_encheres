{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auctions/active.blade.php --}}
@extends('layouts.app')

@section('title', 'Enchères Actives')
@section('page-title', 'Enchères Actives')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Filters Card -->
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Filtrer les Enchères</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('auctions.active') }}" id="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Catégorie</label>
                                    <select name="categorie" class="form-control">
                                        <option value="">Toutes les catégories</option>
                                        @foreach($categories ?? [] as $categorie)
                                            <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                                {{ $categorie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Prix min (MAD)</label>
                                    <input type="number" name="prix_min" class="form-control" value="{{ request('prix_min') }}" step="100">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Prix max (MAD)</label>
                                    <input type="number" name="prix_max" class="form-control" value="{{ request('prix_max') }}" step="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">État du produit</label>
                                    <select name="etat" class="form-control">
                                        <option value="">Tous les états</option>
                                        <option value="NEUF" {{ request('etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                        <option value="TRES_BON_ETAT" {{ request('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                        <option value="BON_ETAT" {{ request('etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                        <option value="ACCEPTABLE" {{ request('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn bg-gradient-dark w-100">
                                    <i class="material-symbols-rounded">filter_alt</i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Active Auctions Grid -->
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Enchères Actives</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($auctions as $annonce)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 auction-card">
                                    @php
                                        $photos = $annonce->produit->photos ?? [];
                                        $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/300x200?text=No+Image';
                                        $timeLeft = \Carbon\Carbon::parse($annonce->date_fin)->diffForHumans();
                                        $currentBid = $annonce->getMontantActuel();
                                        $bidCount = $annonce->mises()->count();
                                    @endphp

                                    <div class="position-relative">
                                        <img src="{{ $firstPhoto }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $annonce->titre }}">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-gradient-success">{{ $bidCount }} enchère(s)</span>
                                        </div>
                                        <div class="position-absolute bottom-0 start-0 m-2">
                                            <span class="badge bg-gradient-info">
                                                <i class="material-symbols-rounded" style="font-size: 14px;">schedule</i> {{ $timeLeft }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($annonce->titre, 50) }}</h5>
                                        <p class="card-text text-muted small">{{ Str::limit($annonce->description, 80) }}</p>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Prix de départ</small>
                                                <h6 class="mb-0">{{ number_format($annonce->prix_depart, 2) }} MAD</h6>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Enchère actuelle</small>
                                                <h6 class="mb-0 text-primary">{{ number_format($currentBid, 2) }} MAD</h6>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <span class="badge bg-gradient-secondary">{{ $annonce->produit->etat_label ?? $annonce->produit->etat }}</span>
                                            <span class="badge bg-gradient-dark">{{ $annonce->produit->categorie->nom ?? 'N/A' }}</span>
                                        </div>

                                        <div class="progress mb-3" style="height: 5px;">
                                            @php
                                                $start = strtotime($annonce->date_debut);
                                                $end = strtotime($annonce->date_fin);
                                                $now = time();
                                                $total = $end - $start;
                                                $elapsed = $now - $start;
                                                $percentage = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
                                            @endphp
                                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-top-0">
                                        <a href="{{ route('annonces.show', $annonce) }}" class="btn bg-gradient-primary w-100">
                                            <i class="material-symbols-rounded">gavel</i> Participer à l'enchère
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="material-symbols-rounded" style="font-size: 64px;">gavel</i>
                                    <h5 class="mt-3">Aucune enchère active</h5>
                                    <p class="text-muted">Il n'y a pas d'enchères actives pour le moment. Revenez plus tard !</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $auctions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .auction-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            transition: transform 0.3s ease;
        }

        .auction-card:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
@endpush