{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('page-title', 'Gestion des Catégories')
@section('breadcrumb', 'Catégories')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Ajouter une Catégorie</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Nom de la catégorie</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Icône (nom Material Icon)</label>
                            <input type="text" name="icone" class="form-control" placeholder="category">
                        </div>
                        <button type="submit" class="btn bg-gradient-dark w-100">
                            <i class="material-symbols-rounded me-1">add</i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Liste des Catégories</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Icône
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Description</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sous-catégories</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $categorie)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <i class="material-symbols-rounded">{{ $categorie->icone ?? 'category' }}</i>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $categorie->nom }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">{{ Str::limit($categorie->description, 50) }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="badge badge-sm bg-gradient-info">{{ $categorie->sousCategories->count() }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary mb-0" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="material-symbols-rounded">more_vert</i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                                    <li>
                                                        <a class="dropdown-item border-radius-md" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#subcategoryModal{{ $categorie->id }}">
                                                            <i
                                                                class="material-symbols-rounded me-2">subdirectory_arrow_right</i>
                                                            Gérer sous-catégories
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item border-radius-md" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editCategoryModal{{ $categorie->id }}">
                                                            <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('admin.categories.destroy', $categorie) }}"
                                                            onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item border-radius-md text-danger">
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
                                                    <button type="button" class="btn-close text-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="input-group input-group-outline mb-3">
                                                            <label class="form-label">Nom</label>
                                                            <input type="text" name="nom" class="form-control"
                                                                value="{{ $categorie->nom }}" required>
                                                        </div>
                                                        <div class="input-group input-group-outline mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" class="form-control"
                                                                rows="3">{{ $categorie->description }}</textarea>
                                                        </div>
                                                        <div class="input-group input-group-outline mb-3">
                                                            <label class="form-label">Icône</label>
                                                            <input type="text" name="icone" class="form-control"
                                                                value="{{ $categorie->icone }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Annuler</button>
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
                                                    <button type="button" class="btn-close text-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('admin.categories.subcategories.store', $categorie) }}"
                                                        class="mb-4">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group input-group-outline">
                                                                    <label class="form-label">Nom de la sous-catégorie</label>
                                                                    <input type="text" name="nom" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="submit" class="btn bg-gradient-dark w-100">
                                                                    <i class="material-symbols-rounded">add</i> Ajouter
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="input-group input-group-outline mt-2">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" class="form-control"
                                                                rows="2"></textarea>
                                                        </div>
                                                    </form>

                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
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
                                                                        <td>{{ $sousCategorie->nom }}</td>
                                                                        <td>{{ Str::limit($sousCategorie->description, 50) }}</td>
                                                                        <td class="text-center">
                                                                            <form method="POST"
                                                                                action="{{ route('admin.categories.subcategories.destroy', [$categorie, $sousCategorie]) }}"
                                                                                class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-link text-danger p-0"
                                                                                    onclick="return confirm('Supprimer cette sous-catégorie ?')">
                                                                                    <i class="material-symbols-rounded">delete</i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center">Aucune sous-catégorie
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Aucune catégorie trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection