{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails du produit')
@section('page-title', 'Détails du produit')
@section('breadcrumb', 'Produits')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-gradient-theme text-white rounded-top-4">
                <h5 class="mb-0 fw-bold">
                    <i class="material-symbols-rounded align-middle me-2">inventory_2</i> {{ $product->nom }}
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Image Gallery -->
                    <div class="col-md-5">
                        @php
                            $photos = $product->photos ?? [];
                            $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/400x300?text=No+Image';
                        @endphp
                        <img src="{{ $firstPhoto }}" class="img-fluid rounded-3 shadow-sm w-100" alt="{{ $product->nom }}" style="object-fit: cover; height: 250px;">
                        @if(count($photos) > 1)
                            <div class="row mt-2 g-1">
                                @foreach(array_slice($photos, 1, 3) as $photo)
                                    <div class="col-3">
                                        <img src="{{ Storage::url($photo) }}" class="img-fluid rounded-2" style="height: 70px; width: 100%; object-fit: cover;">
                                    </div>
                                @endforeach
                                @if(count($photos) > 4)
                                    <div class="col-3 d-flex align-items-center justify-content-center bg-light rounded-2" style="height: 70px;">
                                        <small class="text-muted">+{{ count($photos) - 4 }}</small>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-7">
                        <h3 class="fw-bold text-dark">{{ $product->nom }}</h3>
                        <div class="mb-3">
                            @if($product->marque || $product->modele)
                                <p class="text-muted mb-1">
                                    <i class="material-symbols-rounded align-middle text-primary" style="font-size: 18px;">branding_watermark</i>
                                    <strong>Marque :</strong> {{ $product->marque ?? 'Non spécifiée' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="material-symbols-rounded align-middle text-primary" style="font-size: 18px;">model_training</i>
                                    <strong>Modèle :</strong> {{ $product->modele ?? 'Non spécifié' }}
                                </p>
                            @endif
                            <p class="text-muted mb-1">
                                <i class="material-symbols-rounded align-middle text-primary" style="font-size: 18px;">category</i>
                                <strong>Catégorie :</strong> {{ $product->sousCategorie?->categorie?->nom ?? $product->sousCategorie?->nom ?? 'Non catégorisé' }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="material-symbols-rounded align-middle text-primary" style="font-size: 18px;">inventory_2</i>
                                <strong>État :</strong>
                                @switch($product->etat)
                                    @case('NEUF') <span class="badge bg-gradient-success rounded-pill">Neuf</span> @break
                                    @case('TRES_BON_ETAT') <span class="badge bg-gradient-info rounded-pill">Très bon état</span> @break
                                    @case('BON_ETAT') <span class="badge bg-gradient-primary rounded-pill">Bon état</span> @break
                                    @default <span class="badge bg-gradient-warning rounded-pill">Acceptable</span>
                                @endswitch
                            </p>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold text-dark">Description</h6>
                            <p class="text-secondary">{{ $product->description ?: 'Aucune description fournie.' }}</p>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                                    <i class="material-symbols-rounded align-middle me-1">arrow_back</i> Retour
                                </a>
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-gradient rounded-3 px-4">
                                    <i class="material-symbols-rounded align-middle me-1">edit</i> Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>
@endpush
@endsection