{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Annonces')
@section('page-title', 'Mes Annonces')
@section('breadcrumb', 'Annonces')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Mes Annonces d'Enchères</h6>
                    <a href="{{ route('annonces.create') }}" class="btn btn-sm bg-gradient-success text-white me-3 mb-0">
                        <i class="material-symbols-rounded">add</i> Nouvelle Annonce
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Produit</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix de Départ</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix Actuel</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de Fin</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($annonces as $annonce)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            @php
                                                $photos = $annonce->produit->photos ?? [];
                                                $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/40x40';
                                            @endphp
                                            <img src="{{ $firstPhoto }}" class="avatar avatar-sm me-3 border-radius-lg" alt="produit">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ Str::limit($annonce->titre, 40) }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $annonce->produit->marque }} {{ $annonce->produit->modele }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $annonce->produit->etat }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ number_format($annonce->prix_depart, 2) }} MAD</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }} MAD</span>
                                </td>
                                <td class="align-middle text-center">
                                    @switch($annonce->statut)
                                        @case('EN_ATTENTE')
                                            <span class="badge badge-sm bg-gradient-warning">En Attente</span>
                                            @break
                                        @case('ACTIVE')
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                            @break
                                        @case('CLOTUREE')
                                            <span class="badge badge-sm bg-gradient-secondary">Clôturée</span>
                                            @break
                                        @case('BLOQUEE')
                                            <span class="badge badge-sm bg-gradient-danger">Bloquée</span>
                                            @break
                                        @case('ANNULEE')
                                            <span class="badge badge-sm bg-gradient-dark">Annulée</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($annonce->date_fin)->diffForHumans() }}</small>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('annonces.show', $annonce) }}">
                                                    <i class="material-symbols-rounded me-2">visibility</i> Voir
                                                </a>
                                            </li>
                                            @if($annonce->statut === 'EN_ATTENTE')
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('annonces.edit', $annonce) }}">
                                                    <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">
                                                        <i class="material-symbols-rounded me-2">delete</i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-secondary mb-0">Aucune annonce trouvée.</p>
                                    <a href="{{ route('annonces.create') }}" class="btn btn-sm bg-gradient-primary mt-3">Créer votre première annonce</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $annonces->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection