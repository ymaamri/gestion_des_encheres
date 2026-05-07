{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Créer un produit cosmique | BidMaster')
@section('page-title', 'Nouveau produit')
@section('breadcrumb', 'Produits / Création')

@section('content')
    <div class="stellar-create-container">
        {{-- Effets d'arrière-plan nébuleux (purement CSS) --}}
        <div class="cosmic-ambient">
            <div class="nebula-layer nebula-1"></div>
            <div class="nebula-layer nebula-2"></div>
            <div class="nebula-layer nebula-3"></div>
            <div class="star-twinkle"></div>
        </div>

        <div class="crystal-card">
            {{-- En-tête inspirant --}}
            <div class="card-aurora">
                <div class="aurora-title">
                    <div class="icon-orbit">
                        <i class="fas fa-feather-alt"></i>
                    </div>
                    <div>
                        <h1>Nouvel <span class="gradient-highlight">éclat</span></h1>
                        <p class="aurora-sub">Donnez vie à un produit, captivez les enchérisseurs</p>
                    </div>
                </div>
                <div class="aurora-badge">
                    <i class="fas fa-gem"></i> Étape 1/1 – Collection vendeur
                </div>
            </div>

            <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" id="cosmicForm">
                @csrf
                <div class="form-dual-grid">
                    {{-- Colonne gauche : informations produit --}}
                    <div class="form-fields-group">
                        <div class="stellar-field">
                            <input type="text" name="nom" id="nom"
                                class="stellar-input @error('nom') is-invalid-custom @enderror" placeholder=" "
                                value="{{ old('nom') }}" required>
                            <label for="nom" class="stellar-label">Nom du produit <span
                                    class="required-star">*</span></label>
                            @error('nom')<div class="error-feedback"><i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}</div>@enderror
                        </div>

                        <div class="double-group">
                            <div class="stellar-field">
                                <input type="text" name="marque" id="marque"
                                    class="stellar-input @error('marque') is-invalid-custom @enderror" placeholder=" "
                                    value="{{ old('marque') }}">
                                <label for="marque" class="stellar-label">Marque</label>
                                @error('marque')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="stellar-field">
                                <input type="text" name="modele" id="modele"
                                    class="stellar-input @error('modele') is-invalid-custom @enderror" placeholder=" "
                                    value="{{ old('modele') }}">
                                <label for="modele" class="stellar-label">Modèle</label>
                                @error('modele')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="stellar-field">
                            <textarea name="description" id="description"
                                class="stellar-input @error('description') is-invalid-custom @enderror" placeholder=" "
                                rows="4">{{ old('description') }}</textarea>
                            <label for="description" class="stellar-label">Description (histoire, caractéristiques)</label>
                            @error('description')<div class="error-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="double-group">
                            <div class="custom-select-wrap">
                                <select name="categorie_id" id="categorie_id"
                                    class="stellar-select @error('categorie_id') is-invalid-custom @enderror" required>
                                    <option value="" disabled selected>Choisir une catégorie</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="select-arrow"><i class="fas fa-chevron-down"></i></div>
                                <span class="static-label">Catégorie <span class="required-star">*</span></span>
                                @error('categorie_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="custom-select-wrap">
                                <select name="sous_categorie_id" id="sous_categorie_id" class="stellar-select">
                                    <option value="">Aucune sous-catégorie</option>
                                </select>
                                <div class="select-arrow"><i class="fas fa-chevron-down"></i></div>
                                <span class="static-label">Sous‑catégorie (optionnelle)</span>
                            </div>
                        </div>

                        <div class="custom-select-wrap">
                            <select name="etat" id="etat" class="stellar-select @error('etat') is-invalid-custom @enderror"
                                required>
                                <option value="NEUF" {{ old('etat') == 'NEUF' ? 'selected' : '' }}>✨ Neuf – jamais utilisé
                                </option>
                                <option value="TRES_BON_ETAT" {{ old('etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>🌟 Très
                                    bon état</option>
                                <option value="BON_ETAT" {{ old('etat') == 'BON_ETAT' ? 'selected' : '' }}>👍 Bon état
                                </option>
                                <option value="ACCEPTABLE" {{ old('etat') == 'ACCEPTABLE' ? 'selected' : '' }}>🔄 Acceptable /
                                    fonctionnel</option>
                            </select>
                            <div class="select-arrow"><i class="fas fa-chevron-down"></i></div>
                            <span class="static-label">État général <span class="required-star">*</span></span>
                            @error('etat')<div class="error-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Colonne droite : gestion des photos --}}
                    <div class="photo-cosmos">
                        <div class="gallery-header">
                            <i class="fas fa-camera-retro"></i>
                            <h3>Vitrine visuelle</h3>
                        </div>
                        <div class="gallery-sub">
                            Des images puissantes multiplient les enchères. Jusqu'à 10 photos.
                        </div>

                        <div id="dropZone" class="drop-zone">
                            <div class="drop-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="drop-title">Glissez / déposez vos images</div>
                            <div class="drop-hint">ou cliquez pour parcourir (JPG, PNG, max 5MB)</div>
                            <input type="file" id="fileInput" name="photos[]" multiple
                                accept="image/jpeg,image/png,image/jpg" style="display:none">
                            <div class="fake-selector"><span class="btn-cosmic-mini">Sélectionner des fichiers</span></div>
                        </div>

                        <div id="photoPreviewContainer" class="preview-grid"></div>
                        @error('photos.*')<div class="error-feedback mt-2"><i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}</div>@enderror
                        <p class="photo-info"><i class="fas fa-info-circle"></i> La première photo sera l'image principale
                            de l'annonce.</p>
                    </div>
                </div>

                <div class="action-strip">
                    <a href="{{ route('seller.products.index') }}" class="btn-cosmic btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn-cosmic btn-primary" id="submitProductBtn">
                        <i class="fas fa-sparkles"></i> Mettre en orbite
                        <div class="btn-shine"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* -------------------------------
                   STELLAR CREATE - OVERRIDES & PREMIUM CSS
                   Intégration parfaite dans le layout existant
                ------------------------------- */
        .stellar-create-container {
            position: relative;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 1rem 2rem;
            isolation: isolate;
        }

        /* Arrière-plan cosmique (fixe mais dans la zone contenu) */
        .cosmic-ambient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .nebula-layer {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: cosmicDrift 22s infinite alternate ease-in-out;
        }

        .nebula-1 {
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3), transparent);
            top: -20%;
            left: -15%;
        }

        .nebula-2 {
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.25), transparent);
            bottom: -15%;
            right: -10%;
            animation-duration: 28s;
        }

        .nebula-3 {
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
            top: 40%;
            left: 30%;
            animation-duration: 18s;
        }

        @keyframes cosmicDrift {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }

            100% {
                transform: translate(4%, 6%) scale(1.2);
                opacity: 0.7;
            }
        }

        .star-twinkle {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(2px 2px at 20% 30%, white, rgba(0, 0, 0, 0)),
                radial-gradient(1px 1px at 60% 70%, rgba(255, 255, 255, 0.8), rgba(0, 0, 0, 0));
            background-repeat: no-repeat;
            background-size: 200px 200px;
            opacity: 0.4;
            animation: starsMove 40s linear infinite;
        }

        @keyframes starsMove {
            0% {
                background-position: 0% 0%;
            }

            100% {
                background-position: 100% 100%;
            }
        }

        /* Carte principale (verre) */
        .crystal-card {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            border-radius: 2.5rem;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(102, 126, 234, 0.2);
            transition: transform 0.3s ease, box-shadow 0.4s;
            overflow: hidden;
        }

        .crystal-card:hover {
            box-shadow: 0 40px 80px -20px rgba(102, 126, 234, 0.35);
            transform: translateY(-4px);
        }

        /* En-tête */
        .card-aurora {
            padding: 2rem 2.5rem;
            background: linear-gradient(115deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.08));
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .aurora-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .icon-orbit {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 12px 20px -8px rgba(102, 126, 234, 0.5);
        }

        .aurora-title h1 {
            font-size: 1.9rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, #1a202c, #2d3748);
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

        .aurora-sub {
            margin: 0;
            font-size: 0.85rem;
            color: #5b6e8c;
        }

        .aurora-badge {
            background: rgba(102, 126, 234, 0.12);
            padding: 0.4rem 1.2rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4c51bf;
            backdrop-filter: blur(4px);
        }

        /* Grille formulaire */
        .form-dual-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem 2.5rem;
        }

        @media (max-width: 950px) {
            .form-dual-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .card-aurora {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Champs flottants */
        .stellar-field {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .stellar-input {
            width: 100%;
            padding: 1rem 1rem 0.6rem;
            font-size: 1rem;
            border: 1.5px solid #e2edf2;
            border-radius: 1.2rem;
            background: white;
            transition: 0.25s;
            font-weight: 500;
            outline: none;
        }

        .stellar-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .stellar-label {
            position: absolute;
            left: 1rem;
            top: 0.9rem;
            background: transparent;
            transition: 0.2s;
            pointer-events: none;
            color: #7f8fa4;
            font-weight: 500;
        }

        .stellar-input:focus~.stellar-label,
        .stellar-input:not(:placeholder-shown)~.stellar-label {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.7rem;
            background: white;
            padding: 0 0.3rem;
            color: #667eea;
        }

        textarea.stellar-input {
            resize: vertical;
            min-height: 100px;
        }

        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .required-star {
            color: #f56565;
            margin-left: 2px;
        }

        .error-feedback {
            font-size: 0.7rem;
            color: #f56565;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .is-invalid-custom {
            border-color: #f56565 !important;
            background-color: #fff5f5;
        }

        /* Selects personnalisés */
        .custom-select-wrap {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .stellar-select {
            width: 100%;
            padding: 0.85rem 1rem;
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
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
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

        /* Partie photo - style cosmique */
        .photo-cosmos {
            background: rgba(248, 250, 252, 0.7);
            border-radius: 1.8rem;
            padding: 1.2rem 1.5rem;
        }

        .gallery-header {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1rem;
        }

        .gallery-header i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gallery-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        .gallery-sub {
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
            color: #5b6e8c;
            border-left: 3px solid #667eea;
            padding-left: 0.8rem;
        }

        .drop-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 1.8rem;
            background: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 1.8rem 1rem;
            cursor: pointer;
            transition: all 0.25s;
            margin-bottom: 1rem;
        }

        .drop-zone:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-2px);
        }

        .drop-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .drop-title {
            font-weight: 700;
            color: #1e293b;
        }

        .drop-hint {
            font-size: 0.7rem;
            color: #718096;
        }

        .fake-selector {
            margin-top: 0.8rem;
        }

        .btn-cosmic-mini {
            background: rgba(102, 126, 234, 0.1);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #4c51bf;
            transition: 0.2s;
            display: inline-block;
        }

        .preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .preview-item {
            position: relative;
            width: 85px;
            height: 85px;
            border-radius: 1rem;
            overflow: hidden;
            background: #eef2ff;
            border: 1px solid #e2e8f0;
            transition: 0.1s;
        }

        .preview-item img {
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
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            cursor: pointer;
            transition: 0.1s;
            z-index: 5;
        }

        .photo-info {
            font-size: 0.7rem;
            color: #8b9eb0;
            margin-top: 1rem;
        }

        /* Actions en bas */
        .action-strip {
            padding: 1.5rem 2.5rem 2rem;
            border-top: 1px solid #eef2f8;
            display: flex;
            justify-content: flex-end;
            gap: 1.2rem;
            flex-wrap: wrap;
            background: rgba(248, 250, 252, 0.5);
        }

        .btn-cosmic {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 2rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: 0.25s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid #cbd5e1;
            color: #334155;
        }

        .btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 20px -8px rgba(102, 126, 234, 0.45);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -10px rgba(102, 126, 234, 0.6);
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
            pointer-events: none;
        }

        .btn-primary:hover .btn-shine {
            left: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // ---------- Gestion des labels flottants (placeholder fictif) ----------
            const allInputs = document.querySelectorAll('.stellar-input');
            allInputs.forEach(inp => {
                if (!inp.value || inp.value === "") inp.setAttribute('placeholder', ' ');
                else inp.setAttribute('placeholder', ' ');
                inp.addEventListener('blur', function () { if (!this.value.trim()) this.setAttribute('placeholder', ' '); });
                inp.addEventListener('focus', function () { this.setAttribute('placeholder', ' '); });
            });

            // ---------- Sous-catégories dynamiques (AJAX) ----------
            const catSelect = document.getElementById('categorie_id');
            const subSelect = document.getElementById('sous_categorie_id');
            if (catSelect) {
                catSelect.addEventListener('change', function () {
                    const catId = this.value;
                    if (catId) {
                        fetch(`/vendeur/subcategories/${catId}`)
                            .then(res => res.json())
                            .then(data => {
                                subSelect.innerHTML = '<option value="">Aucune sous-catégorie</option>';
                                if (Array.isArray(data)) {
                                    data.forEach(sub => {
                                        subSelect.innerHTML += `<option value="${sub.id}">${escapeHtml(sub.nom)}</option>`;
                                    });
                                }
                                const oldSub = "{{ old('sous_categorie_id') }}";
                                if (oldSub && oldSub !== "") subSelect.value = oldSub;
                            })
                            .catch(err => console.warn(err));
                    } else {
                        subSelect.innerHTML = '<option value="">Aucune sous-catégorie</option>';
                    }
                });
                if (catSelect.value) catSelect.dispatchEvent(new Event('change'));
            }

            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function (m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }

            // ---------- Upload multiple avec preview, drag & drop, suppression ----------
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('photoPreviewContainer');
            let selectedFiles = []; // stockage des objets File

            function renderPreviews() {
                previewContainer.innerHTML = '';
                selectedFiles.forEach((file, idx) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'preview-item';
                            previewDiv.setAttribute('data-index', idx);
                            previewDiv.innerHTML = `
                                        <img src="${e.target.result}" alt="aperçu">
                                        <div class="remove-preview" data-idx="${idx}"><i class="fas fa-times"></i></div>
                                    `;
                            previewContainer.appendChild(previewDiv);
                            const rmBtn = previewDiv.querySelector('.remove-preview');
                            if (rmBtn) rmBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                removeFileAtIndex(parseInt(rmBtn.dataset.idx));
                            });
                        };
                        reader.readAsDataURL(file);
                    } else {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'preview-item';
                        previewDiv.style.display = 'flex';
                        previewDiv.style.alignItems = 'center';
                        previewDiv.style.justifyContent = 'center';
                        previewDiv.innerHTML = `<i class="fas fa-file-image"></i><div class="remove-preview" data-idx="${idx}"><i class="fas fa-times"></i></div>`;
                        previewContainer.appendChild(previewDiv);
                        const rmBtn = previewDiv.querySelector('.remove-preview');
                        if (rmBtn) rmBtn.addEventListener('click', (e) => { e.stopPropagation(); removeFileAtIndex(parseInt(rmBtn.dataset.idx)); });
                    }
                });
            }

            function removeFileAtIndex(index) {
                selectedFiles.splice(index, 1);
                updateFileInputFromList();
                renderPreviews();
            }

            function updateFileInputFromList() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            function handleNewFiles(files) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 5 * 1024 * 1024;
                for (let i = 0; i < files.length; i++) {
                    const f = files[i];
                    if (!allowedTypes.includes(f.type)) {
                        alert(`Format non supporté: ${f.name}. Utilisez JPG ou PNG.`);
                        continue;
                    }
                    if (f.size > maxSize) {
                        alert(`Fichier trop volumineux: ${f.name} (max 5MB)`);
                        continue;
                    }
                    const duplicate = selectedFiles.some(ex => ex.name === f.name && ex.size === f.size);
                    if (!duplicate && selectedFiles.length < 12) {
                        selectedFiles.push(f);
                    } else if (selectedFiles.length >= 12) {
                        alert("Maximum 12 photos par produit.");
                        break;
                    }
                }
                updateFileInputFromList();
                renderPreviews();
            }

            if (dropZone) {
                dropZone.addEventListener('click', (e) => {
                    if (e.target === dropZone || e.target.closest('.drop-zone')) fileInput.click();
                });
                const fakeBtn = dropZone.querySelector('.fake-selector');
                if (fakeBtn) fakeBtn.addEventListener('click', (e) => { e.stopPropagation(); fileInput.click(); });

                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length) handleNewFiles(Array.from(e.target.files));
                });

                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.style.borderColor = '#667eea';
                    dropZone.style.background = 'rgba(102,126,234,0.05)';
                });
                dropZone.addEventListener('dragleave', () => {
                    dropZone.style.borderColor = '#cbd5e1';
                    dropZone.style.background = 'rgba(255,255,255,0.8)';
                });
                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.style.borderColor = '#cbd5e1';
                    dropZone.style.background = 'rgba(255,255,255,0.8)';
                    const dt = e.dataTransfer;
                    if (dt.files.length) handleNewFiles(Array.from(dt.files));
                });
            }

            // Désactivation du bouton submit pendant l'envoi
            const form = document.getElementById('cosmicForm');
            const submitBtn = document.getElementById('submitProductBtn');
            if (form) {
                form.addEventListener('submit', function () {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Élévation en cours...';
                    }
                });
            }
        })();
    </script>
@endpush