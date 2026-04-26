@extends('layouts.app')

@section('title', 'Modifier le produit')
@section('page-title', 'Modifier le produit')
@section('breadcrumb', 'Produits')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-dark text-white">
                <h5 class="mb-0">✏️ Modifier le produit : {{ $product->nom }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Nom du produit *</label>
                        <input type="text" name="nom" class="form-control" required value="{{ old('nom', $product->nom) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Marque</label>
                            <input type="text" name="marque" class="form-control" value="{{ old('marque', $product->marque) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Modèle</label>
                            <input type="text" name="modele" class="form-control" value="{{ old('modele', $product->modele) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Catégorie *</label>
                            <select name="categorie_id" id="categorie_id" class="form-control" required>
                                <option value="">Choisir</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                        {{ old('categorie_id', $product->sousCategorie->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sous‑catégorie</label>
                            <select name="sous_categorie_id" id="sous_categorie_id" class="form-control">
                                <option value="">Aucune</option>
                                @if($product->sousCategorie)
                                    @foreach($product->sousCategorie->categorie->sousCategories ?? [] as $sub)
                                        <option value="{{ $sub->id }}" {{ $product->sous_categorie_id == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->nom }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>État *</label>
                        <select name="etat" class="form-control" required>
                            <option value="NEUF" {{ old('etat', $product->etat) == 'NEUF' ? 'selected' : '' }}>Neuf</option>
                            <option value="TRES_BON_ETAT" {{ old('etat', $product->etat) == 'TRES_BON_ETAT' ? 'selected' : '' }}>Très bon état</option>
                            <option value="BON_ETAT" {{ old('etat', $product->etat) == 'BON_ETAT' ? 'selected' : '' }}>Bon état</option>
                            <option value="ACCEPTABLE" {{ old('etat', $product->etat) == 'ACCEPTABLE' ? 'selected' : '' }}>Acceptable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Photos actuelles</label>
                        <div class="row">
                            @foreach($product->photos ?? [] as $photo)
                                @php $loopIndex = $loop->index; @endphp
                                <div class="col-md-3 mb-2 position-relative">
                                    <img src="{{ Storage::url($photo) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="delete_photos[]" value="{{ $photo }}" id="del_{{ $loopIndex }}">
                                        <label class="form-check-label text-danger" for="del_{{ $loopIndex }}">
                                            Supprimer
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Ajouter de nouvelles photos</label>
                        <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">JPG, PNG, max 2MB chacun</small>
                    </div>

                    <button type="submit" class="btn bg-gradient-primary">💾 Mettre à jour</button>
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
                        let selected = (sub.id == {{ $product->sous_categorie_id ?? 0 }}) ? 'selected' : '';
                        subSelect.innerHTML += `<option value="${sub.id}" ${selected}>${sub.nom}</option>`;
                    });
                });
        } else {
            subSelect.innerHTML = '<option value="">Aucune</option>';
        }
    });
</script>
@endpush
@endsection