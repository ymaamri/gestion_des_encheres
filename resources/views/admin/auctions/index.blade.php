{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/auctions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Enchères')
@section('page-title', 'Gestion des Enchères')
@section('breadcrumb', 'Enchères')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div
                    class="card-header bg-gradient-theme text-white rounded-top-4 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-gavel me-2"></i> Toutes les Enchères
                    </h6>
                    <select id="statusFilter"
                        class="form-select form-select-sm w-auto rounded-3 bg-white text-dark fw-semibold border-0">
                        <option value="">Tous les statuts</option>
                        <option value="EN_ATTENTE">En attente</option>
                        <option value="ACTIVE">Active</option>
                        <option value="CLOTUREE">Clôturée</option>
                        <option value="BLOQUEE">Bloquée</option>
                    </select>
                </div>

                <div class="card-body px-4 pb-0">
                    <div class="row g-3" id="auctionsGrid">
                        @forelse($auctions as $annonce)
                            <div class="col-sm-6 col-lg-4 col-xl-4 auction-card-wrapper" data-status="{{ $annonce->statut }}">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                                    <!-- Barre colorée statut -->
                                    <div class="position-absolute top-0 start-0 w-100"
                                        style="height: 4px; background: linear-gradient(135deg, #667eea, #764ba2);"></div>

                                    <div class="card-body p-3">
                                        <!-- Image + Titre -->
                                        <div class="d-flex align-items-start mb-3">
                                            @php
                                                $photos = $annonce->produit->photos ?? [];
                                                $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/80x60';
                                            @endphp
                                            <img src="{{ $firstPhoto }}" class="rounded-3 me-3" width="70" height="50"
                                                style="object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">{{ Str::limit($annonce->titre, 30) }}</h6>
                                                <small class="text-muted">{{ $annonce->produit->nom }}</small>
                                            </div>
                                        </div>

                                        <!-- Infos vendeur + prix -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <small class="text-secondary d-block">Vendeur</small>
                                                <span
                                                    class="fw-semibold text-dark small">{{ $annonce->vendeur->client->nom }}</span>
                                                <span class="badge bg-light text-dark ms-1 small">★
                                                    {{ number_format($annonce->vendeur->note_moyenne, 1) }}</span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-secondary d-block">Prix actuel</small>
                                                <span
                                                    class="text-primary fw-bold">{{ number_format($annonce->getMontantActuel(), 2) }}
                                                    MAD</span>
                                            </div>
                                        </div>

                                        <!-- Bas de carte : statut + enchères + date + actions -->
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="badge rounded-pill 
                                                    @if($annonce->statut == 'EN_ATTENTE') bg-warning text-dark
                                                    @elseif($annonce->statut == 'ACTIVE') bg-success
                                                    @elseif($annonce->statut == 'CLOTUREE') bg-secondary
                                                    @else bg-danger @endif">
                                                {{ $annonce->statut }}
                                            </span>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($annonce->date_fin)->format('d/m/Y') }}</small>
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info rounded-pill">{{ $annonce->encheres()->count() }}
                                                ench.</span>
                                        </div>

                                        <!-- Actions dropdown -->
                                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-secondary p-0" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2">
                                                    <li>
                                                        <a class="dropdown-item rounded-3"
                                                            href="{{ route('annonces.show', $annonce) }}" target="_blank">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    @if($annonce->statut == 'EN_ATTENTE')
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('admin.auctions.publish', $annonce) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item rounded-3 text-success">
                                                                    <i class="fas fa-check-circle me-2"></i> Publier
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($annonce->statut == 'ACTIVE')
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('admin.auctions.block', $annonce) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item rounded-3 text-danger"
                                                                    onclick="return confirm('Bloquer cette enchère ?')">
                                                                    <i class="fas fa-ban me-2"></i> Bloquer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($annonce->statut == 'BLOQUEE')
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('admin.auctions.publish', $annonce) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item rounded-3 text-success">
                                                                    <i class="fas fa-unlock-alt me-2"></i> Débloquer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fas fa-gavel fa-4x mb-3 opacity-25"></i>
                                <p>Aucune enchère trouvée.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination personnalisée -->
                    @if($auctions->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 pb-3 px-2 flex-wrap">
                            <small class="text-muted">
                                Affichage de {{ $auctions->firstItem() }} à {{ $auctions->lastItem() }} sur
                                {{ $auctions->total() }} résultats
                            </small>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous --}}
                                    @if ($auctions->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link rounded-3">&laquo;</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link rounded-3"
                                                href="{{ $auctions->previousPageUrl() }}">&laquo;</a></li>
                                    @endif

                                    {{-- Numbers --}}
                                    @foreach ($auctions->links()->elements[0] as $page => $url)
                                        @if ($page == $auctions->currentPage())
                                            <li class="page-item active"><span class="page-link rounded-3">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link rounded-3" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($auctions->hasMorePages())
                                        <li class="page-item"><a class="page-link rounded-3"
                                                href="{{ $auctions->nextPageUrl() }}">&raquo;</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link rounded-3">&raquo;</span></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('statusFilter').addEventListener('change', function () {
            let filter = this.value;
            document.querySelectorAll('.auction-card-wrapper').forEach(card => {
                card.style.display = (filter === '' || card.dataset.status === filter) ? '' : 'none';
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .btn-gradient,
        .bg-gradient-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .btn-gradient {
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .pagination .page-link {
            color: #667eea;
            border: none;
            min-width: 32px;
            text-align: center;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem !important;
        }

        .pagination .page-link:hover {
            background-color: #f0e7ff;
            color: #764ba2;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
        }

        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
@endpush