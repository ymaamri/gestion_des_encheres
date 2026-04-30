{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/subcategories.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Sous-catégories')
@section('page-title', 'Sous-catégories de : ' . $category->nom)
@section('breadcrumb', 'Sous-catégories')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4 border-0 shadow-sm rounded-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div
                        class="bg-gradient-theme shadow-lg border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center rounded-4">
                        <h6 class="text-white text-capitalize ps-3 mb-0 fw-bold">
                            <i class="material-symbols-rounded me-1 align-middle">subdirectory_arrow_right</i>
                            Sous-catégories - {{ $category->nom }}
                        </h6>
                        <div class="me-3">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm rounded-3">
                                <i class="material-symbols-rounded align-middle me-1">arrow_back</i> Retour aux catégories
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <!-- Formulaire d'ajout rapide -->
                    <div class="px-4 pt-4">
                        <div class="card bg-light border-0 rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary mb-3">Ajouter une sous-catégorie</h6>
                                <form method="POST" action="{{ route('admin.categories.subcategories.store', $category) }}">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <input type="text" name="nom" class="form-control rounded-3"
                                                placeholder="Nom de la sous-catégorie" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="description" class="form-control rounded-3"
                                                placeholder="Description (optionnel)">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-gradient w-100 rounded-3">
                                                <i class="material-symbols-rounded">add</i> Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des sous-catégories -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Nom
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Description</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre de produits</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Date de création</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $subcategory)
                                    <tr class="align-middle">
                                        <td class="px-4">
                                            <h6 class="mb-0 text-sm fw-bold text-dark">{{ $subcategory->nom }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ Str::limit($subcategory->description, 50) }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="badge bg-gradient-info px-3 py-1 rounded-pill">{{ $subcategory->produits->count() }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $subcategory->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary mb-0" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="material-symbols-rounded">more_vert</i>
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end px-2 py-3 shadow-sm border-0 rounded-3">
                                                    <li>
                                                        <a class="dropdown-item border-radius-md" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSubModal{{ $subcategory->id }}">
                                                            <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.categories.subcategories.destroy', [$category, $subcategory]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item border-radius-md text-danger"
                                                                onclick="return confirm('Supprimer cette sous-catégorie ?')">
                                                                <i class="material-symbols-rounded me-2">delete</i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Édition Sous-catégorie -->
                                    <div class="modal fade" id="editSubModal{{ $subcategory->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                                <div class="modal-header bg-gradient-theme text-white rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Modifier la sous-catégorie</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.categories.subcategories.update', $subcategory) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nom</label>
                                                            <input type="text" name="nom" class="form-control rounded-3"
                                                                value="{{ $subcategory->nom }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control rounded-3"
                                                                rows="3">{{ $subcategory->description }}</textarea>
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="material-symbols-rounded"
                                                style="font-size: 48px; color: #cbd5e0;">subdirectory_arrow_right</i>
                                            <p class="text-secondary mt-2">Aucune sous-catégorie trouvée.</p>
                                            <p class="text-muted">Utilisez le formulaire ci-dessus pour en ajouter.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 pt-3">
                        {{ $subcategories->links() }}
                    </div>
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

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
    </style>
@endpush