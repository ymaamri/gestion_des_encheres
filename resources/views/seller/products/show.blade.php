@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gradient-dark text-white">
                    <h5>{{ $product->nom }}</h5>
                </div>
                <div class="card-body">
                    <img src="{{ \App\Helpers\ImageHelper::getProductImage($product) }}" class="img-fluid rounded mb-3">
                    <p><strong>Marque :</strong> {{ $product->marque }}</p>
                    <p><strong>Modèle :</strong> {{ $product->modele }}</p>
                    <p><strong>État :</strong> {{ $product->etat }}</p>
                    <p><strong>Description :</strong> {{ $product->description }}</p>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">← Retour</a>
                    <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-warning">Modifier</a>
                </div>
            </div>
        </div>
    </div>
@endsection