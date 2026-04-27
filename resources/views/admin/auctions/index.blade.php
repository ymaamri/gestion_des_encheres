{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/auctions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Enchères')
@section('page-title', 'Gestion des Enchères')
@section('breadcrumb', 'Enchères')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4 border-0 shadow-sm rounded-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-theme shadow-lg border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center rounded-4">
                    <h6 class="text-white text-capitalize ps-3 mb-0 fw-bold">
                        <i class="material-symbols-rounded me-1 align-middle">gavel</i> Toutes les Enchères
                    </h6>
                    <div class="me-3">
                        <select id="statusFilter" class="form-control form-control-sm w-auto d-inline-block me-2 rounded-3 border-0 bg-white text-dark fw-semibold" style="padding: 0.4rem 1rem;">
                            <option value="">Tous les statuts</option>
                            <option value="EN_ATTENTE">En attente</option>
                            <option value="ACTIVE">Active</option>
                            <option value="CLOTUREE">Clôturée</option>
                            <option value="BLOQUEE">Bloquée</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="auctionsTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Annonce</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vendeur</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix départ</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix actuel</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date fin</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enchères</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auctions as $annonce)
                            <tr data-status="{{ $annonce->statut }}" class="align-middle">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        @php
                                            $photos = $annonce->produit->photos ?? [];
                                            $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/40x40';
                                        @endphp
                                        <div>
                                            <img src="{{ $firstPhoto }}" class="avatar avatar-sm me-3 border-radius-lg" alt="produit" style="width: 40px; height: 40px; object-fit: cover; border-radius: 10px;">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm fw-bold text-dark">{{ Str::limit($annonce->titre, 40) }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-dark">{{ $annonce->vendeur->client->nom }}</p>
                                    <p class="text-xs text-secondary mb-0">Note: {{ number_format($annonce->vendeur->note_moyenne, 1) }}/5</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ number_format($annonce->prix_depart, 2) }} MAD</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-primary text-xs font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }} MAD</span>
                                </td>
                                <td class="align-middle text-center">
                                    @switch($annonce->statut)
                                        @case('EN_ATTENTE')
                                            <span class="badge badge-sm bg-gradient-warning px-3 py-1 rounded-pill">En attente</span>
                                            @break
                                        @case('ACTIVE')
                                            <span class="badge badge-sm bg-gradient-success px-3 py-1 rounded-pill">Active</span>
                                            @break
                                        @case('CLOTUREE')
                                            <span class="badge badge-sm bg-gradient-secondary px-3 py-1 rounded-pill">Clôturée</span>
                                            @break
                                        @case('BLOQUEE')
                                            <span class="badge badge-sm bg-gradient-danger px-3 py-1 rounded-pill">Bloquée</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm bg-gradient-info px-3 py-1 rounded-pill">{{ $annonce->encheres()->count() }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 shadow-sm border-0 rounded-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('annonces.show', $annonce) }}" target="_blank">
                                                    <i class="material-symbols-rounded me-2">visibility</i> Voir
                                                </a>
                                            </li>
                                            @if($annonce->statut == 'EN_ATTENTE')
                                                <li>
                                                    <form method="POST" action="{{ route('admin.auctions.publish', $annonce) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-radius-md text-success">
                                                            <i class="material-symbols-rounded me-2">publish</i> Publier
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if($annonce->statut == 'ACTIVE')
                                                <li>
                                                    <form method="POST" action="{{ route('admin.auctions.block', $annonce) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-radius-md text-danger" onclick="return confirm('Bloquer cette enchère ?')">
                                                            <i class="material-symbols-rounded me-2">block</i> Bloquer
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if($annonce->statut == 'BLOQUEE')
                                                <li>
                                                    <form method="POST" action="{{ route('admin.auctions.publish', $annonce) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-radius-md text-success">
                                                            <i class="material-symbols-rounded me-2">check_circle</i> Débloquer
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="material-symbols-rounded" style="font-size: 48px; color: #cbd5e0;">gavel</i>
                                    <p class="text-secondary mt-2">Aucune enchère trouvée.</p>
                                 </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $auctions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('statusFilter').addEventListener('change', function() {
        let filterValue = this.value;
        let tableRows = document.querySelectorAll('#auctionsTable tbody tr');
        
        tableRows.forEach(row => {
            if (filterValue === '' || row.dataset.status === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush