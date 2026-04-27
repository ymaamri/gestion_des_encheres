{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Ajouter un produit')
@section('page-title', 'Ajouter un produit')
@section('breadcrumb', 'Produits')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-theme text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="material-symbols-rounded align-middle me-2">add_circle</i> Ajouter un produit
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nom du produit <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                    class="form-control @error('nom') is-invalid @enderror rounded-3" required
                                    value="{{ old('nom') }}">
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marque</label>
                                <input type="text" name="marque"
                                    class="form-control @error('marque') is-invalid @enderror rounded-3"
                                    value="{{ old('marque') }}">
                                @error('marque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modèle</label>
                                <input type="text" name="modele"
                                    class="form-control @error('modele') is-invalid @enderror rounded-3"
                                    value="{{ old('modele') }}">
                                @error('modele')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror rounded-3">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                                <select name="categorie_id" id="categorie_id"
                                    class="form-select @error('categorie_id') is-invalid @enderror rounded-3" required>
                                    <option value="">Choisir</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sous‑catégorie</label>
                                <select name="sous_categorie_id" id="sous_categorie_id" class="form-select rounded-3">
                                    <option value="">Aucune</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select @error('etat') is-invalid @enderror rounded-3"
                                    required>
                                    <option value="NEUF" {{ old('etat') == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                    <option value="TRES_BON_ETAT" {{ old('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très
                                        bon état</option>
                                    <option value="BON_ETAT" {{ old('etat') == 'BON_ETAT' ? 'selected' : '' }}>Bon état
                                    </option>
                                    <option value="ACCEPTABLE" {{ old('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable
                                    </option>
                                </select>
                                @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Upload zone for photos -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Photos (plusieurs possibles)</label>
                                <div class="upload-zone rounded-3 p-4 text-center bg-light border-dashed"
                                    onclick="document.getElementById('photos-input').click()">
                                    <i class="material-symbols-rounded mb-2"
                                        style="font-size: 48px; color: #cbd5e0;">add_photo_alternate</i>
                                    <h6 class="fw-bold mb-1" style="color: #4a5568;">Cliquez pour ajouter des photos</h6>
                                    <p class="text-muted small mb-0">JPG, PNG - Max 2MB chacun</p>
                                    <input type="file" id="photos-input" name="photos[]" class="d-none" multiple
                                        accept="image/*" onchange="updateFileList(this)">
                                </div>
                                <div id="file-list" class="mt-3 d-flex flex-wrap gap-2"></div>
                                @error('photos.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('seller.products.index') }}"
                                class="btn btn-outline-secondary rounded-3 px-4">Annuler</a>
                            <button type="submit" class="btn btn-gradient rounded-3 px-4">
                                <i class="material-symbols-rounded align-middle me-1">save</i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
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
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

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

    @push('scripts')
        <script>
            document.getElementById('categorie_id').addEventListener('change', function () {
                let catId = this.value;
                let subSelect = document.getElementById('sous_categorie_id');
                if (catId) {
                    fetch(`/vendeur/subcategories/${catId}`)
                        .then(res => res.json())
                        .then(data => {
                            subSelect.innerHTML = '<option value="">Aucune</option>';
                            data.forEach(sub => {
                                subSelect.innerHTML += `<option value="${sub.id}">${sub.nom}</option>`;
                            });
                        });
                } else {
                    subSelect.innerHTML = '<option value="">Aucune</option>';
                }
            });

            function updateFileList(input) {
                const fileList = document.getElementById('file-list');
                fileList.innerHTML = '';
                if (input.files && input.files.length > 0) {
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
@endsection