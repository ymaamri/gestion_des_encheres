{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Créer une Annonce')
@section('page-title', 'Créer une Nouvelle Annonce')
@section('breadcrumb', 'Créer une Annonce')

@section('content')
    <!-- Header Card -->
    <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="bg-gradient-theme p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white mb-1 fw-bold">
                            <i class="material-symbols-rounded align-middle me-2">add_circle</i>
                            Créer une Nouvelle Annonce
                        </h4>
                        <p class="text-white opacity-8 mb-0">Remplissez les informations pour publier votre enchère</p>
                    </div>
                    <a href="{{ route('annonces.index') }}" class="btn btn-light">
                        <i class="material-symbols-rounded align-middle">arrow_back</i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="material-symbols-rounded align-middle me-2">error</i>
            <strong>Erreur!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('annonces.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Colonne Gauche -->
            <div class="col-lg-8">
                <!-- Section 1: Produit -->
                <div class="card mb-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="material-symbols-rounded text-white">inventory_2</i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #2d3748;">Informations du Produit</h5>
                                <p class="text-muted small mb-0">Décrivez le produit que vous souhaitez vendre</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- ✅ CATEGORY SELECT - now always visible -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" id="categorie_id"
                                class="form-select @error('categorie_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Product Source Selection -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Source du produit :</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="product_source" id="source_existing"
                                        value="existing" checked>
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

                        <!-- Existing Product Selection -->
                        <div id="existing_product_block" class="mb-4">
                            <label class="form-label fw-semibold">Sélectionner un produit existant *</label>
                            <select name="existing_product_id" id="existing_product_id" class="form-select">
                                <option value="">-- Choisissez un produit --</option>
                                @foreach($sellerProducts as $product)
                                    <option value="{{ $product->id }}" data-marque="{{ $product->marque }}"
                                        data-modele="{{ $product->modele }}" data-description="{{ $product->description }}"
                                        data-etat="{{ $product->etat }}" data-photos="{{ json_encode($product->photos) }}"
                                        data-sous_categorie_id="{{ $product->sous_categorie_id }}">
                                        {{ $product->nom }} ({{ $product->marque ?: 'Sans marque' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('existing_product_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div id="product_prefill_info" class="alert alert-info mt-2" style="display: none;">
                                <i class="material-symbols-rounded">info</i> Les détails du produit seront automatiquement utilisés.
                            </div>
                        </div>

                        <!-- New Product Fields -->
                        <div id="new_product_block" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nom du Produit <span class="text-danger">*</span></label>
                                    <input type="text" name="produit_nom"
                                        class="form-control @error('produit_nom') is-invalid @enderror"
                                        value="{{ old('produit_nom') }}">
                                    @error('produit_nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sous-catégorie</label>
                                    <select name="produit_sous_categorie_id" id="produit_sous_categorie_id" class="form-select">
                                        <option value="">-- Aucune sous-catégorie --</option>
                                    </select>
                                    <small class="text-muted">Sélectionnez d'abord une catégorie ci-dessus</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Marque</label>
                                    <input type="text" name="produit_marque"
                                        class="form-control @error('produit_marque') is-invalid @enderror"
                                        value="{{ old('produit_marque') }}">
                                    @error('produit_marque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Modèle</label>
                                    <input type="text" name="produit_modele"
                                        class="form-control @error('produit_modele') is-invalid @enderror"
                                        value="{{ old('produit_modele') }}">
                                    @error('produit_modele')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                    <select name="produit_etat"
                                        class="form-select @error('produit_etat') is-invalid @enderror" required>
                                        <option value="">Sélectionner l'état</option>
                                        <option value="NEUF" {{ old('produit_etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                        <option value="TRES_BON_ETAT" {{ old('produit_etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                        <option value="BON_ETAT" {{ old('produit_etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                        <option value="ACCEPTABLE" {{ old('produit_etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                    </select>
                                    @error('produit_etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Description du produit</label>
                                    <textarea name="produit_description"
                                        class="form-control @error('produit_description') is-invalid @enderror"
                                        rows="3">{{ old('produit_description') }}</textarea>
                                    @error('produit_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Enchère -->
                <div class="card mb-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #4fd1c5 0%, #38b2ac 100%);">
                                <i class="material-symbols-rounded text-white">gavel</i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #2d3748;">Paramètres de l'Enchère</h5>
                                <p class="text-muted small mb-0">Définissez le prix et la durée de votre enchère</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Titre de l'annonce <span class="text-danger">*</span></label>
                                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                    value="{{ old('titre') }}" placeholder="Un titre accrocheur pour votre enchère" required>
                                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prix de départ (MAD) <span class="text-danger">*</span></label>
                                <input type="number" name="prix_depart"
                                    class="form-control @error('prix_depart') is-invalid @enderror" step="0.01" min="0"
                                    value="{{ old('prix_depart') }}" placeholder="0.00" required>
                                @error('prix_depart')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date et heure de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_fin"
                                    class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin') }}" required>
                                @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pas d'enchère (MAD)</label>
                                <input type="number" name="montant_mise" class="form-control" step="1"
                                    value="{{ old('montant_mise', 1) }}" min="1">
                                <small class="text-muted">Montant minimum d'augmentation</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description de l'annonce</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="3" placeholder="Conditions particulières, livraison, etc.">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite -->
            <div class="col-lg-4">
                <!-- Section Photos -->
                <div class="card mb-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);">
                                <i class="material-symbols-rounded text-white">photo_camera</i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #2d3748;">Photo</h5>
                                <p class="text-muted small mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="upload-zone rounded-3 p-4 text-center"
                            onclick="document.getElementById('photos-input').click()">
                            <i class="material-symbols-rounded mb-3"
                                style="font-size: 48px; color: #cbd5e0;">add_photo_alternate</i>
                            <h6 class="fw-bold mb-2" style="color: #4a5568; font-size: 0.9rem;">Ajouter des photos</h6>
                            <p class="text-muted small mb-0">JPG, PNG - Max 2MB</p>
                            <input type="file" id="photos-input" name="photos[]" class="d-none" multiple accept="image/*"
                                onchange="updateFileList(this)">
                        </div>
                        <div id="file-list" class="mt-3 d-flex flex-column gap-2"></div>
                        @error('photos.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <button type="submit" class="btn-gradient w-100">
                                <i class="material-symbols-rounded align-middle me-1" style="font-size: 18px;">publish</i>
                                Publier l'Annonce
                            </button>
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-gradient w-100">
                                <i class="material-symbols-rounded align-middle me-1" style="font-size: 18px;">close</i>
                                Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .form-label {
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-size: 0.875rem;
        }

        .upload-zone {
            border: 2px dashed #e2e8f0;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            border-color: #667eea;
            background: #f7fafc;
        }

        .file-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .icon-shape {
            flex-shrink: 0;
        }
    </style>
@endpush

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
        const newProductSubcatBlock = document.getElementById('new_product_subcat_block');

        // Toggle visibility based on radio selection
        function toggleProductBlocks() {
            if (radioExisting.checked) {
                existingBlock.style.display = 'block';
                newBlock.style.display = 'none';
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
                // Keep category select enabled
                categorySelect.disabled = false;
            } else {
                existingBlock.style.display = 'none';
                newBlock.style.display = 'block';
                // Enable new product fields
                document.querySelectorAll('#new_product_block input, #new_product_block select, #new_product_block textarea').forEach(field => {
                    field.disabled = false;
                });
                existingProductSelect.disabled = true;
                let nomField = document.querySelector('input[name="produit_nom"]');
                let etatField = document.querySelector('select[name="produit_etat"]');
                if (nomField) nomField.required = true;
                if (etatField) etatField.required = true;
                // Keep category select enabled
                categorySelect.disabled = false;
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

        categorySelect.addEventListener('change', function () {
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

    // File list display function
    function updateFileList(input) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';

        if (input.files && input.files.length > 0) {
            if (input.files.length > 5) {
                alert('Vous ne pouvez sélectionner que 5 images maximum.');
                input.value = '';
                return;
            }

            for (let i = 0; i < input.files.length; i++) {
                const badge = document.createElement('div');
                badge.className = 'file-badge';
                badge.innerHTML = '<i class="material-symbols-rounded" style="font-size: 16px;">image</i><span>' + input.files[i].name + '</span>';
                fileList.appendChild(badge);
            }
        }
    }
</script>
@endpush