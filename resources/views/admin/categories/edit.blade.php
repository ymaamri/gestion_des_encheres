{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier la Catégorie')
@section('page-title', 'Modifier la Catégorie')
@section('breadcrumb', 'Modifier Catégorie')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4 border-0 shadow-sm rounded-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-theme shadow-lg border-radius-lg pt-4 pb-3 rounded-4">
                        <h6 class="text-white text-capitalize ps-3 mb-0 fw-bold">
                            <i class="material-symbols-rounded me-1 align-middle">edit</i> Modifier : {{ $category->nom }}
                        </h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom de la catégorie *</label>
                                <input type="text" name="nom"
                                    class="form-control @error('nom') is-invalid @enderror rounded-3"
                                    value="{{ old('nom', $category->nom) }}" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Icône (nom Material Symbol)</label>
                                <input type="text" name="icone" class="form-control rounded-3"
                                    value="{{ old('icone', $category->icone) }}" placeholder="ex: category, devices, home">
                                <small class="text-muted">Utilisez un nom d'icône Material Symbols valide</small>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror rounded-3"
                                    rows="4">{{ old('description', $category->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-gradient rounded-3 px-4">
                                    <i class="material-symbols-rounded me-1">update</i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.categories.index') }}"
                                    class="btn btn-outline-secondary rounded-3 px-4">Annuler</a>
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
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .bg-gradient-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
    </style>
@endpush