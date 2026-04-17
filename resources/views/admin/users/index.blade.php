{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Liste des Utilisateurs</h6>
                    <div class="me-3">
                        <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text text-body">
                                <i class="material-symbols-rounded">search</i>
                            </span>
                            <input type="text" class="form-control" id="userSearch" placeholder="Rechercher...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Utilisateur</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rôle</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date d'inscription</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->nom }} {{ $user->prenom }}</h6>
                                            <p class="text-xs text-secondary mb-0">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge badge-sm bg-gradient-danger">Admin</span>
                                            @break
                                        @case('vendeur')
                                            <span class="badge badge-sm bg-gradient-warning">Vendeur</span>
                                            @break
                                        @default
                                            <span class="badge badge-sm bg-gradient-info">Client</span>
                                    @endswitch
                                </td>
                                <td class="align-middle text-center">
                                    @php
                                        $statut = $user->client ? $user->client->statut : 'ACTIF';
                                    @endphp
                                    @if($statut == 'ACTIF')
                                        <span class="badge badge-sm bg-gradient-success">Actif</span>
                                    @elseif($statut == 'BLOQUE')
                                        <span class="badge badge-sm bg-gradient-danger">Bloqué</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                    <i class="material-symbols-rounded me-2">visibility</i> Détails
                                                </a>
                                            </li>
                                            @if($user->role != 'admin')
                                                @if($user->client && $user->client->statut == 'ACTIF')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item border-radius-md text-warning">
                                                                <i class="material-symbols-rounded me-2">block</i> Bloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @elseif($user->client && $user->client->statut == 'BLOQUE')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item border-radius-md text-success">
                                                                <i class="material-symbols-rounded me-2">check_circle</i> Débloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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

                            <!-- User Details Modal -->
                            <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-dark text-white">
                                            <h5 class="modal-title">Détails de l'utilisateur</h5>
                                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Informations personnelles</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>Nom complet:</th>
                                                            <td>{{ $user->nom }} {{ $user->prenom }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email:</th>
                                                            <td>{{ $user->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Rôle:</th>
                                                            <td>{{ ucfirst($user->role) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Inscrit le:</th>
                                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                @if($user->client)
                                                <div class="col-md-6">
                                                    <h6>Informations client</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>Téléphone:</th>
                                                            <td>{{ $user->client->telephone ?? 'Non renseigné' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Adresse:</th>
                                                            <td>{{ $user->client->adresse_livraison ?? 'Non renseignée' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Solde:</th>
                                                            <td>{{ number_format($user->client->solde ?? 0, 2) }} MAD</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Statut:</th>
                                                            <td>{{ $user->client->statut ?? 'ACTIF' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                @endif
                                            </div>
                                            @if($user->role == 'vendeur' && $user->client && $user->client->vendeur)
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6>Informations vendeur</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>SIRET:</th>
                                                            <td>{{ $user->client->vendeur->siret ?? 'Non renseigné' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Note moyenne:</th>
                                                            <td>{{ number_format($user->client->vendeur->note_moyenne ?? 0, 1) }} / 5.0</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Nombre de ventes:</th>
                                                            <td>{{ $user->client->vendeur->nombre_ventes ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Annonces:</th>
                                                            <td>{{ $user->client->vendeur->annonces()->count() }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="material-symbols-rounded" style="font-size: 48px;">people</i>
                                    <p class="text-secondary mt-2">Aucun utilisateur trouvé.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('userSearch').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#usersTable tbody tr');
        
        tableRows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection