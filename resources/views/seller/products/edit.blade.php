@extends('layouts.app')

@section('title', 'Modifier le produit | BidMaster')
@section('page-title', 'Modifier le produit')
@section('breadcrumb', 'Produits')

@section('content')
    <div class="stellar-container">
        {{-- Cosmic background effects --}}
        <div class="cosmic-aura">
            <div class="nebula nebula-1"></div>
            <div class="nebula nebula-2"></div>
            <div class="nebula nebula-3"></div>
            <div class="star-field">
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
            </div>
        </div>

        <div class="edit-card" data-augmented-ui>
            <div class="card-glow"></div>

            {{-- Header --}}
            <div class="edit-header">
                <div class="header-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <div class="header-text">
                    <h1 class="editor-title">Affiner votre <span class="gradient-highlight">chef-d'œuvre</span></h1>
                    <p class="editor-subtitle">Affinez les détails, sublimez l'enchère — chaque modification magnifie votre
                        impact</p>
                </div>
                <div class="header-decoration">
                    <div class="orb"></div>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data"
                id="stellarForm">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    {{-- Left column --}}
                    <div class="form-col">
                        <div class="input-floating">
                            <input type="text" name="nom" id="productName"
                                class="stellar-input @error('nom') error-border @enderror"
                                value="{{ old('nom', $product->nom) }}" required autocomplete="off">
                            <label for="productName" class="floating-label">Nom du produit <span
                                    class="required-star">*</span></label>
                            @error('nom')<div class="input-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>@enderror
                        </div>

                        <div class="dual-group">
                            <div class="input-floating">
                                <input type="text" name="marque" id="brand"
                                    class="stellar-input @error('marque') error-border @enderror"
                                    value="{{ old('marque', $product->marque) }}">
                                <label for="brand" class="floating-label">Marque</label>
                                @error('marque')<div class="input-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="input-floating">
                                <input type="text" name="modele" id="model"
                                    class="stellar-input @error('modele') error-border @enderror"
                                    value="{{ old('modele', $product->modele) }}">
                                <label for="model" class="floating-label">Modèle</label>
                                @error('modele')<div class="input-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="input-floating textarea-enhanced">
                            <textarea name="description" id="description" rows="5"
                                class="stellar-input @error('description') error-border @enderror">{{ old('description', $product->description) }}</textarea>
                            <label for="description" class="floating-label">Description du produit</label>
                            @error('description')<div class="input-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="dual-group">
                            <div class="select-custom">
                                <select name="categorie_id" id="categorie_id"
                                    class="stellar-select @error('categorie_id') error-border @enderror" required>
                                    <option value="" disabled selected>Choisissez une famille</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('categorie_id', $product->sousCategorie->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="select-icon"><i class="fas fa-chevron-down"></i></div>
                                <label class="static-label">Catégorie <span class="required-star">*</span></label>
                                @error('categorie_id')<div class="input-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="select-custom">
                                <select name="sous_categorie_id" id="sous_categorie_id" class="stellar-select">
                                    <option value="">Aucune sous-catégorie</option>
                                    @if($product->sousCategorie)
                                        @foreach($product->sousCategorie->categorie->sousCategories ?? [] as $sub)
                                            <option value="{{ $sub->id }}" {{ $product->sous_categorie_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->nom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="select-icon"><i class="fas fa-chevron-down"></i></div>
                                <label class="static-label">Sous‑catégorie</label>
                            </div>
                        </div>

                        <div class="select-custom full-width">
                            <select name="etat" id="productState"
                                class="stellar-select @error('etat') error-border @enderror" required>
                                <option value="NEUF" {{ old('etat', $product->etat) == 'NEUF' ? 'selected' : '' }}>✨ Neuf –
                                    Parfait, jamais utilisé</option>
                                <option value="TRES_BON_ETAT" {{ old('etat', $product->etat) == 'TRES_BON_ETAT' ? 'selected' : '' }}>🌟 Très bon état – Presque neuf</option>
                                <option value="BON_ETAT" {{ old('etat', $product->etat) == 'BON_ETAT' ? 'selected' : '' }}>👍
                                    Bon état – Usure minime</option>
                                <option value="ACCEPTABLE" {{ old('etat', $product->etat) == 'ACCEPTABLE' ? 'selected' : '' }}>🔄 Acceptable – Fonctionnel</option>
                            </select>
                            <div class="select-icon"><i class="fas fa-chevron-down"></i></div>
                            <label class="static-label">État du produit <span class="required-star">*</span></label>
                            @error('etat')<div class="input-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Right column: Photo management --}}
                    <div class="form-col photo-galaxy">
                        <div class="media-section">
                            <div class="section-badge"><i class="fas fa-camera-retro"></i> Galerie visuelle</div>
                            <div class="section-title">Identité visuelle</div>
                            <p class="section-hint">Des images haute définition captivent l'œil. Sublimez votre produit.</p>

                            {{-- Current photos with elegant selection --}}
                            @if($product->photos && count($product->photos))
                                <div class="current-photos">
                                    <div class="gallery-label">Photos actuelles</div>
                                    <div class="photo-grid" id="existingPhotosGrid">
                                        @foreach($product->photos as $photoIndex => $photo)
                                            <div class="photo-card" data-photo="{{ $photo }}">
                                                <div class="photo-preview">
                                                    <img src="{{ Storage::url($photo) }}" alt="photo produit">
                                                    <div class="photo-overlay"></div>
                                                    <label class="delete-checkbox stellar-checkbox">
                                                        <input type="checkbox" name="delete_photos[]" value="{{ $photo }}">
                                                        <span class="checkmark"><i class="fas fa-trash-alt"></i></span>
                                                        <span class="checkbox-label">Marquer pour suppression</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- New photos upload zone --}}
                            <div class="upload-nebula">
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file" id="photosInput" name="photos[]" multiple
                                        accept="image/jpeg,image/png,image/jpg" style="display: none;">
                                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <h4 class="upload-title">Glissez‑déposez ou cliquez</h4>
                                    <p class="upload-info">PNG, JPG jusqu'à 5MB chacun</p>
                                    <div class="upload-btn">Parcourir</div>
                                </div>
                                <div id="newPhotosPreview" class="photos-preview-grid"></div>
                                @error('photos.*')<div class="input-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions">
                    <a href="{{ route('seller.products.index') }}" class="action-btn ghost-btn">
                        <i class="fas fa-arrow-left"></i> Retour au catalogue
                    </a>
                    <button type="submit" class="action-btn primary-btn" id="submitBtn">
                        <i class="fas fa-save"></i> Métamorphoser le produit
                        <div class="btn-shine"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ================================
           STELLAR EDITOR – PURE CUSTOM CSS
           Immersive, glassmorphic, cosmic
        ================================ */
        .stellar-container {
            position: relative;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            isolation: isolate;
        }

        /* Cosmic background */
        .cosmic-aura {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .nebula {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: floatCosmic 25s infinite alternate ease-in-out;
        }

        .nebula-1 {
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.25), transparent);
            top: -20%;
            left: -10%;
        }

        .nebula-2 {
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.2), transparent);
            bottom: -10%;
            right: -5%;
            animation-duration: 30s;
        }

        .nebula-3 {
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15), transparent);
            top: 40%;
            left: 30%;
            animation-duration: 20s;
        }

        @keyframes floatCosmic {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }

            100% {
                transform: translate(5%, 5%) scale(1.2);
                opacity: 0.7;
            }
        }

        .star-field {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            width: 2px;
            height: 2px;
            opacity: 0.6;
            animation: twinkle 4s infinite;
        }

        .star:nth-child(1) {
            top: 15%;
            left: 25%;
            animation-delay: 0s;
        }

        .star:nth-child(2) {
            top: 70%;
            left: 80%;
            animation-delay: 1s;
        }

        .star:nth-child(3) {
            top: 45%;
            left: 15%;
            animation-delay: 2s;
        }

        .star:nth-child(4) {
            top: 85%;
            left: 45%;
            animation-delay: 0.5s;
        }

        .star:nth-child(5) {
            top: 10%;
            left: 90%;
            animation-delay: 1.5s;
        }

        .star:nth-child(6) {
            top: 55%;
            left: 60%;
            animation-delay: 0.8s;
        }

        .star:nth-child(7) {
            top: 30%;
            left: 40%;
            animation-delay: 2.5s;
        }

        .star:nth-child(8) {
            top: 90%;
            left: 10%;
            animation-delay: 1.2s;
        }

        .star:nth-child(9) {
            top: 60%;
            left: 30%;
            animation-delay: 0.3s;
        }

        .star:nth-child(10) {
            top: 20%;
            left: 70%;
            animation-delay: 1.8s;
        }

        .star:nth-child(11) {
            top: 75%;
            left: 55%;
            animation-delay: 2.2s;
        }

        .star:nth-child(12) {
            top: 40%;
            left: 85%;
            animation-delay: 0.9s;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.2;
                transform: scale(1);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.3);
            }
        }

        /* Main Card */
        .edit-card {
            position: relative;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 3rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(102, 126, 234, 0.15);
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .edit-card:hover {
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.18);
            transform: translateY(-3px);
        }

        .card-glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 20%, rgba(102, 126, 234, 0.08), transparent 70%);
            pointer-events: none;
        }

        /* Header */
        .edit-header {
            padding: 2rem 2.5rem;
            background: linear-gradient(115deg, rgba(102, 126, 234, 0.03), rgba(118, 75, 162, 0.05));
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            position: relative;
        }

        .header-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 12px 20px -8px rgba(102, 126, 234, 0.4);
        }

        .editor-title {
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #1e293b, #2d3748);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gradient-highlight {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .editor-subtitle {
            color: #5b6e8c;
            margin: 0.25rem 0 0;
            font-weight: 500;
        }

        .header-decoration .orb {
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
            border-radius: 50%;
            filter: blur(25px);
        }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem 2.5rem;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .edit-header {
                flex-direction: column;
                text-align: center;
            }

            .header-icon {
                margin: 0 auto;
            }
        }

        /* Floating inputs */
        .input-floating {
            position: relative;
            margin-bottom: 2rem;
        }

        .stellar-input {
            width: 100%;
            padding: 1rem 1rem 0.5rem;
            font-size: 1rem;
            border: 1.5px solid #e2edf2;
            border-radius: 1.2rem;
            background: white;
            transition: all 0.25s;
            font-weight: 500;
            outline: none;
        }

        .stellar-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .floating-label {
            position: absolute;
            left: 1rem;
            top: 0.9rem;
            background: transparent;
            transition: 0.2s ease;
            pointer-events: none;
            color: #7f8fa4;
            font-weight: 500;
        }

        .stellar-input:focus~.floating-label,
        .stellar-input:not(:placeholder-shown)~.floating-label {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.7rem;
            background: white;
            padding: 0 0.3rem;
            color: #667eea;
        }

        .textarea-enhanced textarea {
            resize: vertical;
            min-height: 100px;
        }

        .dual-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .required-star {
            color: #f56565;
            margin-left: 2px;
        }

        .input-error {
            font-size: 0.75rem;
            color: #e53e3e;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-border {
            border-color: #f56565 !important;
        }

        /* Select custom */
        .select-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .stellar-select {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: 1.2rem;
            border: 1.5px solid #e2edf2;
            background: white;
            font-size: 0.95rem;
            appearance: none;
            outline: none;
            transition: 0.2s;
            font-weight: 500;
        }

        .stellar-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }

        .select-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
        }

        .static-label {
            position: absolute;
            top: -0.6rem;
            left: 0.8rem;
            background: white;
            padding: 0 0.4rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: #667eea;
            letter-spacing: 0.3px;
        }

        .full-width {
            grid-column: span 2;
        }

        @media (max-width: 650px) {
            .full-width {
                grid-column: span 1;
            }
        }

        /* Photo gallery */
        .photo-galaxy {
            background: rgba(248, 250, 252, 0.5);
            border-radius: 1.8rem;
            padding: 1rem;
        }

        .media-section {
            padding: 0.5rem;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea15, #764ba215);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 0.8rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .section-hint {
            font-size: 0.8rem;
            color: #5b6e8c;
            margin-bottom: 1.5rem;
        }

        .current-photos {
            margin-bottom: 2rem;
        }

        .gallery-label {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 1rem;
        }

        .photo-card {
            position: relative;
            border-radius: 1.2rem;
            overflow: hidden;
            background: #f1f5f9;
            transition: transform 0.2s;
        }

        .photo-card:hover {
            transform: scale(0.98);
        }

        .photo-preview {
            position: relative;
            aspect-ratio: 1 / 1;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
            opacity: 0;
            transition: 0.2s;
        }

        .photo-card:hover .photo-overlay {
            opacity: 1;
        }

        .stellar-checkbox {
            position: absolute;
            bottom: 8px;
            left: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            border-radius: 30px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 0.7rem;
            color: white;
            transition: 0.2s;
        }

        .stellar-checkbox input {
            display: none;
        }

        .stellar-checkbox .checkmark {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.1s;
        }

        .stellar-checkbox input:checked+.checkmark {
            background: #ef4444;
            color: white;
        }

        .stellar-checkbox .checkbox-label {
            font-weight: 500;
        }

        /* Upload zone */
        .upload-nebula {
            margin-top: 1rem;
        }

        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 1.8rem;
            background: rgba(255, 255, 255, 0.7);
            text-align: center;
            padding: 2rem 1rem;
            cursor: pointer;
            transition: all 0.25s;
        }

        .upload-zone:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-3px);
        }

        .upload-icon {
            font-size: 2.8rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .upload-title {
            font-weight: 700;
            margin: 0.5rem 0 0.2rem;
            color: #1e293b;
        }

        .upload-info {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .upload-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.4rem 1.5rem;
            border-radius: 30px;
            font-size: 0.8rem;
            margin-top: 0.8rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .photos-preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 1rem;
        }

        .preview-thumb {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 1rem;
            overflow: hidden;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
        }

        .preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-preview {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            cursor: pointer;
            transition: 0.1s;
        }

        /* Actions */
        .form-actions {
            padding: 1.5rem 2.5rem 2rem;
            border-top: 1px solid #eef2f6;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
            background: rgba(248, 250, 252, 0.4);
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 2rem;
            border-radius: 40px;
            font-weight: 700;
            transition: 0.25s;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .ghost-btn {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #334155;
        }

        .ghost-btn:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .primary-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 16px -8px rgba(102, 126, 234, 0.4);
        }

        .primary-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(102, 126, 234, 0.6);
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .primary-btn:hover .btn-shine {
            left: 100%;
        }

        /* Animations */
        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(15px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .edit-card,
        .form-col {
            animation: fadeSlide 0.5s ease forwards;
        }

        .form-col:last-child {
            animation-delay: 0.1s;
        }

        input,
        select,
        textarea {
            transition: all 0.2s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Floating labels fix: add placeholder to force label movement
            document.querySelectorAll('.stellar-input').forEach(inp => {
                if (!inp.value.trim()) inp.setAttribute('placeholder', ' ');
                else inp.setAttribute('placeholder', ' ');
                inp.addEventListener('blur', function () { if (!this.value.trim()) this.setAttribute('placeholder', ' '); });
            });

            // Subcategories dynamic loading
            const catSelect = document.getElementById('categorie_id');
            const subSelect = document.getElementById('sous_categorie_id');
            if (catSelect) {
                catSelect.addEventListener('change', function () {
                    let catId = this.value;
                    if (catId) {
                        fetch(`/vendeur/subcategories/${catId}`)
                            .then(res => res.json())
                            .then(data => {
                                subSelect.innerHTML = '<option value="">Aucune sous-catégorie</option>';
                                data.forEach(sub => {
                                    subSelect.innerHTML += `<option value="${sub.id}">${escapeHtml(sub.nom)}</option>`;
                                });
                            }).catch(() => { });
                    } else {
                        subSelect.innerHTML = '<option value="">Aucune sous-catégorie</option>';
                    }
                });
            }

            function escapeHtml(str) { return str.replace(/[&<>]/g, function (m) { if (m === '&') return '&amp;'; if (m === '<') return '&lt;'; if (m === '>') return '&gt;'; return m; }); }

            // File upload preview
            const uploadZone = document.getElementById('uploadZone');
            const fileInput = document.getElementById('photosInput');
            const previewContainer = document.getElementById('newPhotosPreview');

            if (uploadZone) {
                uploadZone.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', handleFilePreview);
                function handleFilePreview() {
                    previewContainer.innerHTML = '';
                    if (fileInput.files) {
                        Array.from(fileInput.files).forEach((file, idx) => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    const thumb = document.createElement('div');
                                    thumb.className = 'preview-thumb';
                                    thumb.innerHTML = `<img src="${e.target.result}" alt="preview"><div class="remove-preview" data-index="${idx}"><i class="fas fa-times"></i></div>`;
                                    previewContainer.appendChild(thumb);
                                    thumb.querySelector('.remove-preview').addEventListener('click', (ev) => {
                                        ev.stopPropagation();
                                        removeFileFromInput(idx);
                                        thumb.remove();
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                }
                function removeFileFromInput(index) {
                    const dt = new DataTransfer();
                    const files = fileInput.files;
                    for (let i = 0; i < files.length; i++) {
                        if (i !== index) dt.items.add(files[i]);
                    }
                    fileInput.files = dt.files;
                    if (fileInput.files.length === 0) previewContainer.innerHTML = '';
                    else handleFilePreview();
                }
            }

            // Submit loading effect
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('stellarForm');
            if (form) {
                form.addEventListener('submit', () => {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Métamorphose en cours...';
                    }
                });
            }
        })();
    </script>
@endpush