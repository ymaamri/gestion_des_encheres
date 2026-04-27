{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('page-title', 'Gestion des Catégories')
@section('breadcrumb', 'Catégories')

@section('content')
    <div class="row">
        <!-- Formulaire d'ajout de catégorie (colonne gauche) -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-gradient-theme text-white rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-1"></i> Ajouter une Catégorie
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom de la catégorie *</label>
                            <input type="text" name="nom" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Icône (classe Font Awesome)</label>
                            <input type="text" name="icone" class="form-control rounded-3"
                                placeholder="ex: fa-laptop, fa-tshirt, fa-home" value="fa-tag">
                            <small class="text-muted">Entrez une classe Font Awesome (ex: fa-laptop, fa-tshirt,
                                fa-gem)</small>
                        </div>
                        <button type="submit" class="btn btn-gradient w-100 mt-2 rounded-3">
                            <i class="fas fa-save me-1"></i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des catégories (colonne droite) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-gradient-theme text-white rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-list me-1"></i> Liste des Catégories
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder py-3 px-4">Icône
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Nom</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Description</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                        Sous-catégories</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $categorie)
                                    <tr class="align-middle border-bottom">
                                        <td class="px-4">
                                            <i class="fas {{ $categorie->icone ?? 'fa-tag' }} fa-2x text-primary"></i>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $categorie->nom }} </td>
                                        <td class="text-muted small">{{ Str::limit($categorie->description ?? '', 60) }} </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-info rounded-pill px-3 py-1">{{ $categorie->sousCategories->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary p-0" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#subcategoryModal{{ $categorie->id }}">
                                                            <i class="fas fa-folder-open me-2"></i> Gérer sous-catégories
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#editCategoryModal{{ $categorie->id }}">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('admin.categories.destroy', $categorie) }}"
                                                            onsubmit="return confirm('Supprimer cette catégorie ? Toutes ses sous-catégories seront également supprimées.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt me-2"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Modifier Catégorie -->
                                    <div class="modal fade" id="editCategoryModal{{ $categorie->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                                <div class="modal-header bg-gradient-theme text-white rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Modifier la catégorie</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nom *</label>
                                                            <input type="text" name="nom" class="form-control rounded-3"
                                                                value="{{ $categorie->nom }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control rounded-3"
                                                                rows="3">{{ $categorie->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Icône (classe Font
                                                                Awesome)</label>
                                                            <input type="text" name="icone" class="form-control rounded-3"
                                                                value="{{ $categorie->icone }}" placeholder="fa-tag">
                                                            <small class="text-muted">Ex: fa-laptop, fa-tshirt, fa-home</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary rounded-3"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit"
                                                            class="btn btn-gradient rounded-3">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Gérer Sous-catégories -->
                                    <div class="modal fade" id="subcategoryModal{{ $categorie->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                                <div class="modal-header bg-gradient-theme text-white rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Sous-catégories - {{ $categorie->nom }}</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <!-- Formulaire ajout sous-catégorie -->
                                                    <form method="POST"
                                                        action="{{ route('admin.categories.subcategories.store', $categorie) }}"
                                                        class="mb-4 p-3 bg-light rounded-4">
                                                        @csrf
                                                        <h6 class="fw-bold text-primary mb-3">Ajouter une sous-catégorie</h6>
                                                        <div class="row g-2">
                                                            <div class="col-md-5">
                                                                <input type="text" name="nom" class="form-control rounded-3"
                                                                    placeholder="Nom de la sous-catégorie" required>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" name="description"
                                                                    class="form-control rounded-3"
                                                                    placeholder="Description (optionnel)">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="submit"
                                                                    class="btn btn-gradient w-100 text-white rounded-3">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Liste des sous-catégories -->
                                                    <div class="table-responsive">
                                                        <table class="table table-sm align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Description</th>
                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($categorie->sousCategories as $sousCategorie)
                                                                    <tr>
                                                                        <td>{{ $sousCategorie->nom }} </td>
                                                                        <td>{{ Str::limit($sousCategorie->description ?? '', 50) }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-link text-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editSubModal{{ $sousCategorie->id }}">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <form method="POST"
                                                                                action="{{ route('admin.categories.subcategories.destroy', [$categorie, $sousCategorie]) }}"
                                                                                class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-link text-danger"
                                                                                    onclick="return confirm('Supprimer cette sous-catégorie ?')">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>

                                                                    <!-- Modal Édition Sous-catégorie -->
                                                                    <div class="modal fade"
                                                                        id="editSubModal{{ $sousCategorie->id }}" tabindex="-1">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                                                                <div
                                                                                    class="modal-header bg-gradient-theme text-white rounded-top-4">
                                                                                    <h5 class="modal-title fw-bold">Modifier la
                                                                                        sous-catégorie</h5>
                                                                                    <button type="button"
                                                                                        class="btn-close btn-close-white"
                                                                                        data-bs-dismiss="modal"></button>
                                                                                </div>
                                                                                <form method="POST"
                                                                                    action="{{ route('admin.categories.subcategories.update', $sousCategorie) }}">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="modal-body p-4">
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Nom
                                                                                                *</label>
                                                                                            <input type="text" name="nom"
                                                                                                class="form-control rounded-3"
                                                                                                value="{{ $sousCategorie->nom }}"
                                                                                                required>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Description</label>
                                                                                            <textarea name="description"
                                                                                                class="form-control rounded-3"
                                                                                                rows="3">{{ $sousCategorie->description }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer border-0">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary rounded-3"
                                                                                            data-bs-dismiss="modal">Annuler</button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-gradient rounded-3">Enregistrer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center py-4 text-muted">Aucune
                                                                            sous-catégorie.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucune catégorie trouvée.</p>
                                            <p class="small">Ajoutez une catégorie via le formulaire à gauche.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination supprimée car $categories n’est pas paginé -->
                </div>
            </div>
        </div>
    </div>
@endsection

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

        .table th {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
    </style>
@endpush