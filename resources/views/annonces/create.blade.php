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

                        <!-- Existing Product Selection (hidden by default if new product is chosen) -->
                        <div id="existing_product_block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Sélectionner un produit existant *</label>
                                        <select name="existing_product_id" id="existing_product_id" class="form-control">
                                            <option value="">-- Choisissez un produit --</option>
                                            @foreach($sellerProducts as $product)
                                                <option value="{{ $product->id }}" data-marque="{{ $product->marque }}"
                                                    data-modele="{{ $product->modele }}"
                                                    data-description="{{ $product->description }}"
                                                    data-etat="{{ $product->etat }}"
                                                    data-photos="{{ json_encode($product->photos) }}">
                                                    {{ $product->nom }} ({{ $product->marque ?: 'Sans marque' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('existing_product_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!-- Pre-filled info from selected product (optional) -->
                            <div id="product_prefill_info" class="alert alert-info mt-2" style="display: none;">
                                <i class="material-symbols-rounded">info</i> Les détails du produit seront automatiquement
                                utilisés.
                            </div>
                        </div>

                        <!-- New Product Fields (hidden initially if existing product is selected) -->
                        <div id="new_product_block" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Nom du Produit *</label>
                                        <input type="text" name="produit_nom" class="form-control"
                                            value="{{ old('produit_nom') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Marque</label>
                                        <input type="text" name="produit_marque" class="form-control"
                                            value="{{ old('produit_marque') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Modèle</label>
                                        <input type="text" name="produit_modele" class="form-control"
                                            value="{{ old('produit_modele') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <select name="produit_etat" class="form-control">
                                            <option value="">Sélectionner l'État</option>
                                            <option value="NEUF" {{ old('produit_etat') == 'NEUF' ? 'selected' : '' }}>Neuf
                                            </option>
                                            <option value="TRES_BON_ETAT" {{ old('produit_etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                            <option value="BON_ETAT" {{ old('produit_etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                            <option value="ACCEPTABLE" {{ old('produit_etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Description du Produit</label>
                                        <textarea name="produit_description" class="form-control"
                                            rows="3">{{ old('produit_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Photos du Produit</label>
                                        <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                        <small class="text-muted">Vous pouvez sélectionner plusieurs images (max 5)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Common Auction Fields (always visible) -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Titre de l'Annonce *</label>
                                    <input type="text" name="titre" class="form-control" value="{{ old('titre') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Catégorie *</label>
                                    <select name="categorie_id" class="form-control" required>
                                        <option value="">Sélectionner une Catégorie</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                                {{ $categorie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Description de l'Annonce</label>
                                    <textarea name="description" class="form-control"
                                        rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Prix de Départ (MAD) *</label>
                                    <input type="number" name="prix_depart" class="form-control" step="0.01"
                                        value="{{ old('prix_depart') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Date et Heure de Fin *</label>
                                    <input type="datetime-local" name="date_fin" class="form-control"
                                        value="{{ old('date_fin') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Pas d'enchère (MAD)</label>
                                    <input type="number" name="montant_mise" class="form-control" step="1"
                                        value="{{ old('montant_mise', 1) }}" min="1">
                                    <small class="text-muted">Montant minimum d'augmentation</small>
                                </div>
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

            // Toggle visibility based on radio selection
            function toggleProductBlocks() {
                if (radioExisting.checked) {
                    existingBlock.style.display = 'block';
                    newBlock.style.display = 'none';
                    // Disable new product fields so they are not submitted
                    document.querySelectorAll('#new_product_block input, #new_product_block select, #new_product_block textarea').forEach(field => {
                        field.disabled = true;
                    });
                    // Enable existing product select
                    existingProductSelect.disabled = false;
                    // Remove required attribute from new product fields
                    document.querySelector('input[name="produit_nom"]').required = false;
                    document.querySelector('select[name="produit_etat"]').required = false;
                } else {
                    existingBlock.style.display = 'none';
                    newBlock.style.display = 'block';
                    document.querySelectorAll('#new_product_block input, #new_product_block select, #new_product_block textarea').forEach(field => {
                        field.disabled = false;
                    });
                    existingProductSelect.disabled = true;
                    document.querySelector('input[name="produit_nom"]').required = true;
                    document.querySelector('select[name="produit_etat"]').required = true;
                }
            }

            radioExisting.addEventListener('change', toggleProductBlocks);
            radioNew.addEventListener('change', toggleProductBlocks);

            // Initial state
            toggleProductBlocks();

            // Optional: Pre-fill info when selecting an existing product (just for UX)
            existingProductSelect.addEventListener('change', function () {
                const infoDiv = document.getElementById('product_prefill_info');
                if (this.value) {
                    infoDiv.style.display = 'block';
                } else {
                    infoDiv.style.display = 'none';
                }
            });
        });
    </script>
@endpush