{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Header avec recherche --}}
            <div class="card-header bg-gradient-theme p-4 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="text-white mb-0 fw-bold d-flex align-items-center">
                            <span class="icon-circle bg-white bg-opacity-25 me-3">
                                <i class="fas fa-users text-white"></i>
                            </span>
                            Liste des Utilisateurs
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control search-input" id="userSearch" placeholder="Rechercher un utilisateur...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-custom mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th class="ps-4">Utilisateur</th>
                                <th>Email</th>
                                <th class="text-center">Rôle</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Inscription</th>
                                <th class="text-end pe-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($user->nom, 0, 1) . substr($user->prenom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $user->nom }} {{ $user->prenom }}</h6>
                                            <span class="text-muted small">ID: {{ $user->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark small fw-medium">{{ $user->email }}</span>
                                </td>
                                <td class="text-center">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge-role badge-admin">Admin</span>
                                            @break
                                        @case('vendeur')
                                            <span class="badge-role badge-vendeur">Vendeur</span>
                                            @break
                                        @default
                                            <span class="badge-role badge-client">Client</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @php
                                        $statut = $user->client ? $user->client->statut : 'ACTIF';
                                    @endphp
                                    @if($statut == 'ACTIF')
                                        <span class="badge-status badge-active">Actif</span>
                                    @elseif($statut == 'BLOQUE')
                                        <span class="badge-status badge-blocked">Bloqué</span>
                                    @else
                                        <span class="badge-status badge-inactive">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary small">{{ $user->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-action" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                                            <li>
                                                <a class="dropdown-item rounded-3" href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                    <i class="fas fa-eye me-2"></i> Détails
                                                </a>
                                            </li>
                                            @if($user->role != 'admin')
                                                @if($user->client && $user->client->statut == 'ACTIF')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item rounded-3 text-warning">
                                                                <i class="fas fa-ban me-2"></i> Bloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @elseif($user->client && $user->client->statut == 'BLOQUE')
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item rounded-3 text-success">
                                                                <i class="fas fa-check-circle me-2"></i> Débloquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Détails Utilisateur -->
                            <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow-lg">
                                        <div class="modal-header bg-gradient-theme text-white border-0 rounded-top-4 px-4 py-3">
                                            <h5 class="modal-title fw-bold d-flex align-items-center">
                                                <div class="avatar-circle bg-white bg-opacity-25 me-2">
                                                    {{ strtoupper(substr($user->nom, 0, 1) . substr($user->prenom, 0, 1)) }}
                                                </div>
                                                {{ $user->nom }} {{ $user->prenom }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 bg-light">
                                            <div class="row g-4">
                                                {{-- Colonne gauche : Infos générales --}}
                                                <div class="col-md-6">
                                                    <div class="info-card h-100">
                                                        <h6 class="fw-bold text-primary mb-3">
                                                            <i class="fas fa-user-circle me-2"></i>Informations personnelles
                                                        </h6>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Nom complet</span>
                                                                <strong>{{ $user->nom }} {{ $user->prenom }}</strong>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Email</span>
                                                                <strong>{{ $user->email }}</strong>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Rôle</span>
                                                                <span class="badge-role badge-{{ $user->role == 'admin' ? 'admin' : ($user->role == 'vendeur' ? 'vendeur' : 'client') }}">
                                                                    {{ ucfirst($user->role) }}
                                                                </span>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-0">
                                                                <span class="text-muted">Inscrit le</span>
                                                                <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- Colonne droite : Infos client si applicable --}}
                                                @if($user->client)
                                                <div class="col-md-6">
                                                    <div class="info-card h-100">
                                                        <h6 class="fw-bold text-primary mb-3">
                                                            <i class="fas fa-shopping-cart me-2"></i>Informations client
                                                        </h6>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Téléphone</span>
                                                                <strong>{{ $user->client->telephone ?? 'Non renseigné' }}</strong>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Adresse</span>
                                                                <strong>{{ $user->client->adresse_livraison ?? 'Non renseignée' }}</strong>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Solde</span>
                                                                <strong class="text-success">{{ number_format($user->client->solde ?? 0, 2) }} MAD</strong>
                                                            </li>
                                                            <li class="d-flex justify-content-between mb-0">
                                                                <span class="text-muted">Statut</span>
                                                                @php $clientStatut = $user->client->statut ?? 'ACTIF'; @endphp
                                                                <span class="badge-status badge-{{ strtolower($clientStatut) == 'actif' ? 'active' : (strtolower($clientStatut) == 'bloque' ? 'blocked' : 'inactive') }}">
                                                                    {{ $clientStatut }}
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Vendeur --}}
                                                @if($user->role == 'vendeur' && $user->client && $user->client->vendeur)
                                                <div class="col-12 mt-2">
                                                    <div class="info-card">
                                                        <h6 class="fw-bold text-primary mb-3">
                                                            <i class="fas fa-store me-2"></i>Informations vendeur
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-flex justify-content-between mb-2">
                                                                        <span class="text-muted">SIRET</span>
                                                                        <strong>{{ $user->client->vendeur->siret ?? 'Non renseigné' }}</strong>
                                                                    </li>
                                                                    <li class="d-flex justify-content-between mb-0">
                                                                        <span class="text-muted">Note moyenne</span>
                                                                        <strong>{{ number_format($user->client->vendeur->note_moyenne ?? 0, 1) }} / 5.0</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-flex justify-content-between mb-2">
                                                                        <span class="text-muted">Ventes</span>
                                                                        <strong>{{ $user->client->vendeur->nombre_ventes ?? 0 }}</strong>
                                                                    </li>
                                                                    <li class="d-flex justify-content-between mb-0">
                                                                        <span class="text-muted">Annonces</span>
                                                                        <strong>{{ $user->client->vendeur->annonces()->count() }}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                            <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-users-slash"></i>
                                        </div>
                                        <h6 class="fw-bold text-secondary mt-3">Aucun utilisateur trouvé</h6>
                                        <p class="text-muted small">La liste des utilisateurs apparaîtra ici.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                <div class="px-4 py-3 d-flex justify-content-end border-top">
                    {{ $users->links() }}
                </div>
                @endif
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
    /* ----- Design System ----- */
    :root {
        --gradient-theme: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-admin: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
        --gradient-vendeur: linear-gradient(135deg, #fb6340 0%, #fbb140 100%);
        --gradient-client: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);
        --gradient-active: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%);
        --gradient-blocked: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
        --gradient-inactive: linear-gradient(135deg, #8898aa 0%, #adb5bd 100%);
        --shadow-card: 0 8px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 12px 40px rgba(0,0,0,0.08);
    }

    .bg-gradient-theme {
        background: var(--gradient-theme) !important;
    }

    /* ----- Card & header ----- */
    .card {
        box-shadow: var(--shadow-card);
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: var(--shadow-hover);
    }

    /* Search bar */
    .search-wrapper {
        position: relative;
        max-width: 350px;
        margin-left: auto;
    }
    .search-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.7);
        font-size: 1rem;
        z-index: 2;
    }
    .search-input {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px;
        padding: 0.55rem 1rem 0.55rem 2.7rem;
        color: white;
        font-size: 0.875rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .search-input::placeholder {
        color: rgba(255,255,255,0.7);
    }
    .search-input:focus {
        background: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.5);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
        color: white;
        outline: none;
    }

    /* Icon circle in header */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ----- Table customisation ----- */
    .table-custom thead th {
        background: #f8f9fe;
        color: #32325d;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid rgba(102, 126, 234, 0.2);
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f5;
    }
    .table-custom tbody tr:last-child {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background-color: #f4f6ff;
        box-shadow: inset 5px 0 0 #667eea;
    }

    .table-custom td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* Avatar cercle */
    .avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--gradient-theme);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    /* Badges rôles */
    .badge-role {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.4em 0.9em;
        border-radius: 30px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: white;
        display: inline-block;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    }
    .badge-admin {
        background: var(--gradient-admin);
    }
    .badge-vendeur {
        background: var(--gradient-vendeur);
    }
    .badge-client {
        background: var(--gradient-client);
    }

    /* Badges statut */
    .badge-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.4em 0.9em;
        border-radius: 30px;
        color: white;
        display: inline-block;
        box-shadow: 0 4px 8px rgba(0,0,0,0.06);
    }
    .badge-active {
        background: var(--gradient-active);
    }
    .badge-blocked {
        background: var(--gradient-blocked);
    }
    .badge-inactive {
        background: var(--gradient-inactive);
    }

    /* Action button */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #8898aa;
        background: transparent;
        border: none;
        transition: all 0.2s;
    }
    .btn-action:hover {
        background: #f4f6ff;
        color: #667eea;
    }

    /* Dropdown menu */
    .dropdown-menu {
        animation: fadeInDown 0.2s ease;
        min-width: 180px;
    }
    .dropdown-item {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.55rem 1rem;
        transition: background 0.15s;
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    .dropdown-item:hover {
        background-color: #f8f9fe;
    }

    /* Empty state */
    .empty-state {
        padding: 2rem;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f4f6ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #b0b8d1;
        margin-bottom: 1rem;
    }

    /* Info cards in modal */
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f5;
        transition: box-shadow 0.2s;
    }
    .info-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }
    .info-card h6 {
        font-size: 0.95rem;
    }
    .info-card ul li {
        font-size: 0.85rem;
    }

    /* Modal animation */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Pagination style */
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        border-radius: 10px;
        margin: 0 3px;
        border: none;
        color: #667eea;
        background: transparent;
        transition: all 0.2s;
    }
    .page-item.active .page-link {
        background: var(--gradient-theme);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    .page-link:hover {
        background: #f4f6ff;
        color: #667eea;
    }
</style>
@endpush