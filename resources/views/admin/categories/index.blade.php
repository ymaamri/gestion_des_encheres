{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('page-title', 'Gestion des Catégories')
@section('breadcrumb', 'Catégories')

@section('content')
    <div class="admin-categories-page">
        <!-- En-tête décoratif -->
        <div class="page-header-decor">
            <div class="header-content">
                <h1 class="page-title">
                    <span class="icon-circle"><i class="fas fa-layer-group"></i></span>
                    Gestion des Catégories
                    <span class="badge-counter">{{ $categories->count() }} catégories</span>
                </h1>
                <p class="page-subtitle">Organisez et gérez vos familles de produits</p>
            </div>
            <div class="header-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>

        <div class="categories-layout">
            <!-- Panneau de création -->
            <aside class="create-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <i class="fas fa-plus-circle panel-icon"></i>
                        <h2>Nouvelle catégorie</h2>
                    </div>
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="create-form">
                        @csrf
                        <div class="form-floating-group">
                            <input type="text" name="nom" id="cat_nom" class="form-input" placeholder=" " required>
                            <label for="cat_nom">Nom *</label>
                            <div class="input-highlight"></div>
                        </div>
                        <div class="form-floating-group">
                            <textarea name="description" id="cat_desc" class="form-input form-textarea" placeholder=" "
                                rows="3"></textarea>
                            <label for="cat_desc">Description</label>
                            <div class="input-highlight"></div>
                        </div>
                        <div class="form-floating-group">
                            <input type="text" name="icone" id="cat_icone" class="form-input" value="fa-tag"
                                placeholder=" ">
                            <label for="cat_icone">Icône (Font Awesome)</label>
                            <small class="form-hint">Ex: fa-laptop, fa-tshirt, fa-gem</small>
                            <div class="input-highlight"></div>
                        </div>
                        <button type="submit" class="btn-create">
                            <i class="fas fa-save"></i> Créer la catégorie
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Grille des catégories -->
            <main class="categories-grid">
                @if($categories->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">📂</div>
                        <h3>Aucune catégorie</h3>
                        <p>Commencez par créer votre première catégorie.</p>
                    </div>
                @else
                    @foreach($categories as $categorie)
                        <div class="category-card">
                            <div class="card-accent"></div>
                            <div class="card-body">
                                <div class="card-icon-wrapper">
                                    {{-- Ici le fallback garanti : si icone est vide/null, on affiche fa-tag --}}
                                    <i class="fas {{ $categorie->icone ?: 'fa-tag' }} fa-2x"></i>
                                </div>
                                <div class="card-info">
                                    <h3 class="card-title">{{ $categorie->nom }}</h3>
                                    <p class="card-desc">
                                        {{ $categorie->description ? Str::limit($categorie->description, 80) : 'Aucune description' }}
                                    </p>
                                    <div class="card-meta">
                                        <span class="meta-badge">
                                            <i class="fas fa-folder-tree"></i> {{ $categorie->sousCategories->count() }} sous-cat.
                                        </span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="action-btn btn-subcats" data-modal="subcategoryModal{{ $categorie->id }}">
                                        <i class="fas fa-sitemap"></i> Sous-cat.
                                    </button>
                                    <button class="action-btn btn-edit" data-modal="editCategoryModal{{ $categorie->id }}">
                                        <i class="fas fa-pen"></i> Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}"
                                        onsubmit="return confirm('Supprimer cette catégorie ? Toutes ses données seront perdues.')"
                                        class="action-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </main>
        </div>
    </div>

    {{-- ==================== MODALES ==================== --}}
    @foreach($categories as $categorie)
        <!-- Modale Édition Catégorie -->
        <div class="custom-modal" id="editCategoryModal{{ $categorie->id }}">
            <div class="modal-overlay" data-modal-close></div>
            <div class="modal-container">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Modifier la catégorie</h3>
                    <button class="modal-close" data-modal-close>&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-floating-group">
                            <input type="text" name="nom" id="edit_nom_{{ $categorie->id }}" class="form-input"
                                value="{{ $categorie->nom }}" placeholder=" " required>
                            <label for="edit_nom_{{ $categorie->id }}">Nom *</label>
                        </div>
                        <div class="form-floating-group">
                            <textarea name="description" id="edit_desc_{{ $categorie->id }}" class="form-input form-textarea"
                                placeholder=" " rows="3">{{ $categorie->description }}</textarea>
                            <label for="edit_desc_{{ $categorie->id }}">Description</label>
                        </div>
                        <div class="form-floating-group">
                            {{-- Le champ icone pré-rempli reste tel quel --}}
                            <input type="text" name="icone" id="edit_icone_{{ $categorie->id }}" class="form-input"
                                value="{{ $categorie->icone }}" placeholder=" ">
                            <label for="edit_icone_{{ $categorie->id }}">Icône (Font Awesome)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-modal-close>Annuler</button>
                        <button type="submit" class="btn-save">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modale Sous-catégories -->
        <div class="custom-modal" id="subcategoryModal{{ $categorie->id }}">
            <div class="modal-overlay" data-modal-close></div>
            <div class="modal-container modal-lg">
                <div class="modal-header">
                    <h3><i class="fas fa-sitemap"></i> Sous-catégories - {{ $categorie->nom }}</h3>
                    <button class="modal-close" data-modal-close>&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire d'ajout rapide -->
                    <div class="add-sub-form">
                        <h4>Ajouter une sous-catégorie</h4>
                        <form method="POST" action="{{ route('admin.categories.subcategories.store', $categorie) }}"
                            class="inline-form">
                            @csrf
                            <div class="form-row">
                                <div class="flex-grow">
                                    <input type="text" name="nom" class="form-input" placeholder="Nom de la sous-cat." required>
                                </div>
                                <div class="flex-grow">
                                    <input type="text" name="description" class="form-input"
                                        placeholder="Description (optionnel)">
                                </div>
                                <button type="submit" class="btn-add-sub"><i class="fas fa-plus"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des sous-catégories -->
                    <div class="subcategory-list">
                        @forelse($categorie->sousCategories as $sousCategorie)
                            <div class="subcategory-item">
                                <div class="sub-info">
                                    <span class="sub-name">{{ $sousCategorie->nom }}</span>
                                    <span
                                        class="sub-desc">{{ $sousCategorie->description ? Str::limit($sousCategorie->description, 40) : '' }}</span>
                                </div>
                                <div class="sub-actions">
                                    <button class="icon-btn edit-btn" data-modal="editSubModal{{ $sousCategorie->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('admin.categories.subcategories.destroy', [$categorie, $sousCategorie]) }}"
                                        class="d-inline" onsubmit="return confirm('Supprimer cette sous-catégorie ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-btn delete-btn" type="submit"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-sub">Aucune sous-catégorie pour le moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modales d'édition de sous-catégorie --}}
    @foreach($categories as $categorie)
        @foreach($categorie->sousCategories as $sousCategorie)
            <div class="custom-modal" id="editSubModal{{ $sousCategorie->id }}">
                <div class="modal-overlay" data-modal-close></div>
                <div class="modal-container">
                    <div class="modal-header">
                        <h3><i class="fas fa-pen"></i> Modifier la sous-catégorie</h3>
                        <button class="modal-close" data-modal-close>&times;</button>
                    </div>
                    <form method="POST" action="{{ route('admin.categories.subcategories.update', $sousCategorie) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-floating-group">
                                <input type="text" name="nom" id="edit_sub_nom_{{ $sousCategorie->id }}" class="form-input"
                                    value="{{ $sousCategorie->nom }}" placeholder=" " required>
                                <label for="edit_sub_nom_{{ $sousCategorie->id }}">Nom *</label>
                            </div>
                            <div class="form-floating-group">
                                <textarea name="description" id="edit_sub_desc_{{ $sousCategorie->id }}"
                                    class="form-input form-textarea" placeholder=" "
                                    rows="3">{{ $sousCategorie->description }}</textarea>
                                <label for="edit_sub_desc_{{ $sousCategorie->id }}">Description</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-modal-close>Annuler</button>
                            <button type="submit" class="btn-save">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach

@endsection

@push('styles')
    {{-- Fix Font Awesome icons by loading the CSS directly --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #7f9cf5;
            --accent: #764ba2;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
            --gradient: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            --bg-page: #f4f7fc;
            --bg-card: #ffffff;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --border: #e2e8f0;
            --radius-sm: 12px;
            --radius-md: 20px;
            --radius-lg: 28px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 10px 25px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.12);
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-categories-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        /* Header décoratif */
        .page-header-decor {
            background: var(--gradient);
            border-radius: var(--radius-lg);
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .icon-circle {
            background: rgba(255, 255, 255, 0.2);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .badge-counter {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            padding: 0.35rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 1rem;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0.5rem 0 0;
        }

        .header-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -120px;
            right: -80px;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -70px;
            left: -50px;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            top: 40%;
            right: 15%;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Layout */
        .categories-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Panneau création */
        .create-panel {
            position: sticky;
            top: 1.5rem;
        }

        .panel-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: box-shadow var(--transition);
        }

        .panel-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .panel-header {
            background: var(--gradient);
            padding: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .panel-icon {
            font-size: 1.8rem;
        }

        .create-form {
            padding: 1.5rem;
        }

        .form-floating-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            background: #f9fafb;
            transition: all var(--transition);
            outline: none;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-floating-group label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            padding: 0 0.4rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            pointer-events: none;
            transition: all 0.2s ease;
        }

        .form-floating-group .form-textarea~label {
            top: 1.2rem;
            transform: none;
        }

        .form-input:focus~label,
        .form-input:not(:placeholder-shown)~label {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 600;
        }

        .input-highlight {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient);
            transition: all 0.3s;
        }

        .form-input:focus~.input-highlight {
            width: 100%;
            left: 0;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            display: block;
        }

        .btn-create {
            width: 100%;
            padding: 0.9rem;
            background: var(--gradient);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all var(--transition);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        /* Grille des catégories */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.8rem;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
        }

        .empty-state p {
            color: var(--text-muted);
        }

        /* Carte catégorie */
        .category-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition);
            box-shadow: var(--shadow-sm);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient);
        }

        .card-body {
            padding: 1.8rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-icon-wrapper {
            background: rgba(102, 126, 234, 0.1);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex: 1;
        }

        .card-meta {
            margin-bottom: 1.2rem;
        }

        .meta-badge {
            background: #edf2f7;
            color: var(--text-dark);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
        }

        .action-btn {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: #f7fafc;
            color: var(--text-dark);
        }

        .action-btn:hover {
            background: #edf2f7;
            transform: translateY(-1px);
        }

        .btn-subcats {
            color: #2b6cb0;
        }

        .btn-edit {
            color: #2d3748;
        }

        .btn-delete {
            color: #e53e3e;
            background: #fff5f5;
        }

        .btn-delete:hover {
            background: #fed7d7;
        }

        .action-form {
            display: inline;
        }

        /* Modales */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .custom-modal.active {
            display: flex;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.2s;
        }

        .modal-container {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .modal-lg {
            max-width: 700px;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            background: var(--gradient);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: var(--radius-lg);
            border-top-right-radius: var(--radius-lg);
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.8rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1rem 2rem;
            background: #f7fafc;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
        }

        .btn-cancel {
            padding: 0.6rem 1.5rem;
            border: 1px solid var(--border);
            background: white;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #edf2f7;
        }

        .btn-save {
            padding: 0.6rem 1.5rem;
            background: var(--gradient);
            border: none;
            color: white;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Sous-catégories dans modale */
        .add-sub-form {
            background: #f7fafc;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .add-sub-form h4 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .inline-form .form-row {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }

        .flex-grow {
            flex: 1;
        }

        .btn-add-sub {
            background: var(--gradient);
            border: none;
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add-sub:hover {
            transform: scale(1.1);
        }

        .subcategory-list {
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        .subcategory-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #edf2f7;
        }

        .sub-info {
            display: flex;
            flex-direction: column;
        }

        .sub-name {
            font-weight: 600;
            color: var(--text-dark);
        }

        .sub-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .sub-actions {
            display: flex;
            gap: 0.3rem;
        }

        .icon-btn {
            background: transparent;
            border: none;
            font-size: 1rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .edit-btn {
            color: #4a5568;
        }

        .edit-btn:hover {
            background: #edf2f7;
        }

        .delete-btn {
            color: #e53e3e;
        }

        .delete-btn:hover {
            background: #fff5f5;
        }

        .empty-sub {
            text-align: center;
            padding: 1rem;
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .categories-layout {
                grid-template-columns: 1fr;
            }

            .create-panel {
                position: static;
            }

            .page-header-decor {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }

            .card-actions {
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Gestion des modales
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeModal(modalElement) {
                modalElement.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Événements d'ouverture
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const modalId = this.getAttribute('data-modal');
                    openModal(modalId);
                });
            });

            // Fermeture sur clic overlay ou bouton close
            document.querySelectorAll('.custom-modal').forEach(modal => {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal || e.target.hasAttribute('data-modal-close')) {
                        closeModal(modal);
                    }
                });
            });

            // Échap pour fermer
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.custom-modal.active').forEach(modal => {
                        closeModal(modal);
                    });
                }
            });
        })();
    </script>
@endpush