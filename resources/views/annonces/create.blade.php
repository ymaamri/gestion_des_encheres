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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Titre de l'Annonce</label>
                                    <input type="text" name="titre" class="form-control" value="{{ old('titre') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Nom du Produit</label>
                                    <input type="text" name="produit_nom" class="form-control"
                                        value="{{ old('produit_nom') }}" required>
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
                            <div class="col-md-12">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Description du Produit</label>
                                    <textarea name="produit_description" class="form-control"
                                        rows="3">{{ old('produit_description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Marque</label>
                                    <input type="text" name="produit_marque" class="form-control"
                                        value="{{ old('produit_marque') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Modèle</label>
                                    <input type="text" name="produit_modele" class="form-control"
                                        value="{{ old('produit_modele') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <select name="produit_etat" class="form-control" required>
                                        <option value="">Sélectionner l'État</option>
                                        <option value="NEUF" {{ old('produit_etat') == 'NEUF' ? 'selected' : '' }}>Neuf
                                        </option>
                                        <option value="TRES_BON_ETAT" {{ old('produit_etat') == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État</option>
                                        <option value="BON_ETAT" {{ old('produit_etat') == 'BON_ETAT' ? 'selected' : '' }}>
                                            Bon État</option>
                                        <option value="ACCEPTABLE" {{ old('produit_etat') == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Prix de Départ (MAD)</label>
                                    <input type="number" name="prix_depart" class="form-control" step="0.01"
                                        value="{{ old('prix_depart') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Date et Heure de Fin</label>
                                    <input type="datetime-local" name="date_fin" class="form-control"
                                        value="{{ old('date_fin') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline mb-3">
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
                                <div class="mb-3">
                                    <label class="form-label">Photos du Produit</label>
                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">Vous pouvez sélectionner plusieurs images (max 5)</small>
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

@push('styles')
    <style>
        .input-group-outline {
            margin-top: 0 !important;
        }
    </style>
@endpush