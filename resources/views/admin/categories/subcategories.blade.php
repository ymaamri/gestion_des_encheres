@extends('layouts.app')

@section('title', 'Gestion des Sous-catégories')
@section('page-title', 'Sous-catégories de : ' . $category->nom)
@section('breadcrumb', 'Sous-catégories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Sous-catégories - {{ $category->nom }}</h6>
                    <div>
                        <a href="{{ route('admin.categories.subcategories.create', $category) }}" class="btn btn-sm bg-gradient-success text-white me-2 mb-0">
                            <i class="material-symbols-rounded">add</i> Nouvelle Sous-catégorie
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm bg-gradient-secondary text-white me-3 mb-0">
                            <i class="material-symbols-rounded">arrow_back</i> Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre de produits</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de création</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subcategories as $subcategory)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $subcategory->nom }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($subcategory->description, 50) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $subcategory->produits->count() }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $subcategory->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('admin.categories.subcategories.edit', $subcategory) }}">
                                                    <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.categories.subcategories.destroy', $subcategory) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger delete-btn">
                                                        <i class="material-symbols-rounded me-2">delete</i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">Aucune sous-catégorie trouvée</p>
                                    <a href="{{ route('admin.categories.subcategories.create', $category) }}" class="btn btn-sm btn-primary mt-2">
                                        Créer la première sous-catégorie
                                    </a>
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

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Êtes-vous sûr de vouloir supprimer cette sous-catégorie ?')) {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush