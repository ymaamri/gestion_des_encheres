@extends('layouts.app')

@section('title', 'Créer une Catégorie')
@section('page-title', 'Créer une Nouvelle Catégorie')
@section('breadcrumb', 'Créer Catégorie')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Nouvelle Catégorie</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Nom de la catégorie *</label>
                                <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
                            </div>
                            @error('nom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Icône (nom Material Symbol)</label>
                                <input type="text" name="icone" class="form-control" value="{{ old('icone') }}" placeholder="ex: category, devices, home">
                            </div>
                            <small class="text-muted">Utilisez un nom d'icône Material Symbols valide</small>
                            @error('icone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn bg-gradient-dark">
                                <i class="material-symbols-rounded me-1">save</i> Créer la Catégorie
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection