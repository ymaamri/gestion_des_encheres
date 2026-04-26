{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/annonces/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier l\'Annonce')
@section('page-title', 'Modifier l\'Annonce')
@section('breadcrumb', 'Modifier Annonce')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Modifier l'annonce : {{ $annonce->titre }}</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('annonces.update', $annonce) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Information -->
                        <h5 class="mb-3 text-primary">Informations du produit</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom du Produit *</label>
                                <input type="text" name="produit_nom" class="form-control"
                                    value="{{ old('produit_nom', $annonce->produit->nom) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Marque</label>
                                <input type="text" name="produit_marque" class="form-control"
                                    value="{{ old('produit_marque', $annonce->produit->marque) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Modèle</label>
                                <input type="text" name="produit_modele" class="form-control"
                                    value="{{ old('produit_modele', $annonce->produit->modele) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">État *</label>
                                <select name="produit_etat" class="form-control" required>
                                    <option value="NEUF" {{ old('produit_etat', $annonce->produit->etat) == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                                    <option value="TRES_BON_ETAT" {{ old('produit_etat', $annonce->produit->etat) == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très Bon État
                                    </option>
                                    <option value="BON_ETAT" {{ old('produit_etat', $annonce->produit->etat) == 'BON_ETAT' ? 'selected' : '' }}>Bon État</option>
                                    <option value="ACCEPTABLE" {{ old('produit_etat', $annonce->produit->etat) == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description du Produit</label>
                            <textarea name="produit_description" class="form-control"
                                rows="3">{{ old('produit_description', $annonce->produit->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Photos actuelles</label>
                            <div class="row">
                                @foreach($annonce->produit->photos ?? [] as $photo)
                                    @php $loopIndex = $loop->index; @endphp
                                    <div class="col-md-3 mb-2 position-relative">
                                        <img src="{{ Storage::url($photo) }}" class="img-fluid rounded"
                                            style="height: 80px; object-fit: cover;">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="delete_photos[]"
                                                value="{{ $photo }}" id="del_{{ $loopIndex }}">
                                            <label class="form-check-label text-danger" for="del_{{ $loopIndex }}">
                                                Supprimer
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Cochez les photos à supprimer</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ajouter de nouvelles photos</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Vous pouvez ajouter plusieurs images (max 5, 2MB chacune)</small>
                        </div>

                        <hr class="my-4">

                        <!-- Auction Information -->
                        <h5 class="mb-3 text-primary">Informations de l'enchère</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Titre de l'Annonce *</label>
                                <input type="text" name="titre" class="form-control"
                                    value="{{ old('titre', $annonce->titre) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Catégorie (non modifiable)</label>
                                <input type="text" class="form-control"
                                    value="{{ $annonce->produit->categorie->nom ?? 'Non catégorisé' }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description de l'Annonce</label>
                            <textarea name="description" class="form-control"
                                rows="3">{{ old('description', $annonce->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Prix de Départ (MAD) *</label>
                                <input type="number" name="prix_depart" class="form-control" step="0.01"
                                    value="{{ old('prix_depart', $annonce->prix_depart) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date et Heure de Fin *</label>
                                <input type="datetime-local" name="date_fin" class="form-control"
                                    value="{{ old('date_fin', \Carbon\Carbon::parse($annonce->date_fin)->format('Y-m-d\TH:i')) }}"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Pas d'enchère (MAD)</label>
                                <input type="number" name="montant_mise" class="form-control" step="1"
                                    value="{{ old('montant_mise', $annonce->montant_mise) }}" min="1">
                                <small class="text-muted">Montant minimum d'augmentation</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn bg-gradient-dark">
                                    <i class="material-symbols-rounded me-1">update</i> Mettre à jour l'Annonce
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
        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }

        .text-primary {
            color: #e91e63 !important;
        }
    </style>
@endpush