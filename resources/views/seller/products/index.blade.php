{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Produits')
@section('page-title', 'Gestion des Produits')
@section('breadcrumb', 'Produits')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-gradient-theme text-white rounded-top-4 d-flex justify-content-between align-items-center p-3">
                <h6 class="mb-0 fw-bold">
                    <i class="material-symbols-rounded me-1 align-middle">inventory_2</i> Mes Produits
                </h6>
                <a href="{{ route('seller.products.create') }}" class="btn btn-light btn-sm rounded-3">
                    <i class="material-symbols-rounded align-middle me-1" style="font-size: 18px;">add</i> Nouveau Produit
                </a>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Photo</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom / Marque</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catégorie</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">État</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enchères liées</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="align-middle">
                                <td class="px-4">
                                    <img src="{{ \App\Helpers\ImageHelper::getProductImage($product) }}" width="50" height="50" style="object-fit: cover; border-radius: 10px;" alt="{{ $product->nom }}">
                                 </td>
                                <td>
                                    <strong class="text-dark">{{ $product->nom }}</strong><br>
                                    <small class="text-muted">{{ $product->marque }} {{ $product->modele }}</small>
                                 </td>
                                <td>
                                    {{ $product->sousCategorie?->categorie?->nom ?? $product->sousCategorie?->nom ?? 'Non catégorisé' }}
                                 </td>
                                 <td class="text-center">
                                    @switch($product->etat)
                                        @case('NEUF') <span class="badge bg-gradient-success px-3 py-1 rounded-pill">Neuf</span> @break
                                        @case('TRES_BON_ETAT') <span class="badge bg-gradient-info px-3 py-1 rounded-pill">Très bon</span> @break
                                        @case('BON_ETAT') <span class="badge bg-gradient-primary px-3 py-1 rounded-pill">Bon</span> @break
                                        @default <span class="badge bg-gradient-warning px-3 py-1 rounded-pill">Acceptable</span>
                                    @endswitch
                                 </td>
                                 <td class="text-center">
                                    <span class="badge bg-gradient-secondary px-3 py-1 rounded-pill">{{ $product->annonces->count() }}</span>
                                 </td>
                                 <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="material-symbols-rounded">more_vert</i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 shadow-sm border-0 rounded-3">
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('seller.products.show', $product) }}">
                                                    <i class="material-symbols-rounded me-2">visibility</i> Voir
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item border-radius-md" href="{{ route('seller.products.edit', $product) }}">
                                                    <i class="material-symbols-rounded me-2">edit</i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?')">
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
                            @empty
                             <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="material-symbols-rounded" style="font-size: 48px; color: #cbd5e0;">inventory_2</i>
                                    <p class="text-secondary mt-2">Aucun produit trouvé.</p>
                                    <a href="{{ route('seller.products.create') }}" class="btn btn-gradient btn-sm rounded-3 mt-2">Ajoutez-en un</a>
                                </td>
                             </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pt-3">
                    {{ $products->links() }}
                </div>
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
    .table thead th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
</style>
@endpush