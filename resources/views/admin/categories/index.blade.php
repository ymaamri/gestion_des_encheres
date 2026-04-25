{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('page-title', 'Gestion des Catégories')
@section('breadcrumb', 'Catégories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Liste des Catégories</h6>
                    <button type="button" class="btn bg-gradient-success me-3 mb-0" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="material-symbols-rounded me-1">add</i> Ajouter une catégorie
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Icône</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sous-catégories</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $categorie)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <i class="material-symbols-rounded">{{ $categorie->icone ?? 'category' }}</i>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $categorie->nom }}</h6>
                                </td>
                                <td>
                                    <p class="text-xs text-secondary mb-0">{{ Str::limit($categorie->description ?? '', 60) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm bg-gradient-info">{{ $categorie->sousCategories->count() }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="#" data-bs-toggle="modal" data-bs-target="#subcategoryModal{{ $categorie->id }}">
                                                    <i class="material-symbols-rounded me-2">subdirectory_arrow_right</i> Gérer sous-catégories
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $categorie->id }}">
                                                    <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">
                                                        <i class="material-symbols-rounded me-2">delete</i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="editCategoryModal{{ $categorie->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-dark text-white">
                                            <h5 class="modal-title">Modifier la catégorie</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark fw-bold">Nom</label>
                                                    <input type="text" name="nom" class="form-control" value="{{ old('nom', $categorie->nom) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-dark fw-bold">Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $categorie->description) }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-dark fw-bold">Icône</label>
                                                    <input type="text" name="icone" class="form-control" value="{{ old('icone', $categorie->icone) }}" placeholder="category">
                                                    <small class="text-muted">Nom de l'icône Material Symbols (ex: category, devices, home)</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn bg-gradient-dark">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Subcategories Modal -->
                            <div class="modal fade" id="subcategoryModal{{ $categorie->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-dark text-white">
                                            <h5 class="modal-title">Sous-catégories - {{ $categorie->nom }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('admin.categories.subcategories.store', $categorie) }}" class="mb-4">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" name="nom" class="form-control" placeholder="Nom de la sous-catégorie" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn bg-gradient-dark w-100">
                                                            <i class="material-symbols-rounded me-1">add</i> Ajouter
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <textarea name="description" class="form-control" rows="2" placeholder="Description (optionnel)"></textarea>
                                                </div>
                                            </form>

                                            {{-- <div class="table-responsive">
                                                <table class="table align-items-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($categorie->sousCategories as $sousCategorie)
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">{{ $sousCategorie->nom }}</h6>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($sousCategorie->description ?? '', 50) }}</p>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                <form method="POST" action="{{ route('admin.categories.subcategories.destroy', [$categorie, $sousCategorie]) }}" class="d-inline" onsubmit="return confirm('Supprimer cette sous-catégorie ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link text-danger p-0 m-0">
                                                                        <i class="material-symbols-rounded">delete</i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        {{-- <tr>
                                                            <td colspan="3" class="text-center py-4">
                                                                <p class="text-secondary mb-0">Aucune sous-catégorie trouvée.</p>
                                                            </td>
                                                        </tr> --}}
                                                        {{-- @endforelse
                                                    </tbody>
                                                </table>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="material-symbols-rounded" style="font-size: 48px;">category</i>
                                    <p class="text-secondary mt-2">Aucune catégorie trouvée.</p>
                                    <button type="button" class="btn bg-gradient-primary mt-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        Créer la première catégorie
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark text-white">
                <h5 class="modal-title">Ajouter une catégorie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Icône</label>
                        <input type="text" name="icone" class="form-control" placeholder="category">
                        <small class="text-muted">Nom de l'icône Material Symbols (ex: category, devices, home, sports_basketball)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn bg-gradient-dark">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .input-group-outline {
        margin-top: 0 !important;
    }
    .dropdown-item {
        cursor: pointer;
    }
    .btn-link {
        text-decoration: none;
    }
    .modal-header.bg-gradient-dark {
        background: linear-gradient(195deg, #42424a, #191919) !important;
    }
    .form-control {
        border: 1px solid #d2d6da;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }
    .form-control:focus {
        border-color: #e91e63;
        outline: none;
        box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.25);
    }
</style>
@endpush