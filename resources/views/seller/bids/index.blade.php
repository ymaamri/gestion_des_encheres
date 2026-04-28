{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/bids/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Offres reçues')
@section('page-title', 'Offres reçues sur mes enchères')
@section('breadcrumb', 'Offres reçues')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Carte principale -->
        <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
            <!-- En-tête avec gradient amélioré -->
            <div class="card-header bg-gradient-theme text-white rounded-top-4 d-flex justify-content-between align-items-center p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape icon-shape-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                        <i class="material-symbols-rounded text-white">gavel</i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-white">Offres reçues</h5>
                        <p class="mb-0 text-white-50 small">Gérez les enchères sur vos annonces</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-semibold shadow-sm">
                        <i class="material-symbols-rounded align-middle me-1" style="font-size: 18px">tactic</i>
                        {{ $bids->total() }} {{ trans_choice('offre|offres', $bids->total()) }}
                    </span>
                </div>
            </div>

            <!-- Corps de la carte -->
            <div class="card-body p-0 pb-2">
                @if($bids->count() > 0)
                    <!-- Table stylisée -->
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 table-modern">
                            <thead>
                                <tr>
                                    <th class="ps-4 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">Annonce</th>
                                    <th class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">Enchérisseur</th>
                                    <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">Montant</th>
                                    <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">Date</th>
                                    <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">Statut</th>
                                    <th class="text-center text-uppercase text-muted fw-bold pe-4" style="font-size: 0.75rem; letter-spacing: 0.8px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bids as $bid)
                                    @php
                                        $annonce = $bid->annonce;
                                        $productImage = \App\Helpers\ImageHelper::getProductImage($annonce->produit);
                                        $client = $bid->client;
                                        $rowAnimation = $loop->iteration % 2 == 0 ? 'animation-delay-1' : '';
                                    @endphp
                                    <tr class="table-row-glass animate__animated animate__fadeInUp {{ $rowAnimation }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 position-relative">
                                                    <img src="{{ $productImage }}" 
                                                         class="rounded-3 shadow-sm" 
                                                         alt="product" 
                                                         style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #fff;">
                                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-white border border-2 border-white rounded-circle shadow-sm">
                                                        <i class="material-symbols-rounded fs-8 text-warning">star</i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($annonce->titre, 35) }}</h6>
                                                    <p class="mb-0 text-muted small">{{ $annonce->produit->nom }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($client)
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark">{{ $client->nom }} {{ $client->prenom }}</span>
                                                    <small class="text-muted">{{ $client->user->email ?? 'Email non disponible' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Utilisateur supprimé</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border fw-bold px-3 py-2 rounded-pill {{ $bid->isWinning ? 'border-success' : 'border-light' }}">
                                                {{ number_format($bid->montant, 2) }} MAD
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">{{ $bid->created_at->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($annonce->statut == 'ACTIVE')
                                                @if($bid->isWinning)
                                                    <span class="badge bg-gradient-success bg-opacity-20 text-success px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                                        <i class="material-symbols-rounded me-1">emoji_events</i> En tête
                                                    </span>
                                                @else
                                                    <span class="badge bg-gradient-warning bg-opacity-20 text-warning px-3 py-2 rounded-pill">
                                                        Dépassée
                                                    </span>
                                                @endif
                                            @elseif($annonce->statut == 'CLOTUREE')
                                                @if($bid->isWinning)
                                                    <span class="badge bg-gradient-success bg-opacity-20 text-success px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                                        <i class="material-symbols-rounded me-1">verified</i> Gagnante
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-20 text-secondary px-3 py-2 rounded-pill">
                                                        Perdante
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $annonce->statut }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('annonces.show', $annonce) }}" 
                                                   class="btn btn-icon btn-outline-secondary btn-sm border-0 rounded-3" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Voir l'enchère"
                                                   style="width: 34px; height: 34px;">
                                                    <i class="material-symbols-rounded fs-6">visibility</i>
                                                </a>
                                                @if($bid->isWinning && $annonce->statut == 'CLOTUREE' && $client)
                                                    <a href="mailto:{{ $client->user->email }}?subject=Félicitations ! Vous avez gagné l'enchère {{ $annonce->titre }}" 
                                                       class="btn btn-icon btn-outline-success btn-sm border-0 rounded-3" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Contacter le gagnant"
                                                       style="width: 34px; height: 34px;">
                                                        <i class="material-symbols-rounded fs-6">mail</i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination améliorée -->
                    @if($bids->lastPage() > 1)
                        <div class="d-flex flex-column align-items-center mt-4 pb-3">
                            <nav aria-label="Navigation des pages">
                                <ul class="pagination pagination-premium mb-2">
                                    {{-- Page précédente --}}
                                    @if($bids->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link border-0 rounded-3 mx-1 shadow-sm">
                                                <i class="material-symbols-rounded">chevron_left</i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link border-0 rounded-3 mx-1 shadow-sm text-dark" href="{{ $bids->previousPageUrl() }}" rel="prev">
                                                <i class="material-symbols-rounded">chevron_left</i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Éléments de pagination --}}
                                    @php
                                        $start = max(1, $bids->currentPage() - 2);
                                        $end = min($bids->lastPage(), $bids->currentPage() + 2);
                                        
                                        if ($start > 1) {
                                            echo '<li class="page-item"><a class="page-link border-0 rounded-3 mx-1 shadow-sm text-dark" href="' . $bids->url(1) . '">1</a></li>';
                                            if ($start > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link border-0 rounded-3 mx-1 text-muted bg-transparent">...</span></li>';
                                            }
                                        }
                                        
                                        for ($i = $start; $i <= $end; $i++) {
                                            $activeClass = ($bids->currentPage() == $i) ? 'active-premium' : '';
                                            echo '<li class="page-item '.$activeClass.'"><a class="page-link border-0 rounded-3 mx-1 shadow-sm text-dark" href="' . $bids->url($i) . '">' . $i . '</a></li>';
                                        }
                                        
                                        if ($end < $bids->lastPage()) {
                                            if ($end < $bids->lastPage() - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link border-0 rounded-3 mx-1 text-muted bg-transparent">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link border-0 rounded-3 mx-1 shadow-sm text-dark" href="' . $bids->url($bids->lastPage()) . '">' . $bids->lastPage() . '</a></li>';
                                        }
                                    @endphp

                                    {{-- Page suivante --}}
                                    @if($bids->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link border-0 rounded-3 mx-1 shadow-sm text-dark" href="{{ $bids->nextPageUrl() }}" rel="next">
                                                <i class="material-symbols-rounded">chevron_right</i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link border-0 rounded-3 mx-1 shadow-sm">
                                                <i class="material-symbols-rounded">chevron_right</i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <small class="text-muted">
                                Affichage de <span class="fw-semibold">{{ $bids->firstItem() }}</span> à <span class="fw-semibold">{{ $bids->lastItem() }}</span> sur <span class="fw-semibold">{{ $bids->total() }}</span> résultats
                            </small>
                        </div>
                    @endif
                @else
                    <!-- État vide modernisé -->
                    <div class="text-center py-6">
                        <div class="empty-state-icon mb-4">
                            <i class="material-symbols-rounded" style="font-size: 80px; color: #cbd5e0;">hourglass_empty</i>
                        </div>
                        <h4 class="text-secondary fw-bold">Aucune offre reçue</h4>
                        <p class="text-muted mx-auto" style="max-width: 400px;">Vous n'avez pas encore reçu d'enchères sur vos annonces. Publiez une annonce pour commencer à recevoir des offres.</p>
                        <a href="{{ route('annonces.create') }}" class="btn btn-primary-premium mt-3 px-4 py-2 rounded-pill shadow-lg">
                            <i class="material-symbols-rounded align-middle me-1">add_circle</i> Créer une annonce
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* === DESIGN SYSTEM VARIABLES === */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary-color: #667eea;
        --primary-dark: #5a67d8;
        --bg-light: #f8fafc;
        --card-bg: #ffffff;
        --border-color: #edf2f7;
        --text-dark: #1a202c;
        --text-muted: #718096;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 32px rgba(0,0,0,0.1);
        --radius-xl: 1.25rem;
        --radius-lg: 1rem;
        --radius-md: 0.75rem;
        --radius-sm: 0.5rem;
    }

    /* === GLOBAL PAGE STYLING === */
    body {
        background-color: var(--bg-light);
    }

    /* Carte principale premium */
    .shadow-premium {
        box-shadow: var(--shadow-md);
        transition: box-shadow 0.3s ease;
    }
    .shadow-premium:hover {
        box-shadow: var(--shadow-lg);
    }

    /* Header gradient plus subtil et moderne */
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    /* Icône de la carte */
    .icon-shape-primary {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
    }

    /* === TABLE MODERN STYLING === */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 6px;
        width: 100%;
    }
    .table-modern thead th {
        background-color: transparent;
        padding: 1rem 0.5rem;
        border-bottom: 2px solid var(--border-color);
        vertical-align: middle;
    }
    .table-modern tbody tr {
        background-color: var(--card-bg);
        transition: all 0.2s ease;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 8px;
    }
    .table-modern tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background-color: #ffffff;
    }
    .table-modern td {
        padding: 1rem 0.5rem;
        vertical-align: middle;
        border-top: none;
    }
    /* Arrondir les bords des lignes */
    .table-modern tbody tr td:first-child {
        border-top-left-radius: var(--radius-md);
        border-bottom-left-radius: var(--radius-md);
    }
    .table-modern tbody tr td:last-child {
        border-top-right-radius: var(--radius-md);
        border-bottom-right-radius: var(--radius-md);
    }

    /* Animation d'apparition */
    .animate__animated.animate__fadeInUp {
        --animate-duration: 0.5s;
    }
    .animation-delay-1 {
        animation-delay: 0.1s;
    }

    /* === BADGES STYLES === */
    .badge {
        font-weight: 500;
        letter-spacing: 0.2px;
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        border: none;
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%) !important;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #8e9eab 0%, #eef2f3 100%) !important;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* === BOUTONS D'ACTION === */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-icon:hover {
        background-color: rgba(102, 126, 234, 0.08);
    }
    .btn-icon.btn-outline-success:hover {
        background-color: rgba(25, 135, 84, 0.08);
    }

    /* === PAGINATION PREMIUM === */
    .pagination-premium .page-link {
        min-width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--text-dark);
        background: white;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
    }
    .pagination-premium .page-link:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .pagination-premium .page-item.active-premium .page-link {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);
    }
    .pagination-premium .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #94a3b8;
        pointer-events: none;
    }

    /* === BOUTON CTA PRINCIPAL === */
    .btn-primary-premium {
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }

    /* État vide amélioré */
    .empty-state-icon {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush