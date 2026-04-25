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
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm bg-gradient-success text-white me-3 mb-0">
                        <i class="material-symbols-rounded">add</i> Nouvelle Catégorie
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Icône</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sous-catégories</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de création</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $categorie)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $categorie->nom }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($categorie->description, 50) }}</p>
                                </td>
                                <td>
                                    @if($categorie->icone)
                                        <span class="material-symbols-rounded">{{ $categorie->icone }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.categories.subcategories', $categorie) }}" class="btn btn-link text-info mb-0">
                                        {{ $categorie->sousCategories->count() }} sous-catégories
                                    </a>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $categorie->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('admin.categories.edit', $categorie) }}">
                                                    <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('admin.categories.subcategories', $categorie) }}">
                                                    <i class="material-symbols-rounded me-2">subdirectory_arrow_right</i> Gérer sous-catégories
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">Aucune catégorie trouvée</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary mt-2">
                                        Créer la première catégorie
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $categories->links() }}
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
            if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush