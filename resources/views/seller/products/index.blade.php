@extends('layouts.app')

@section('title', 'Mes Produits')
@section('page-title', 'Gestion des Produits')
@section('breadcrumb', 'Produits')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between">
                    <h6 class="text-white text-capitalize ps-3 mb-0">📦 Mes Produits</h6>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-sm bg-gradient-success text-white me-3">
                        + Nouveau Produit
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom / Marque</th>
                                <th>Catégorie</th>
                                <th>État</th>
                                <th>Enchères liées</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ \App\Helpers\ImageHelper::getProductImage($product) }}" width="50" height="50" style="object-fit: cover; border-radius: 8px;">
                                </td>
                                <td>
                                    <strong>{{ $product->nom }}</strong><br>
                                    <small class="text-muted">{{ $product->marque }} {{ $product->modele }}</small>
                                </td>
                                <td>{{ $product->sousCategorie?->categorie?->nom ?? $product->sousCategorie?->nom ?? 'Non catégorisé' }}</td>
                                <td>
                                    @switch($product->etat)
                                        @case('NEUF') <span class="badge bg-success">Neuf</span> @break
                                        @case('TRES_BON_ETAT') <span class="badge bg-info">Très bon</span> @break
                                        @case('BON_ETAT') <span class="badge bg-primary">Bon</span> @break
                                        @default <span class="badge bg-warning">Acceptable</span>
                                    @endswitch
                                </td>
                                <td>{{ $product->annonces->count() }}</td>
                                <td>
                                    <a href="{{ route('seller.products.show', $product) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                    <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Modifier</a>
                                    <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5">Aucun produit. <a href="{{ route('seller.products.create') }}">Ajoutez-en un</a></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection