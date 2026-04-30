{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs | BidMaster')
@section('page-title', 'Gestion des Utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('content')
    <div class="admin-users-container">
        {{-- Hero Section with Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total-users-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Utilisateurs</span>
                    <span class="stat-value" id="totalUsersCount">{{ $users->total() }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active-users-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Utilisateurs Actifs</span>
                    <span class="stat-value" id="activeUsersCount">
                        {{ $users->filter(function ($u) {
        return $u->client && $u->client->statut === 'ACTIF'; })->count() }}
                    </span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blocked-users-icon">
                    <i class="fas fa-user-lock"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Comptes Bloqués</span>
                    <span class="stat-value" id="blockedUsersCount">
                        {{ $users->filter(function ($u) {
        return $u->client && $u->client->statut === 'BLOQUE'; })->count() }}
                    </span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon premium-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total des Ventes (TND)</span>
                    <span class="stat-value">
                        {{ number_format($users->sum(function ($u) {
        return $u->client ? $u->client->solde : 0; }), 0) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="users-card">
            {{-- Header avec recherche et filtres --}}
            <div class="card-header-custom">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-user-astronaut"></i>
                    </div>
                    <h2 class="header-title">Portail des Utilisateurs</h2>
                    <div class="header-badge">{{ $users->total() }} membres</div>
                </div>
                <div class="header-right">
                    <div class="search-container">
                        <i class="fas fa-search search-icon-custom"></i>
                        <input type="text" id="userSearchInput" class="search-input-custom"
                            placeholder="Rechercher par nom, email ou rôle...">
                        <button id="clearSearchBtn" class="clear-search-btn" style="display: none;">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                    <div class="filter-dropdown">
                        <button id="filterBtn" class="filter-btn">
                            <i class="fas fa-sliders-h"></i> Filtre
                        </button>
                        <div id="filterMenu" class="filter-menu">
                            <div class="filter-option" data-filter="all">
                                <i class="fas fa-list"></i> Tous les utilisateurs
                            </div>
                            <div class="filter-option" data-filter="admin">
                                <i class="fas fa-user-shield"></i> Administrateurs
                            </div>
                            <div class="filter-option" data-filter="vendeur">
                                <i class="fas fa-store"></i> Vendeurs
                            </div>
                            <div class="filter-option" data-filter="client">
                                <i class="fas fa-user"></i> Clients
                            </div>
                            <div class="filter-divider"></div>
                            <div class="filter-option" data-filter="active">
                                <i class="fas fa-check-circle"></i> Actifs
                            </div>
                            <div class="filter-option" data-filter="blocked">
                                <i class="fas fa-ban"></i> Bloqués
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Cards Grid --}}
            <div class="users-grid" id="usersGrid">
                @forelse($users as $user)
                    @php
                        // Prepare modal data for this user
                        $clientData = $user->client ? [
                            'telephone' => $user->client->telephone,
                            'adresse_livraison' => $user->client->adresse_livraison,
                            'solde' => $user->client->solde,
                            'statut' => $user->client->statut,
                        ] : null;

                        $vendeurData = null;
                        if ($user->client && $user->client->vendeur) {
                            $vendeurData = [
                                'siret' => $user->client->vendeur->siret,
                                'note_moyenne' => $user->client->vendeur->note_moyenne,
                                'nombre_ventes' => $user->client->vendeur->nombre_ventes,
                                'annonces_count' => $user->client->vendeur->annonces()->count(),
                            ];
                        }

                        $modalData = [
                            'id' => $user->id,
                            'nom' => $user->nom,
                            'prenom' => $user->prenom,
                            'email' => $user->email,
                            'role' => $user->role,
                            'created_at' => $user->created_at->toIso8601String(),
                            'client' => $clientData,
                            'vendeur' => $vendeurData,
                        ];
                    @endphp
                    <div class="user-card" data-user-id="{{ $user->id }}" data-role="{{ $user->role }}"
                        data-status="{{ $user->client ? $user->client->statut : 'ACTIF' }}"
                        data-name="{{ strtolower($user->nom . ' ' . $user->prenom) }}"
                        data-email="{{ strtolower($user->email) }}" data-modal-data='{{ json_encode($modalData) }}'>
                        <div class="card-glow"></div>
                        <div class="card-inner">
                            <div class="card-top">
                                <div class="user-avatar-large">
                                    {{ strtoupper(substr($user->nom, 0, 1) . substr($user->prenom, 0, 1)) }}
                                    <div
                                        class="avatar-status-badge {{ $user->client && $user->client->statut === 'ACTIF' ? 'status-active' : ($user->client && $user->client->statut === 'BLOQUE' ? 'status-blocked' : 'status-inactive') }}">
                                    </div>
                                </div>
                                <div class="user-role-badge role-{{ $user->role }}">
                                    @if($user->role === 'admin')
                                        <i class="fas fa-crown"></i> Admin
                                    @elseif($user->role === 'vendeur')
                                        <i class="fas fa-store"></i> Vendeur
                                    @else
                                        <i class="fas fa-user"></i> Client
                                    @endif
                                </div>
                            </div>

                            <div class="card-body-custom">
                                <h3 class="user-name">{{ $user->nom }} {{ $user->prenom }}</h3>
                                <p class="user-email">
                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                </p>

                                <div class="user-details-grid">
                                    @if($user->client)
                                        <div class="detail-item">
                                            <span class="detail-label">Téléphone</span>
                                            <span class="detail-value">{{ $user->client->telephone ?? 'Non renseigné' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Solde</span>
                                            <span class="detail-value solde-value">{{ number_format($user->client->solde ?? 0, 2) }}
                                                TND</span>
                                        </div>
                                    @else
                                        <div class="detail-item full-width">
                                            <span class="detail-label">Compte</span>
                                            <span class="detail-value">Compte administrateur système</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="user-meta">
                                    <span class="meta-item">
                                        <i class="far fa-calendar-alt"></i> Inscrit le {{ $user->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-actions">
                                <button class="action-btn view-details" data-user-id="{{ $user->id }}">
                                    <i class="fas fa-eye"></i> Détails
                                </button>
                                <div class="dropdown">
                                    <button class="action-btn dropdown-toggle-btn" data-dropdown="dropdown-{{ $user->id }}">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu-custom" id="dropdown-{{ $user->id }}">
                                        @if($user->role != 'admin')
                                            @if($user->client && $user->client->statut == 'ACTIF')
                                                <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item-custom block-action">
                                                        <i class="fas fa-ban"></i> Bloquer l'utilisateur
                                                    </button>
                                                </form>
                                            @elseif($user->client && $user->client->statut == 'BLOQUE')
                                                <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item-custom unblock-action">
                                                        <i class="fas fa-check-circle"></i> Débloquer l'utilisateur
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item-custom delete-action">
                                                    <i class="fas fa-trash-alt"></i> Supprimer le compte
                                                </button>
                                            </form>
                                        @else
                                            <div class="dropdown-item-custom disabled">
                                                <i class="fas fa-shield-alt"></i> Actions limitées pour admin
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-custom">
                        <div class="empty-animation">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h3>Aucun utilisateur trouvé</h3>
                        <p>Il n'y a aucun utilisateur correspondant à vos critères.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="pagination-wrapper-custom">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Détails Utilisateur (Structure Dynamique) --}}
    <div id="userDetailsModal" class="custom-modal">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header-custom">
                <h3>Détails de l'utilisateur</h3>
                <button class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body-custom">
                <div class="modal-content-dynamic">
                    <!-- Content will be inserted here by JS -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ========== RESET & GLOBAL ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .admin-users-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 2rem;
        }

        /* ========== STATS GRID ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .total-users-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .active-users-icon {
            background: linear-gradient(135deg, #2dce89, #2dcecc);
        }

        .blocked-users-icon {
            background: linear-gradient(135deg, #f5365c, #f56036);
        }

        .premium-icon {
            background: linear-gradient(135deg, #ffd700, #ff8c00);
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #6c757d;
            display: block;
            margin-bottom: 0.3rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1;
        }

        /* ========== MAIN CARD ========== */
        .users-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .card-header-custom {
            padding: 1.8rem 2rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.03), rgba(118, 75, 162, 0.03));
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .header-title {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a1a2e, #4a4a6a);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 0;
        }

        .header-badge {
            background: #f0f2ff;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #667eea;
        }

        .header-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* ========== SEARCH ========== */
        .search-container {
            position: relative;
        }

        .search-input-custom {
            padding: 0.7rem 1rem 0.7rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.9rem;
            width: 280px;
            transition: all 0.3s;
            background: white;
        }

        .search-input-custom:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            width: 320px;
        }

        .search-icon-custom {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            pointer-events: none;
        }

        .clear-search-btn {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            display: none;
        }

        /* ========== FILTER ========== */
        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            padding: 0.7rem 1.5rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 60px;
            font-weight: 600;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            z-index: 100;
        }

        .filter-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .filter-option {
            padding: 0.7rem 1rem;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.85rem;
            color: #2d3748;
        }

        .filter-option:hover {
            background: #f7fafc;
            color: #667eea;
        }

        .filter-option.active {
            background: #667eea;
            color: white;
        }

        .filter-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0;
        }

        /* ========== USER CARDS GRID ========== */
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 2rem;
            padding: 2rem;
        }

        .user-card {
            position: relative;
            background: white;
            border-radius: 28px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .card-glow {
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 30px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
        }

        .user-card:hover .card-glow {
            opacity: 0.5;
        }

        .card-inner {
            position: relative;
            background: white;
            border-radius: 28px;
            padding: 1.8rem;
            z-index: 1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .user-card:hover .card-inner {
            transform: translateY(-6px);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.12);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.2rem;
        }

        .user-avatar-large {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            position: relative;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .avatar-status-badge {
            position: absolute;
            bottom: -3px;
            right: -3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .status-active {
            background: #2dce89;
        }

        .status-blocked {
            background: #f5365c;
        }

        .status-inactive {
            background: #adb5bd;
        }

        .user-role-badge {
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: #f0f2ff;
            color: #667eea;
        }

        .role-admin {
            background: linear-gradient(135deg, #f5365c, #f56036);
            color: white;
        }

        .role-vendeur {
            background: linear-gradient(135deg, #fb6340, #fbb140);
            color: white;
        }

        .role-client {
            background: linear-gradient(135deg, #11cdef, #1171ef);
            color: white;
        }

        .user-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.3rem;
        }

        .user-email {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 12px;
        }

        .detail-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #adb5bd;
            display: block;
        }

        .detail-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #2d3748;
        }

        .solde-value {
            color: #2dce89;
        }

        .user-meta {
            border-top: 1px solid #edf2f7;
            padding-top: 0.8rem;
            margin-top: 0.5rem;
        }

        .meta-item {
            font-size: 0.7rem;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 1.2rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.6rem;
            border-radius: 16px;
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .view-details {
            background: linear-gradient(135deg, #f0f2ff, #e9ecef);
            color: #667eea;
        }

        .view-details:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
        }

        .dropdown {
            position: relative;
        }

        .dropdown-toggle-btn {
            background: #f8f9fa;
            width: 42px;
        }

        .dropdown-menu-custom {
            position: absolute;
            bottom: 110%;
            right: 0;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s;
            z-index: 1000;
        }

        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item-custom {
            width: 100%;
            padding: 0.7rem 1rem;
            border: none;
            background: transparent;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            color: #2d3748;
        }

        .dropdown-item-custom:hover {
            background: #f7fafc;
        }

        .block-action {
            color: #f5365c;
        }

        .block-action:hover {
            background: #fff0f2;
        }

        .unblock-action {
            color: #2dce89;
        }

        .delete-action {
            color: #dc3545;
        }

        /* ========== MODAL CUSTOM ========== */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
            animation: fadeIn 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
        }

        .modal-container {
            position: relative;
            width: 90%;
            max-width: 700px;
            margin: 2rem auto;
            background: white;
            border-radius: 36px;
            overflow: hidden;
            animation: slideUp 0.3s;
            max-height: 85vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header-custom {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-body-custom {
            padding: 2rem;
        }

        .details-section {
            margin-bottom: 1.5rem;
        }

        .details-section h4 {
            font-size: 1rem;
            margin-bottom: 0.8rem;
            color: #667eea;
            border-left: 3px solid #667eea;
            padding-left: 0.8rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 20px;
        }

        .details-grid .full-width {
            grid-column: span 2;
        }

        /* ========== PAGINATION ========== */
        .pagination-wrapper-custom {
            padding: 1.5rem 2rem;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: flex-end;
        }

        .pagination-wrapper-custom .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }

        .pagination-wrapper-custom .page-item .page-link {
            padding: 0.5rem 0.9rem;
            border-radius: 12px;
            color: #4a5568;
            transition: all 0.2s;
            background: transparent;
        }

        .pagination-wrapper-custom .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state-custom {
            text-align: center;
            padding: 4rem;
        }

        .empty-animation {
            font-size: 5rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .admin-users-container {
                padding: 0 1rem 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .users-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .search-input-custom {
                width: 100%;
            }

            .card-header-custom {
                flex-direction: column;
                align-items: stretch;
            }

            .header-right {
                flex-direction: column;
            }

            .search-container {
                width: 100%;
            }

            .search-input-custom:focus {
                width: 100%;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .details-grid .full-width {
                grid-column: span 1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // ========== SEARCH & FILTER ==========
            const searchInput = document.getElementById('userSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const usersGrid = document.getElementById('usersGrid');
            const userCards = Array.from(document.querySelectorAll('.user-card'));
            const filterBtn = document.getElementById('filterBtn');
            const filterMenu = document.getElementById('filterMenu');
            let currentFilter = 'all';

            function filterUsers() {
                const searchTerm = searchInput.value.toLowerCase();
                let visibleCount = 0;
                userCards.forEach(card => {
                    const name = card.dataset.name || '';
                    const email = card.dataset.email || '';
                    const role = card.dataset.role;
                    const status = card.dataset.status;
                    let matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                    let matchesFilter = true;
                    if (currentFilter === 'admin') matchesFilter = role === 'admin';
                    else if (currentFilter === 'vendeur') matchesFilter = role === 'vendeur';
                    else if (currentFilter === 'client') matchesFilter = role === 'client';
                    else if (currentFilter === 'active') matchesFilter = status === 'ACTIF';
                    else if (currentFilter === 'blocked') matchesFilter = status === 'BLOQUE';
                    if (matchesSearch && matchesFilter) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                // update empty state
                const existingEmpty = document.querySelector('.no-results-msg');
                if (visibleCount === 0 && userCards.length > 0) {
                    if (!existingEmpty) {
                        const msg = document.createElement('div');
                        msg.className = 'empty-state-custom no-results-msg';
                        msg.innerHTML = '<div class="empty-animation"><i class="fas fa-search"></i></div><h3>Aucun résultat</h3><p>Aucun utilisateur ne correspond à votre recherche.</p>';
                        usersGrid.appendChild(msg);
                    }
                } else {
                    if (existingEmpty) existingEmpty.remove();
                }
                if (clearBtn) {
                    clearBtn.style.display = searchInput.value.length > 0 ? 'flex' : 'none';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterUsers);
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        filterUsers();
                        clearBtn.style.display = 'none';
                    });
                }
            }

            // Filter dropdown
            if (filterBtn) {
                filterBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    filterMenu.classList.toggle('show');
                });
                document.addEventListener('click', () => {
                    filterMenu.classList.remove('show');
                });
                filterMenu.querySelectorAll('.filter-option').forEach(opt => {
                    opt.addEventListener('click', (e) => {
                        const filterVal = opt.dataset.filter;
                        currentFilter = filterVal;
                        filterUsers();
                        filterMenu.classList.remove('show');
                        // update active style
                        filterMenu.querySelectorAll('.filter-option').forEach(o => o.classList.remove('active'));
                        opt.classList.add('active');
                    });
                });
            }

            // ========== DROPDOWNS MANAGEMENT ==========
            document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const dropdownId = btn.dataset.dropdown;
                    const targetMenu = document.getElementById(dropdownId);
                    if (targetMenu) {
                        // Close all other dropdowns
                        document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                            if (menu !== targetMenu) menu.classList.remove('show');
                        });
                        targetMenu.classList.toggle('show');
                    }
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                    menu.classList.remove('show');
                });
            });

            // ========== MODAL WITH EMBEDDED DATA ==========
            const modal = document.getElementById('userDetailsModal');
            const modalContent = modal.querySelector('.modal-content-dynamic');
            const closeModalBtn = modal.querySelector('.modal-close-btn');
            const modalOverlay = modal.querySelector('.modal-overlay');

            function openModal(userId) {
                // Find the user card with matching data-user-id
                const userCard = document.querySelector(`.user-card[data-user-id="${userId}"]`);
                if (!userCard) return;

                const modalDataRaw = userCard.getAttribute('data-modal-data');
                if (!modalDataRaw) return;

                let user;
                try {
                    user = JSON.parse(modalDataRaw);
                } catch (e) {
                    console.error('Invalid modal data', e);
                    return;
                }

                // Render content
                let clientHtml = '';
                if (user.client) {
                    clientHtml = `
                                        <div class="details-section">
                                            <h4><i class="fas fa-shopping-cart"></i> Informations client</h4>
                                            <div class="details-grid">
                                                <div><strong>Téléphone:</strong> ${user.client.telephone || 'Non renseigné'}</div>
                                                <div><strong>Adresse:</strong> ${user.client.adresse_livraison || 'Non renseignée'}</div>
                                                <div><strong>Solde:</strong> ${new Intl.NumberFormat().format(user.client.solde || 0)} TND</div>
                                                <div><strong>Statut client:</strong> ${user.client.statut || 'ACTIF'}</div>
                                            </div>
                                        </div>
                                    `;
                }
                let vendeurHtml = '';
                if (user.vendeur) {
                    vendeurHtml = `
                                        <div class="details-section">
                                            <h4><i class="fas fa-store"></i> Informations vendeur</h4>
                                            <div class="details-grid">
                                                <div><strong>SIRET:</strong> ${user.vendeur.siret || 'Non renseigné'}</div>
                                                <div><strong>Note moyenne:</strong> ${parseFloat(user.vendeur.note_moyenne || 0).toFixed(1)} / 5</div>
                                                <div><strong>Nombre ventes:</strong> ${user.vendeur.nombre_ventes || 0}</div>
                                                <div><strong>Annonces:</strong> ${user.vendeur.annonces_count || 0}</div>
                                            </div>
                                        </div>
                                    `;
                }

                modalContent.innerHTML = `
                                    <div class="details-section">
                                        <h4><i class="fas fa-user-circle"></i> Informations personnelles</h4>
                                        <div class="details-grid">
                                            <div><strong>Nom complet:</strong> ${escapeHtml(user.nom)} ${escapeHtml(user.prenom)}</div>
                                            <div><strong>Email:</strong> ${escapeHtml(user.email)}</div>
                                            <div><strong>Rôle:</strong> ${escapeHtml(user.role)}</div>
                                            <div><strong>Inscrit le:</strong> ${new Date(user.created_at).toLocaleDateString()}</div>
                                        </div>
                                    </div>
                                    ${clientHtml}
                                    ${vendeurHtml}
                                `;

                modal.style.display = 'block';
            }

            // Helper to escape HTML to prevent XSS
            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function (m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }

            function closeModal() {
                modal.style.display = 'none';
            }

            document.querySelectorAll('.view-details').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const userId = btn.dataset.userId;
                    openModal(userId);
                });
            });

            closeModalBtn.addEventListener('click', closeModal);
            modalOverlay.addEventListener('click', closeModal);
        })();
    </script>
@endpush