{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Créer une Annonce')
@section('page-title', 'Créer une Nouvelle Annonce')
@section('breadcrumb', 'Créer une Annonce')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Créer une Nouvelle Annonce d'Enchère</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('annonces.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Source Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="fw-bold mb-2">Source du produit :</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="product_source"
                                            id="source_existing" value="existing" checked>
                                        <label class="form-check-label" for="source_existing">
                                            Utiliser un produit existant
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="product_source" id="source_new"
                                            value="new">
                                        <label class="form-check-label" for="source_new">
                                            Créer un nouveau produit
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Product Selection -->
                        <div id="existing_product_block">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sélectionner un produit existant *</label>
                                <select name="existing_product_id" id="existing_product_id" class="form-control">
                                    <option value="">-- Choisissez un produit --</option>
                                    @foreach($sellerProducts as $product)
                                        <option value="{{ $product->id }}"
                                            data-marque="{{ $product->marque }}"
                                            data-modele="{{ $product->modele }}"
                                            data-description="{{ $product->description }}"
                                            data-etat="{{ $product->etat }}"
                                            data-photos="{{ json_encode($product->photos) }}"
                                            data-sous_categorie_id="{{ $product->sous_categorie_id }}">
                                            {{ $product->nom }} ({{ $product->marque ?: 'Sans marque' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('existing_product_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div id="product_prefill_info" class="alert alert-info mt-2" style="display: none;">
                                <i class="material-symbols-rounded">info</i> Les détails du produit seront automatiquement utilisés.
                            </div>
                        </div>

                        <!-- New Product Fields -->
                        <div id="new_product_block" style="display: none;">
                            <h5 class="mb-3 text-primary">Nouveau produit</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nom du Produit *</label>
                                    <input type="text" name="produit_nom" class="form-control" value="{{ old('produit_nom') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Marque</label>
                                    <input type="text" name="produit_marque" class="form-control" value="{{ old('produit_marque') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Modèle</label>
                                    <input type="text" name="produit_modele" class="form-control" value="{{ old('produit_modele') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">État *</label>
                                    <select name="produit_etat" class="form-control">
                                        <option value="">Sélectionner l'État</option>
                                        <option value="NEUF" {{ old('produit_etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                        <option value="TRES_BON_ETAT" {{ old('produit_etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                        <option value="BON_ETAT" {{ old('produit_etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                        <option value="ACCEPTABLE" {{ old('produit_etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description du Produit</label>
                                <textarea name="produit_description" class="form-control" rows="3">{{ old('produit_description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Photos du Produit</label>
                                <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Vous pouvez sélectionner plusieurs images (max 5, 2MB chacune)</small>
                            </div>
                        </div>

                        <!-- Common Auction Fields -->
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Informations de l'enchère</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Titre de l'Annonce *</label>
                                <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Catégorie *</label>
                                <select name="categorie_id" id="categorie_id" class="form-control" required>
                                    <option value="">Sélectionner une Catégorie</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- NEW: Sous-catégorie dropdown (visible only when creating a new product) -->
                        <div id="new_product_sous_categorie_block" class="mb-3" style="display: none;">
                            <label class="form-label fw-bold">Sous-catégorie</label>
                            <select name="produit_sous_categorie_id" id="produit_sous_categorie_id" class="form-control">
                                <option value="">-- Aucune sous-catégorie --</option>
                            </select>
                            <small class="text-muted">Sélectionnez d'abord une catégorie ci-dessus</small>
                        </div>

                        <!-- For existing products, we don't allow changing subcategory here -->
                        <div id="existing_product_subcategory_info" class="alert alert-secondary mt-2" style="display: none;">
                            <i class="material-symbols-rounded">info</i> La sous-catégorie du produit existant sera conservée.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description de l'Annonce</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Prix de Départ (MAD) *</label>
                                <input type="number" name="prix_depart" class="form-control" step="0.01" value="{{ old('prix_depart') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date et Heure de Fin *</label>
                                <input type="datetime-local" name="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Pas d'enchère (MAD)</label>
                                <input type="number" name="montant_mise" class="form-control" step="1" value="{{ old('montant_mise', 1) }}" min="1">
                                <small class="text-muted">Montant minimum d'augmentation</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn bg-gradient-dark">
                                    <i class="material-symbols-rounded me-1">add_circle</i> Créer l'Annonce
                                </button>
                                <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radioExisting = document.getElementById('source_existing');
            const radioNew = document.getElementById('source_new');
            const existingBlock = document.getElementById('existing_product_block');
            const newBlock = document.getElementById('new_product_block');
            const existingProductSelect = document.getElementById('existing_product_id');
            const categorySelect = document.getElementById('categorie_id');
            const subcategorySelect = document.getElementById('produit_sous_categorie_id');
            const newProductSubcatBlock = document.getElementById('new_product_sous_categorie_block');
            const existingSubcatInfo = document.getElementById('existing_product_subcategory_info');

            // Toggle visibility based on radio selection
            function toggleProductBlocks() {
                if (radioExisting.checked) {
                    existingBlock.style.display = 'block';
                    newBlock.style.display = 'none';
                    newProductSubcatBlock.style.display = 'none';
                    existingSubcatInfo.style.display = 'block';
                    // Disable new product fields
                    document.querySelectorAll('#new_product_block input, #new_product_block select, #new_product_block textarea').forEach(field => {
                        field.disabled = true;
                    });
                    // Enable existing product select
                    existingProductSelect.disabled = false;
                    // Remove required attributes from new product fields
                    let nomField = document.querySelector('input[name="produit_nom"]');
                    let etatField = document.querySelector('select[name="produit_etat"]');
                    if (nomField) nomField.required = false;
                    if (etatField) etatField.required = false;
                } else {
                    existingBlock.style.display = 'none';
                    newBlock.style.display = 'block';
                    newProductSubcatBlock.style.display = 'block';
                    existingSubcatInfo.style.display = 'none';
                    // Enable new product fields
                    document.querySelectorAll('#new_product_block input, #new_product_block select, #new_product_block textarea').forEach(field => {
                        field.disabled = false;
                    });
                    existingProductSelect.disabled = true;
                    let nomField = document.querySelector('input[name="produit_nom"]');
                    let etatField = document.querySelector('select[name="produit_etat"]');
                    if (nomField) nomField.required = true;
                    if (etatField) etatField.required = true;
                }
            }

            radioExisting.addEventListener('change', toggleProductBlocks);
            radioNew.addEventListener('change', toggleProductBlocks);
            toggleProductBlocks();

            // Prefill info when selecting existing product (just for UX)
            existingProductSelect.addEventListener('change', function () {
                const infoDiv = document.getElementById('product_prefill_info');
                if (this.value) {
                    infoDiv.style.display = 'block';
                } else {
                    infoDiv.style.display = 'none';
                }
            });

            // Dynamically load subcategories when category changes (only if creating new product)
            function loadSubcategories() {
                const categoryId = categorySelect.value;
                if (!categoryId) {
                    subcategorySelect.innerHTML = '<option value="">-- Aucune sous-catégorie --</option>';
                    return;
                }

                fetch(`/api/subcategories/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Aucune sous-catégorie --</option>';
                        data.forEach(sub => {
                            options += `<option value="${sub.id}">${sub.nom}</option>`;
                        });
                        subcategorySelect.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error loading subcategories:', error);
                        subcategorySelect.innerHTML = '<option value="">-- Erreur de chargement --</option>';
                    });
            }

            categorySelect.addEventListener('change', function() {
                // Only load subcategories if we are in "new product" mode
                if (radioNew.checked) {
                    loadSubcategories();
                }
            });

            // If new product mode is active initially, load subcategories for the selected category
            if (radioNew.checked && categorySelect.value) {
                loadSubcategories();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }
        .text-primary {
            color: #e91e63 !important;
        }
    </style>
@endpush