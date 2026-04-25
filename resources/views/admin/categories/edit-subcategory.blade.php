@extends('layouts.app')

@section('title', 'Modifier la Sous-catégorie')
@section('page-title', 'Modifier : ' . $subcategory->nom)
@section('breadcrumb', 'Modifier Sous-catégorie')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Modifier la Sous-catégorie</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.subcategories.update', $subcategory) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Nom de la sous-catégorie *</label>
                                <input type="text" name="nom" class="form-control" value="{{ old('nom', $subcategory->nom) }}" required>
                            </div>
                            @error('nom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $subcategory->description) }}</textarea>
                            </div>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn bg-gradient-dark">
                                <i class="material-symbols-rounded me-1">update</i> Mettre à jour
                            </button>
                            <a href="{{ route('admin.categories.subcategories', $subcategory->categorie_id) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection