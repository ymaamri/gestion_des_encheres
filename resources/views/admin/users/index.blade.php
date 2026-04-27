{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4 border-0 shadow-sm rounded-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-theme shadow-lg border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center rounded-4">
                    <h6 class="text-white text-capitalize ps-3 mb-0 fw-bold">
                        <i class="fas fa-users me-1"></i> Liste des Utilisateurs
                    </h6>
                    <div class="me-3">
                        <div class="input-group input-group-sm w-auto bg-white rounded-3 overflow-hidden shadow-sm">
                            <span class="input-group-text bg-transparent border-0 text-secondary">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-transparent" id="userSearch" placeholder="Rechercher...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Utilisateur</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rôle</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date d'inscription</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="align-middle">
                                <td class="px-4">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-sm fw-bold text-dark">{{ $user->nom }} {{ $user->prenom }}</h6>
                                        <p class="text-xs text-secondary mb-0">ID: {{ $user->id }}</p>
                                    </div>
                                 </td>
                                 <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                 </td>
                                 <td class="align-middle text-center">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge bg-gradient-danger px-3 py-1 rounded-pill">Admin</span>
                                            @break
                                        @case('vendeur')
                                            <span class="badge bg-gradient-warning px-3 py-1 rounded-pill">Vendeur</span>
                                            @break
                                        @default
                                            <span class="badge bg-gradient-info px-3 py-1 rounded-pill">Client</span>
                                    @endswitch
                                 </td>
                                 <td class="align-middle text-center">
                                    @php
                                        $statut = $user->client ? $user->client->statut : 'ACTIF';
                                    @endphp
                                    @if($statut == 'ACTIF')
                                        <span class="badge bg-gradient-success px-3 py-1 rounded-pill">Actif</span>
                                    @elseif($statut == 'BLOQUE')
                                        <span class="badge bg-gradient-danger px-3 py-1 rounded-pill">Bloqué</span>
                                    @else
                                        <span class="badge bg-gradient-secondary px-3 py-1 rounded-pill">Inactif</span>
                                    @endif
                                 </td>
                                 <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                 </td>
                                 <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 shadow-sm border-0 rounded-3">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                    <i class="fas fa-eye me-2"></i> Détails
                                                </a>
                                            </li>
                                            @if($user->role != 'admin')
                                                @if($user->client && $user->client->statut == 'ACTIF')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-ban me-2"></i> Bloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @elseif($user->client && $user->client->statut == 'BLOQUE')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle me-2"></i> Débloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> Supprimer
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
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow-lg">
                                        <div class="modal-header bg-gradient-theme text-white rounded-top-4">
                                            <h5 class="modal-title fw-bold">Détails de l'utilisateur</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-primary"><i class="fas fa-user-circle me-1"></i> Informations personnelles</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <th class="text-secondary">Nom complet:</th>
                                                            <td class="text-dark"><strong>{{ $user->nom }} {{ $user->prenom }}</strong></td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Email:</th>
                                                            <td class="text-dark">{{ $user->email }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Rôle:</th>
                                                            <td class="text-dark">{{ ucfirst($user->role) }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Inscrit le:</th>
                                                            <td class="text-dark">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                                         </tr>
                                                     </table>
                                                </div>
                                                @if($user->client)
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-primary"><i class="fas fa-shopping-cart me-1"></i> Informations client</h6>
                                                    <table class="table table-sm table-borderless">
                                                         <tr>
                                                            <th class="text-secondary">Téléphone:</th>
                                                            <td class="text-dark">{{ $user->client->telephone ?? 'Non renseigné' }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Adresse:</th>
                                                            <td class="text-dark">{{ $user->client->adresse_livraison ?? 'Non renseignée' }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Solde:</th>
                                                            <td class="text-success fw-bold">{{ number_format($user->client->solde ?? 0, 2) }} MAD</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Statut:</th>
                                                            <td class="text-dark">{{ $user->client->statut ?? 'ACTIF' }}</td>
                                                         </tr>
                                                    </table>
                                                </div>
                                                @endif
                                            </div>
                                            @if($user->role == 'vendeur' && $user->client && $user->client->vendeur)
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <h6 class="fw-bold text-primary"><i class="fas fa-store me-1"></i> Informations vendeur</h6>
                                                    <table class="table table-sm table-borderless">
                                                         <tr>
                                                            <th class="text-secondary">SIRET:</th>
                                                            <td class="text-dark">{{ $user->client->vendeur->siret ?? 'Non renseigné' }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Note moyenne:</th>
                                                            <td class="text-dark">{{ number_format($user->client->vendeur->note_moyenne ?? 0, 1) }} / 5.0</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Nombre de ventes:</th>
                                                            <td class="text-dark">{{ $user->client->vendeur->nombre_ventes ?? 0 }}</td>
                                                         </tr>
                                                         <tr>
                                                            <th class="text-secondary">Annonces:</th>
                                                            <td class="text-dark">{{ $user->client->vendeur->annonces()->count() }}</td>
                                                         </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-secondary">Aucun utilisateur trouvé.</p>
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
@endsection

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
    .dropdown-item {
        cursor: pointer;
        transition: background 0.2s;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush