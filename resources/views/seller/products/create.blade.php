@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="mb-0">➕ Ajouter un produit</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label>Nom du produit *</label>
                            <input type="text" name="nom" class="form-control" required value="{{ old('nom') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Marque</label>
                                <input type="text" name="marque" class="form-control" value="{{ old('marque') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Modèle</label>
                                <input type="text" name="modele" class="form-control" value="{{ old('modele') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Catégorie *</label>
                                <select name="categorie_id" id="categorie_id" class="form-control" required>
                                    <option value="">Choisir</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Sous‑catégorie</label>
                                <select name="sous_categorie_id" id="sous_categorie_id" class="form-control">
                                    <option value="">Aucune</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>État *</label>
                            <select name="etat" class="form-control" required>
                                <option value="NEUF">Neuf</option>
                                <option value="TRES_BON_ETAT">Très bon état</option>
                                <option value="BON_ETAT">Bon état</option>
                                <option value="ACCEPTABLE">Acceptable</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Photos (plusieurs possibles)</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">JPG, PNG, max 2MB chacun</small>
                        </div>

                        <button type="submit" class="btn bg-gradient-primary">💾 Enregistrer le produit</button>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        </script>
    @endpush
@endsection