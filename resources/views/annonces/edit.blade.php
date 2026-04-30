{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier l\'annonce – BidMaster')
@section('page-title', 'Actualiser votre enchère')
@section('breadcrumb', 'Modifier annonce')

@section('content')
    <div class="cosmic-edit-container">
        {{-- AURA BACKGROUND --}}
        <div class="cosmic-aura">
            <div class="aura-sphere aura-1"></div>
            <div class="aura-sphere aura-2"></div>
            <div class="aura-sphere aura-3"></div>
        </div>

        {{-- HERO SECTION --}}
        <div class="edit-hero">
            <div class="hero-glow"></div>
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-sync-alt"></i> Mise à jour
                </div>
                <h1 class="hero-title">
                    Peaufinez <span class="gradient-text">votre enchère</span>
                </h1>
                <p class="hero-subtitle">
                    Modifiez les informations de votre annonce. Chaque amélioration attire davantage d’enchérisseurs.
                </p>
            </div>
            <svg class="hero-wave" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#ffffff" fill-opacity="1" d="M0,64 C480,128 960,0 1440,64 L1440,120 L0,120 Z"></path>
            </svg>
        </div>

        {{-- FORM CARD --}}
        <form method="POST" action="{{ route('annonces.update', $annonce) }}" enctype="multipart/form-data"
            class="stellar-form" id="editAuctionForm">
            @csrf
            @method('PUT')

            <div class="form-grid">
                {{-- LEFT COLUMN: MAIN FIELDS --}}
                <div class="form-main">
                    {{-- SECTION PRODUIT --}}
                    <div class="form-card floating-card">
                        <div class="card-header-custom">
                            <div class="header-icon gradient-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h3>Informations produit</h3>
                                <p>Modifiez les détails de votre produit</p>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            <div class="grid-two-columns">
                                <div class="input-group-modern">
                                    <label class="label-modern">Nom du produit <span class="required-star">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-cube"></i>
                                        <input type="text" name="produit_nom" class="field-modern"
                                            value="{{ old('produit_nom', $annonce->produit->nom) }}" required>
                                    </div>
                                    @error('produit_nom')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">Catégorie <span class="required-star">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-layer-group"></i>
                                        <select name="categorie_id" id="categorie_id" class="field-modern" required>
                                            <option value="">Sélectionner une catégorie</option>
                                            @foreach($categories as $categorie)
                                                <option value="{{ $categorie->id }}" {{ old('categorie_id', $annonce->produit->sousCategorie->categorie_id ?? $annonce->produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                    {{ $categorie->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categorie_id')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">Marque</label>
                                    <div class="input-icon">
                                        <i class="fas fa-trademark"></i>
                                        <input type="text" name="produit_marque" class="field-modern"
                                            value="{{ old('produit_marque', $annonce->produit->marque) }}">
                                    </div>
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">Modèle</label>
                                    <div class="input-icon">
                                        <i class="fas fa-microchip"></i>
                                        <input type="text" name="produit_modele" class="field-modern"
                                            value="{{ old('produit_modele', $annonce->produit->modele) }}">
                                    </div>
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">État <span class="required-star">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                        <select name="produit_etat" class="field-modern" required>
                                            <option value="NEUF" {{ old('produit_etat', $annonce->produit->etat) == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                            <option value="TRES_BON_ETAT" {{ old('produit_etat', $annonce->produit->etat) == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très bon état
                                            </option>
                                            <option value="BON_ETAT" {{ old('produit_etat', $annonce->produit->etat) == 'BON_ETAT' ? 'selected' : '' }}>Bon état</option>
                                            <option value="ACCEPTABLE" {{ old('produit_etat', $annonce->produit->etat) == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable
                                            </option>
                                        </select>
                                    </div>
                                    @error('produit_etat')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="input-group-modern full-width">
                                    <label class="label-modern">Description du produit</label>
                                    <div class="input-icon textarea-icon">
                                        <i class="fas fa-align-left"></i>
                                        <textarea name="produit_description" class="field-modern" rows="3"
                                            placeholder="Caractéristiques, accessoires...">{{ old('produit_description', $annonce->produit->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION ENCHÈRE --}}
                    <div class="form-card floating-card">
                        <div class="card-header-custom">
                            <div class="header-icon gradient-icon-alt">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <div>
                                <h3>Paramètres de l'enchère</h3>
                                <p>Prix, durée et conditions</p>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            <div class="input-group-modern">
                                <label class="label-modern">Titre de l'annonce <span class="required-star">*</span></label>
                                <div class="input-icon">
                                    <i class="fas fa-heading"></i>
                                    <input type="text" name="titre" class="field-modern"
                                        value="{{ old('titre', $annonce->titre) }}" required>
                                </div>
                                @error('titre')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="grid-two-columns">
                                <div class="input-group-modern">
                                    <label class="label-modern">Prix de départ (TND) <span
                                            class="required-star">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-coins"></i>
                                        <input type="number" name="prix_depart" class="field-modern" step="0.01" min="0"
                                            value="{{ old('prix_depart', $annonce->prix_depart) }}" required>
                                    </div>
                                    @error('prix_depart')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">Date & heure de fin <span
                                            class="required-star">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                        <input type="datetime-local" name="date_fin" class="field-modern"
                                            value="{{ old('date_fin', \Carbon\Carbon::parse($annonce->date_fin)->format('Y-m-d\TH:i')) }}"
                                            required>
                                    </div>
                                    @error('date_fin')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="input-group-modern">
                                    <label class="label-modern">Pas d'enchère (MAD)</label>
                                    <div class="input-icon">
                                        <i class="fas fa-arrow-up"></i>
                                        <input type="number" name="montant_mise" class="field-modern" step="1" min="1"
                                            value="{{ old('montant_mise', $annonce->montant_mise ?? 1) }}">
                                    </div>
                                    <small class="field-hint">Montant minimum d'augmentation</small>
                                </div>
                            </div>
                            <div class="input-group-modern">
                                <label class="label-modern">Conditions particulières</label>
                                <div class="input-icon textarea-icon">
                                    <i class="fas fa-file-alt"></i>
                                    <textarea name="description" class="field-modern" rows="4"
                                        placeholder="Livraison, garantie, retour...">{{ old('description', $annonce->description) }}</textarea>
                                </div>
                                @error('description')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: GALLERY & ACTIONS --}}
                <div class="form-sidebar">
                    <div class="form-card floating-card sticky-side">
                        <div class="card-header-custom">
                            <div class="header-icon gradient-icon-photo">
                                <i class="fas fa-images"></i>
                            </div>
                            <div>
                                <h3>Galerie visuelle</h3>
                                <p>Photos actuelles & nouvelles</p>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            {{-- CURRENT PHOTOS --}}
                            @if(isset($annonce->produit->photos) && count($annonce->produit->photos) > 0)
                                <div class="current-photos-section">
                                    <label class="label-modern">Photos actuelles</label>
                                    <div class="existing-photos-grid" id="existingPhotosGrid">
                                        @foreach($annonce->produit->photos as $index => $photo)
                                            <div class="existing-photo-card" data-photo="{{ $photo }}">
                                                <img src="{{ Storage::url($photo) }}" alt="photo produit">
                                                <div class="delete-check-overlay">
                                                    <label class="checkbox-custom">
                                                        <input type="checkbox" name="delete_photos[]" value="{{ $photo }}"
                                                            class="delete-photo-checkbox">
                                                        <span class="checkmark"></span>
                                                        <span class="delete-text">Supprimer</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- UPLOAD NEW PHOTOS --}}
                            <div class="upload-zone-cosmic" id="uploadZone">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h4>Ajouter / remplacer des images</h4>
                                <p>JPG, PNG – max 5 fichiers, 2Mo chacun</p>
                                <input type="file" id="photos-input" name="photos[]" class="upload-input" multiple
                                    accept="image/jpeg,image/png,image/jpg">
                                <div class="upload-btn-glow">Parcourir</div>
                            </div>
                            <div id="imagePreviewGrid" class="image-preview-grid"></div>
                            @error('photos.*')<div class="field-error mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="action-buttons-group">
                            <button type="submit" class="btn-primary-cosmic">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                                <span class="btn-ripple"></span>
                            </button>
                            <a href="{{ route('annonces.index') }}" class="btn-secondary-cosmic">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ===============================================
                    COSMIC EDIT - PURE CUSTOM DESIGN
                    Matches create page style perfectly
                ================================================== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap');

        .cosmic-edit-container {
            position: relative;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* AURA BACKGROUND */
        .cosmic-aura {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
            overflow: hidden;
            pointer-events: none;
        }

        .aura-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.5;
            animation: floatAura 18s infinite alternate ease-in-out;
        }

        .aura-1 {
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.25) 0%, rgba(118, 75, 162, 0.1) 100%);
            top: -20%;
            left: -15%;
            animation-duration: 22s;
        }

        .aura-2 {
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.2) 0%, rgba(102, 126, 234, 0.05) 100%);
            bottom: -30%;
            right: -20%;
            animation-duration: 28s;
            animation-direction: alternate-reverse;
        }

        .aura-3 {
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(102, 126, 234, 0.05) 100%);
            top: 40%;
            left: 30%;
            animation-duration: 25s;
            opacity: 0.3;
        }

        @keyframes floatAura {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(5%, 5%) scale(1.2);
            }
        }

        /* HERO SECTION */
        .edit-hero {
            position: relative;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 56px;
            margin-bottom: 3rem;
            padding: 2.5rem 2rem;
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 45px -15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .hero-glow {
            position: absolute;
            top: -30%;
            right: -15%;
            width: 40%;
            height: 180%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
            filter: blur(60px);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding-bottom: 50px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(102, 126, 234, 0.12);
            backdrop-filter: blur(8px);
            padding: 0.4rem 1.2rem;
            border-radius: 60px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 1.2rem;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #4a5568;
            max-width: 550px;
            margin: 0 auto;
        }

        .hero-wave {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
            pointer-events: none;
        }

        /* FORM GRID & CARDS */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
        }

        @media (max-width: 1100px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-sidebar .sticky-side {
                position: relative;
                top: 0;
            }
        }

        .form-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 40px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.3s;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .floating-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 28px 40px -16px rgba(0, 0, 0, 0.12);
        }

        .card-header-custom {
            padding: 1.2rem 1.8rem;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.5);
        }

        .header-icon {
            width: 56px;
            height: 56px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
        }

        .gradient-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .gradient-icon-alt {
            background: linear-gradient(135deg, #38b2ac, #2c7a7b);
        }

        .gradient-icon-photo {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
        }

        .card-header-custom h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            color: #1a202c;
        }

        .card-header-custom p {
            margin: 0;
            font-size: 0.8rem;
            color: #718096;
        }

        .card-body-custom {
            padding: 1.8rem;
        }

        /* FORM FIELDS */
        .input-group-modern {
            margin-bottom: 1.5rem;
        }

        .label-modern {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            color: #2d3748;
            letter-spacing: -0.2px;
        }

        .required-star {
            color: #f56565;
        }

        .input-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            color: #a0aec0;
            font-size: 1rem;
            pointer-events: none;
            transition: 0.2s;
        }

        .field-modern {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            border: 2px solid #e2e8f0;
            border-radius: 28px;
            background: white;
            font-size: 0.95rem;
            transition: all 0.25s;
            outline: none;
            font-family: inherit;
        }

        textarea.field-modern {
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            resize: vertical;
        }

        .field-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .textarea-icon i {
            align-self: flex-start;
            margin-top: 1rem;
        }

        .field-error {
            font-size: 0.7rem;
            color: #f56565;
            margin-top: 0.3rem;
            padding-left: 0.5rem;
        }

        .field-hint {
            font-size: 0.7rem;
            color: #a0aec0;
            margin-top: 0.3rem;
            display: block;
        }

        /* TWO COLUMN GRID */
        .grid-two-columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .full-width {
            grid-column: span 2;
        }

        @media (max-width: 680px) {
            .grid-two-columns {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }

        /* CURRENT PHOTOS GALLERY */
        .current-photos-section {
            margin-bottom: 2rem;
        }

        .existing-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 0.8rem;
        }

        .existing-photo-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            background: #edf2f7;
            border: 2px solid white;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
            transition: 0.2s;
        }

        .existing-photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-check-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            padding: 0.5rem;
            display: flex;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .existing-photo-card:hover .delete-check-overlay {
            opacity: 1;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.2rem 0.7rem;
            border-radius: 60px;
            cursor: pointer;
            font-size: 0.7rem;
            font-weight: 600;
            color: #e53e3e;
        }

        .checkbox-custom input {
            display: none;
        }

        .checkmark {
            width: 16px;
            height: 16px;
            border: 2px solid #e53e3e;
            border-radius: 4px;
            display: inline-block;
            position: relative;
            background: white;
        }

        .checkbox-custom input:checked+.checkmark::after {
            content: "✓";
            position: absolute;
            top: -2px;
            left: 2px;
            font-size: 12px;
            color: #e53e3e;
        }

        .delete-text {
            font-weight: 600;
        }

        /* UPLOAD ZONE */
        .upload-zone-cosmic {
            position: relative;
            background: rgba(102, 126, 234, 0.05);
            border: 2px dashed #cbd5e0;
            border-radius: 32px;
            padding: 2rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }

        .upload-zone-cosmic:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.08);
            transform: scale(0.99);
        }

        .upload-icon {
            font-size: 2.8rem;
            color: #a0aec0;
            margin-bottom: 0.5rem;
        }

        .upload-zone-cosmic h4 {
            font-weight: 700;
            margin: 0.5rem 0 0.2rem;
            color: #2d3748;
        }

        .upload-zone-cosmic p {
            font-size: 0.75rem;
            color: #718096;
        }

        .upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-btn-glow {
            display: inline-block;
            margin-top: 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1.8rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: 0.2s;
            pointer-events: none;
        }

        /* NEW IMAGES PREVIEW */
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .preview-thumb {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            background: #edf2f7;
            border: 2px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-preview {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .remove-preview:hover {
            background: #f56565;
        }

        /* ACTION BUTTONS */
        .action-buttons-group {
            padding: 1.5rem 1.8rem 1.8rem;
            border-top: 1px solid rgba(102, 126, 234, 0.1);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-primary-cosmic,
        .btn-secondary-cosmic {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.9rem 1rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.25s;
            text-decoration: none;
            border: none;
            cursor: pointer;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-cosmic {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 18px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-cosmic:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary-cosmic {
            background: #f1f5f9;
            color: #4a5568;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary-cosmic:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: rippleAnim 0.6s linear;
            pointer-events: none;
        }

        @keyframes rippleAnim {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .sticky-side {
            position: sticky;
            top: 100px;
        }

        @media (max-width: 1100px) {
            .sticky-side {
                position: relative;
                top: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // ---------- NEW IMAGES PREVIEW (with remove) ----------
            const fileInput = document.getElementById('photos-input');
            const previewGrid = document.getElementById('imagePreviewGrid');
            const uploadZone = document.getElementById('uploadZone');
            let newFiles = [];

            function updateNewPreview() {
                previewGrid.innerHTML = '';
                newFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const thumb = document.createElement('div');
                        thumb.className = 'preview-thumb';
                        thumb.innerHTML = `
                                    <img src="${e.target.result}" alt="aperçu">
                                    <div class="remove-preview" data-index="${idx}"><i class="fas fa-times"></i></div>
                                `;
                        previewGrid.appendChild(thumb);
                        thumb.querySelector('.remove-preview').addEventListener('click', (ev) => {
                            ev.stopPropagation();
                            newFiles.splice(idx, 1);
                            updateNewFileInput();
                            updateNewPreview();
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateNewFileInput() {
                const dataTransfer = new DataTransfer();
                newFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            fileInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                if (newFiles.length + files.length > 5) {
                    alert('Maximum 5 nouvelles images autorisées.');
                    fileInput.value = '';
                    return;
                }
                newFiles.push(...files);
                updateNewPreview();
                updateNewFileInput();
            });

            uploadZone.addEventListener('click', () => fileInput.click());
            // drag & drop
            uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.style.borderColor = '#667eea'; });
            uploadZone.addEventListener('dragleave', () => uploadZone.style.borderColor = '#cbd5e0');
            uploadZone.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadZone.style.borderColor = '#cbd5e0';
                const dtFiles = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                if (newFiles.length + dtFiles.length > 5) {
                    alert('Maximum 5 images.');
                    return;
                }
                newFiles.push(...dtFiles);
                updateNewPreview();
                updateNewFileInput();
            });

            // BUTTON RIPPLE EFFECT
            const primaryBtn = document.querySelector('.btn-primary-cosmic');
            if (primaryBtn) {
                primaryBtn.addEventListener('click', function (e) {
                    const ripple = document.createElement('span');
                    ripple.className = 'btn-ripple';
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = e.clientX - rect.left - size / 2 + 'px';
                    ripple.style.top = e.clientY - rect.top - size / 2 + 'px';
                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            }
        })();
    </script>
@endpush