{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')
@section('breadcrumb', 'Dashboard')

@section('content')
    @auth
        @role('admin')
        <!-- Admin Dashboard -->
        <div class="row">
            <!-- Welcome Section -->
            <div class="col-12 mb-4">
                <div class="card bg-gradient-theme shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2 fw-bold">Bonjour, {{ Auth::user()->nom }}! 👋</h4>
                                <p class="text-white opacity-8 mb-0">Bienvenue sur votre tableau de bord administrateur. Voici
                                    un résumé de votre plateforme d'enchères.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="material-symbols-rounded text-white" style="font-size: 64px;">analytics</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Utilisateurs</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['total_users'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #48bb78;">+12% ce mois</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #667eea;">people</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Gérer les utilisateurs →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Total Enchères</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['total_auctions'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #4299e1;">
                                    {{ $stats['active_auctions'] ?? 0 }} actives</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(66, 153, 225, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #4299e1;">gavel</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('admin.auctions.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Gérer les enchères →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Total Offres</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ number_format($stats['total_bids'] ?? 0) }}
                                </h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #ed8936;">Volume:
                                    {{ number_format(($stats['total_bids'] ?? 0) * 1000, 0) }} MAD</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(237, 137, 54, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #ed8936;">paid</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('admin.auctions.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Voir les offres →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Catégories</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ \App\Models\Categorie::count() }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #f56565;">
                                    {{ \App\Models\SousCategorie::count() }} sous-catégories</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(245, 101, 101, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #f56565;">category</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('admin.categories.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Gérer les catégories →</a>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div
                        class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="material-symbols-rounded me-2"
                                style="color: #667eea;">people</i> Derniers Utilisateurs Inscrits</h6>
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea; text-decoration: none;">Voir tout →</a>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">
                                            Utilisateur</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rôle
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm fw-bold text-dark">{{ $user->nom }}
                                                            {{ $user->prenom }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'vendeur' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-secondary text-xs">{{ $user->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Auctions -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div
                        class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="material-symbols-rounded me-2"
                                style="color: #667eea;">gavel</i> Dernières Enchères</h6>
                        <a href="{{ route('admin.auctions.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea; text-decoration: none;">Voir tout →</a>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">
                                            Annonce</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix
                                            Actuel</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Annonce::with('produit')->latest()->take(5)->get() as $annonce)
                                        @php
                                            $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        @endphp
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ $productImage }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="product"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm fw-bold text-dark">
                                                            {{ Str::limit($annonce->titre, 25) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-primary text-sm font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }}
                                                    MAD</span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $annonce->statut == 'ACTIVE' ? 'success' : ($annonce->statut == 'CLOTUREE' ? 'secondary' : 'warning') }}">
                                                    {{ $annonce->statut }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @role('vendeur')
        <!-- Seller Dashboard -->
        <div class="row">
            <!-- Welcome Banner -->
            <div class="col-12 mb-4">
                <div class="card bg-gradient-theme shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2 fw-bold">Bonjour, {{ Auth::user()->nom }}! 🛒</h4>
                                <p class="text-white opacity-8 mb-0">Gérez vos ventes, suivez vos performances et créez de
                                    nouvelles annonces.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('annonces.create') }}"
                                    class="btn-gradient d-inline-block text-decoration-none">
                                    <i class="material-symbols-rounded align-middle me-1">add_circle</i> Nouvelle Annonce
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Mes Annonces</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['total_listings'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #667eea;">
                                    {{ $stats['active_listings'] ?? 0 }} annonces actives</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #667eea;">inventory_2</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('annonces.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Gérer mes annonces →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Ventes Totales</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['total_sales'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #4299e1;">
                                    +{{ ($stats['total_sales'] ?? 0) * 15 }}% ce mois</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(66, 153, 225, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #4299e1;">store</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Note Moyenne</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ number_format($stats['rating'] ?? 0, 1) }}/5
                                </h2>
                                <p class="text-xs mb-0">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="material-symbols-rounded"
                                            style="font-size: 14px; color: {{ $i <= round($stats['rating'] ?? 0) ? '#f6ad55' : '#e2e8f0' }}">star</i>
                                    @endfor
                                </p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(246, 173, 85, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #f6ad55;">star</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Chiffre d'Affaires</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">
                                    {{ number_format(($stats['total_sales'] ?? 0) * 1000, 0) }} MAD</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #4fd1c5;">Mois en cours</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(79, 209, 197, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #4fd1c5;">payments</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Products List -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div
                        class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="material-symbols-rounded me-2"
                                style="color: #667eea;">inventory_2</i> Mes Annonces Récentes</h6>
                        <a href="{{ route('annonces.index') }}" class="text-sm font-weight-bold"
                            style="color: #667eea; text-decoration: none;">Voir toutes mes annonces →</a>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">
                                            Produit</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Prix Actuel</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Enchères</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Statut</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Fin dans</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sellerAuctions = Auth::user()->client->vendeur->annonces()->with('produit', 'encheres')->latest()->take(5)->get();
                                    @endphp
                                    @forelse($sellerAuctions as $annonce)
                                        @php
                                            $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        @endphp
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex px-2 py-1">
                                                    <img src="{{ $productImage }}" class="avatar avatar-sm me-3" alt="product"
                                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm fw-bold text-dark">
                                                            {{ Str::limit($annonce->titre, 30) }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-primary text-sm font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }}
                                                    MAD</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge"
                                                    style="background-color: #764ba2; border-radius: 8px; font-weight: 600; padding: 6px 10px; color: white;">{{ $annonce->encheres()->count() }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($annonce->statut == 'ACTIVE')
                                                    <span class="badge"
                                                        style="background-color: #48bb78; border-radius: 8px; font-weight: 600; padding: 6px 10px; color: white;">ACTIVE</span>
                                                @elseif($annonce->statut == 'EN_ATTENTE')
                                                    <span class="badge"
                                                        style="background-color: #ed8936; border-radius: 8px; font-weight: 600; padding: 6px 10px; color: white;">EN
                                                        ATTENTE</span>
                                                @elseif($annonce->statut == 'CLOTUREE')
                                                    <span class="badge"
                                                        style="background-color: #718096; border-radius: 8px; font-weight: 600; padding: 6px 10px; color: white;">CLÔTURÉE</span>
                                                @else
                                                    <span class="badge"
                                                        style="background-color: #f56565; border-radius: 8px; font-weight: 600; padding: 6px 10px; color: white;">BLOQUÉE</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($annonce->statut == 'ACTIVE')
                                                    <span class="text-sm font-weight-bold"
                                                        style="color: #667eea;">{{ \Carbon\Carbon::parse($annonce->date_fin)->diffForHumans() }}</span>
                                                @else
                                                    <span class="text-sm font-weight-bold text-secondary">Terminée</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('annonces.show', $annonce) }}" class="text-secondary"
                                                    style="font-size: 20px;">
                                                    <i class="material-symbols-rounded">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center">
                                                    <i class="material-symbols-rounded text-secondary mb-3"
                                                        style="font-size: 48px; opacity: 0.5;">inventory_2</i>
                                                    <h6 class="text-dark font-weight-bold">Aucune annonce trouvée</h6>
                                                    <p class="text-sm text-secondary mb-3">Commencez à vendre vos produits dès
                                                        maintenant.</p>
                                                    <a href="{{ route('annonces.create') }}"
                                                        class="btn-gradient d-inline-block text-decoration-none px-3 py-2">Créer ma
                                                        première annonce</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips for Sellers -->
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center"><i
                                class="material-symbols-rounded me-2 text-warning">tips_and_updates</i> Conseils pour Réussir
                            vos Ventes</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="d-flex align-items-center p-3 rounded-3 border h-100" style="background: #f8f9fa;">
                                    <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                        <i class="material-symbols-rounded"
                                            style="font-size: 24px; color: #667eea;">photo_camera</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-dark font-weight-bold">Photos de qualité</h6>
                                        <p class="text-xs text-secondary mb-0">Les annonces avec photos ont 85% plus de chances
                                            de vendre</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="d-flex align-items-center p-3 rounded-3 border h-100" style="background: #f8f9fa;">
                                    <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 48px; height: 48px; background: rgba(118, 75, 162, 0.1);">
                                        <i class="material-symbols-rounded"
                                            style="font-size: 24px; color: #764ba2;">price_check</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-dark font-weight-bold">Prix compétitifs</h6>
                                        <p class="text-xs text-secondary mb-0">Fixez un prix de départ attractif</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center p-3 rounded-3 border h-100" style="background: #f8f9fa;">
                                    <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 48px; height: 48px; background: rgba(79, 209, 197, 0.1);">
                                        <i class="material-symbols-rounded"
                                            style="font-size: 24px; color: #4fd1c5;">description</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-dark font-weight-bold">Description détaillée</h6>
                                        <p class="text-xs text-secondary mb-0">Décrivez précisément l'état du produit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @role('client')
        <!-- Client Dashboard -->
        <div class="row">
            <!-- Welcome Section -->
            <div class="col-12 mb-4">
                <div class="card bg-gradient-theme shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2 fw-bold">Bonjour, {{ Auth::user()->nom }}! 🎯</h4>
                                <p class="text-white opacity-8 mb-0">Découvrez les meilleures enchères, suivez vos offres et
                                    remportez des lots exceptionnels.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('auctions.active') }}"
                                    class="btn-gradient d-inline-block text-decoration-none">
                                    <i class="material-symbols-rounded align-middle me-1">gavel</i> Explorer les Enchères
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Mes Offres</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['total_bids'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #48bb78;">
                                    {{ $stats['active_bids'] ?? 0 }} offres en tête</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(102, 126, 234, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #667eea;">gavel</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('my.bids') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Voir mes offres →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Enchères Gagnées</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ $stats['won_auctions'] ?? 0 }}</h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #4299e1;">Félicitations! 🏆</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(66, 153, 225, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #4299e1;">emoji_events</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('my.won') }}" class="text-sm font-weight-bold"
                            style="color: #667eea !important; text-decoration: none;">Voir mes gains →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Mon Solde</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">{{ number_format($stats['balance'] ?? 0, 2) }} MAD
                                </h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #ed8936;">Disponible</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(237, 137, 54, 0.1);">
                                <i class="material-symbols-rounded"
                                    style="font-size: 24px; color: #ed8936;">account_balance_wallet</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-xs font-weight-bold mb-1 text-uppercase text-secondary"
                                    style="letter-spacing: 0.5px;">Taux de Réussite</p>
                                <h2 class="font-weight-bolder mb-1 text-dark">
                                    {{ $stats['total_bids'] > 0 ? round(($stats['won_auctions'] / $stats['total_bids']) * 100) : 0 }}%
                                </h2>
                                <p class="text-xs font-weight-bold mb-0" style="color: #f56565;">Victoires / Total offres</p>
                            </div>
                            <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: rgba(245, 101, 101, 0.1);">
                                <i class="material-symbols-rounded" style="font-size: 24px; color: #f56565;">insights</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Auctions Grid -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div
                        class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="material-symbols-rounded me-2"
                                style="color: #667eea;">gavel</i> Enchères Actives pour Vous</h6>
                        <a href="{{ route('auctions.active') }}" class="text-sm font-weight-bold"
                            style="color: #667eea; text-decoration: none;">Voir toutes les enchères →</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $featuredAuctions = \App\Models\Annonce::with('produit')
                                    ->where('statut', 'ACTIVE')
                                    ->where('date_fin', '>', now())
                                    ->orderBy('created_at', 'desc')
                                    ->limit(6)
                                    ->get();
                            @endphp
                            @forelse($featuredAuctions as $auction)
                                @php
                                    $images = \App\Helpers\ImageHelper::getProductImages($auction->produit);
                                    $firstPhoto = $images[0] ?? 'https://via.placeholder.com/300x200?text=No+Image';
                                    $currentBid = $auction->getMontantActuel();
                                    $bidCount = $auction->encheres()->count();
                                    $timeLeft = \Carbon\Carbon::parse($auction->date_fin);
                                    $isEndingSoon = $timeLeft->diffInHours(now()) <= 24;
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 auction-card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="position-relative">
                                            <img src="{{ $firstPhoto }}" class="card-img-top w-100"
                                                style="height: 200px; object-fit: cover;" alt="{{ $auction->titre }}">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-gradient-info">{{ $bidCount }} enchère(s)</span>
                                            </div>
                                            @if($isEndingSoon)
                                                <div class="position-absolute bottom-0 start-0 m-2">
                                                    <span class="badge bg-gradient-warning">
                                                        <i class="material-symbols-rounded" style="font-size: 14px;">schedule</i>
                                                        Bientôt fini !
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title mb-1 fw-bold">{{ Str::limit($auction->titre, 50) }}</h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <small class="text-muted">Prix actuel</small>
                                                    <h5 class="text-primary mb-0">{{ number_format($currentBid, 2) }} MAD</h5>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted">Départ</small>
                                                    <p class="mb-0">{{ number_format($auction->prix_depart, 2) }} MAD</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0 pb-4 px-4">
                                            <a href="{{ route('annonces.show', $auction) }}"
                                                class="btn-gradient w-100 mb-0 py-2 d-flex justify-content-center align-items-center text-white"
                                                style="border-radius: 12px; text-decoration: none;">
                                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">gavel</i>
                                                Participer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="material-symbols-rounded" style="font-size: 64px;">gavel</i>
                                    <h5 class="mt-3">Aucune enchère active</h5>
                                    <p class="text-muted">Revenez plus tard pour découvrir de nouvelles enchères!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole
    @endauth
@endsection

@push('styles')
    <style>
        .auction-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .card-img-top {
            transition: transform 0.5s ease;
        }

        .auction-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
@endpush