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
                <div class="card bg-gradient-dark shadow-lg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2">Bonjour, {{ Auth::user()->nom }}! 👋</h4>
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
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary font-weight-bold">Utilisateurs</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                                <p class="text-xs text-success mb-0 mt-2">
                                    <i class="material-symbols-rounded text-success" style="font-size: 14px;">trending_up</i>
                                    +12% ce mois
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="material-symbols-rounded text-white opacity-10">people</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('admin.users.index') }}" class="text-primary text-sm">Gérer les utilisateurs →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary font-weight-bold">Total Enchères</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['total_auctions'] ?? 0 }}</h3>
                                <p class="text-xs text-info mb-0 mt-2">
                                    <i class="material-symbols-rounded text-info" style="font-size: 14px;">gavel</i>
                                    {{ $stats['active_auctions'] ?? 0 }} actives
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="material-symbols-rounded text-white opacity-10">gavel</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('admin.auctions.index') }}" class="text-success text-sm">Gérer les enchères →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary font-weight-bold">Total Offres</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ number_format($stats['total_bids'] ?? 0) }}</h3>
                                <p class="text-xs text-warning mb-0 mt-2">
                                    <i class="material-symbols-rounded text-warning" style="font-size: 14px;">paid</i>
                                    Volume total: {{ number_format(($stats['total_bids'] ?? 0) * 1000, 0) }} MAD
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="material-symbols-rounded text-white opacity-10">paid</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('admin.auctions.index') }}" class="text-warning text-sm">Voir les offres →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary font-weight-bold">Catégories</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ \App\Models\Categorie::count() }}</h3>
                                <p class="text-xs text-danger mb-0 mt-2">
                                    <i class="material-symbols-rounded text-danger" style="font-size: 14px;">category</i>
                                    {{ \App\Models\SousCategorie::count() }} sous-catégories
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="material-symbols-rounded text-white opacity-10">category</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('admin.categories.index') }}" class="text-danger text-sm">Gérer les catégories →</a>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6><i class="material-symbols-rounded me-1">people</i> Derniers Utilisateurs Inscrits</h6>
                        <a href="{{ route('admin.users.index') }}" class="text-primary text-sm">Voir tout →</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Utilisateur</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Rôle</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->nom }} {{ $user->prenom }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'vendeur' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
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
                <div class="card h-100">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6><i class="material-symbols-rounded me-1">gavel</i> Dernières Enchères</h6>
                        <a href="{{ route('admin.auctions.index') }}" class="text-primary text-sm">Voir tout →</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Annonce</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Prix Actuel</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Annonce::with('produit')->latest()->take(5)->get() as $annonce)
                                        @php
                                            $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ $productImage }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="product"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ Str::limit($annonce->titre, 25) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-primary text-sm font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }}
                                                    MAD</span>
                                            </td>
                                            <td>
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
            <!-- Welcome Section -->
            <div class="col-12 mb-4">
                <div class="card bg-gradient-dark shadow-lg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2">Bonjour, {{ Auth::user()->nom }}! 🛒</h4>
                                <p class="text-white opacity-8 mb-0">Gérez vos ventes, suivez vos performances et créez de
                                    nouvelles annonces.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('annonces.create') }}" class="btn bg-gradient-light mb-0">
                                    <i class="material-symbols-rounded">add_circle</i> Nouvelle Annonce
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Mes Annonces</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['total_listings'] ?? 0 }}</h3>
                                <p class="text-xs text-success mb-0 mt-2">{{ $stats['active_listings'] ?? 0 }} annonces actives
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-primary shadow-primary rounded-circle">
                                <i class="material-symbols-rounded text-white">inventory_2</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('annonces.index') }}" class="text-primary text-sm">Gérer mes annonces →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Ventes Totales</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['total_sales'] ?? 0 }}</h3>
                                <p class="text-xs text-info mb-0 mt-2">+{{ $stats['total_sales'] * 15 ?? 0 }}% ce mois</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-success shadow-success rounded-circle">
                                <i class="material-symbols-rounded text-white">store</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Note Moyenne</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ number_format($stats['rating'] ?? 0, 1) }}/5</h3>
                                <p class="text-xs text-warning mb-0 mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="material-symbols-rounded"
                                            style="font-size: 12px; color: {{ $i <= round($stats['rating'] ?? 0) ? '#ffc107' : '#dee2e6' }}">star</i>
                                    @endfor
                                </p>
                            </div>
                            <div class="icon icon-shape bg-gradient-warning shadow-warning rounded-circle">
                                <i class="material-symbols-rounded text-white">star</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Chiffre d'Affaires</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">
                                    {{ number_format(($stats['total_sales'] ?? 0) * 1000, 0) }} MAD</h3>
                                <p class="text-xs text-danger mb-0 mt-2">Mois en cours</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-danger shadow-danger rounded-circle">
                                <i class="material-symbols-rounded text-white">payments</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Products List -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6><i class="material-symbols-rounded me-1">inventory_2</i> Mes Annonces Récentes</h6>
                        <a href="{{ route('annonces.index') }}" class="text-primary text-sm">Voir toutes mes annonces →</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Produit</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Prix Actuel</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Enchères</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Statut</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Fin dans</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sellerAuctions = Auth::user()->client->vendeur->annonces()->with('produit', 'mises')->latest()->take(5)->get();
                                    @endphp
                                    @forelse($sellerAuctions as $annonce)
                                        @php
                                            $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <img src="{{ $productImage }}" class="avatar avatar-sm me-3 border-radius-lg"
                                                        alt="product" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ Str::limit($annonce->titre, 30) }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $annonce->produit->nom }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-primary text-sm font-weight-bold">{{ number_format($annonce->getMontantActuel(), 2) }}
                                                    MAD</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-sm bg-gradient-info">{{ $annonce->mises()->count() }}</span>
                                            </td>
                                            <td>
                                                @if($annonce->statut == 'ACTIVE')
                                                    <span class="badge badge-sm bg-gradient-success">Active</span>
                                                @elseif($annonce->statut == 'EN_ATTENTE')
                                                    <span class="badge badge-sm bg-gradient-warning">En attente</span>
                                                @elseif($annonce->statut == 'CLOTUREE')
                                                    <span class="badge badge-sm bg-gradient-secondary">Clôturée</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-danger">Bloquée</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($annonce->statut == 'ACTIVE' && $annonce->date_fin)
                                                    <span
                                                        class="text-warning text-sm">{{ \Carbon\Carbon::parse($annonce->date_fin)->diffForHumans() }}</span>
                                                @else
                                                    <span class="text-secondary text-sm">Terminée</span>
                                                @endif
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
                                                <i class="material-symbols-rounded" style="font-size: 48px;">inventory_2</i>
                                                <p class="mt-2">Vous n'avez pas encore d'annonces.</p>
                                                <a href="{{ route('annonces.create') }}"
                                                    class="btn btn-sm bg-gradient-primary mt-2">
                                                    Créer ma première annonce
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <td>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips for Sellers -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="material-symbols-rounded me-1">tips_and_updates</i> Conseils pour Réussir vos Ventes</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-info rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">photo_camera</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Photos de qualité</h6>
                                        <small class="text-muted">Les annonces avec photos ont 85% plus de chances de
                                            vendre</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-success rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">price_check</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Prix compétitifs</h6>
                                        <small class="text-muted">Fixez un prix de départ attractif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-warning rounded-circle me-3">
                                        <i class="material-symbols-rounded text-white">description</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Description détaillée</h6>
                                        <small class="text-muted">Décrivez précisément l'état du produit</small>
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
                <div class="card bg-gradient-dark shadow-lg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2">Bonjour, {{ Auth::user()->nom }}! 🎯</h4>
                                <p class="text-white opacity-8 mb-0">Découvrez les meilleures enchères, suivez vos offres et
                                    remportez des lots exceptionnels.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('auctions.active') }}" class="btn bg-gradient-light mb-0">
                                    <i class="material-symbols-rounded">gavel</i> Explorer les Enchères
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Mes Offres</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['total_bids'] ?? 0 }}</h3>
                                <p class="text-xs text-success mb-0 mt-2">{{ $stats['active_bids'] ?? 0 }} offres en tête</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-primary shadow-primary rounded-circle">
                                <i class="material-symbols-rounded text-white">gavel</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('my.bids') }}" class="text-primary text-sm">Voir mes offres →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Enchères Gagnées</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ $stats['won_auctions'] ?? 0 }}</h3>
                                <p class="text-xs text-info mb-0 mt-2">Félicitations! 🏆</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-success shadow-success rounded-circle">
                                <i class="material-symbols-rounded text-white">emoji_events</i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent p-2">
                        <a href="{{ route('my.won') }}" class="text-success text-sm">Voir mes gains →</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Mon Solde</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">{{ number_format($stats['balance'] ?? 0, 2) }} MAD</h3>
                                <p class="text-xs text-warning mb-0 mt-2">Disponible</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-warning shadow-warning rounded-circle">
                                <i class="material-symbols-rounded text-white">account_balance_wallet</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-0 text-uppercase text-secondary">Taux de Réussite</p>
                                <h3 class="font-weight-bolder mt-2 mb-0">
                                    {{ $stats['total_bids'] > 0 ? round(($stats['won_auctions'] / $stats['total_bids']) * 100) : 0 }}%
                                </h3>
                                <p class="text-xs text-danger mb-0 mt-2">Victoires / Total offres</p>
                            </div>
                            <div class="icon icon-shape bg-gradient-danger shadow-danger rounded-circle">
                                <i class="material-symbols-rounded text-white">insights</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Auctions Grid -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6><i class="material-symbols-rounded me-1">gavel</i> Enchères Actives pour Vous</h6>
                        <a href="{{ route('auctions.active') }}" class="text-primary text-sm">Voir toutes les enchères →</a>
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
                                    $bidCount = $auction->mises()->count();
                                    $timeLeft = \Carbon\Carbon::parse($auction->date_fin);
                                    $isEndingSoon = $timeLeft->diffInHours(now()) <= 24;
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 auction-card">
                                        <div class="position-relative">
                                            <img src="{{ $firstPhoto }}" class="card-img-top"
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
                                            <h6 class="card-title mb-1">{{ Str::limit($auction->titre, 50) }}</h6>
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
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('annonces.show', $auction) }}"
                                                class="btn bg-gradient-primary w-100 mb-0">
                                                <i class="material-symbols-rounded">gavel</i> Participer
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
    </style>
@endpush