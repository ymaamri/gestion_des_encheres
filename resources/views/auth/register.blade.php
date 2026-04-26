{{-- /resources/views/auth/register.blade.php --}}
@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Nom</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="material-symbols-rounded">badge</i>
                    </span>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus
                        autocomplete="name" placeholder="Dupont">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Prénom</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="material-symbols-rounded">person</i>
                    </span>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}" placeholder="Jean">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="material-symbols-rounded">email</i>
                </span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                    autocomplete="username" placeholder="jean.dupont@email.com">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="material-symbols-rounded">lock</i>
                </span>
                <input type="password" name="password" class="form-control" required autocomplete="new-password"
                    placeholder="••••••••">
            </div>
            <small class="text-muted">Minimum 8 caractères</small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Confirmer le mot de passe</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="material-symbols-rounded">lock</i>
                </span>
                <input type="password" name="password_confirmation" class="form-control" required
                    autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="terms" required>
            <label class="form-check-label" for="terms">
                J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
            </label>
        </div>

        <button type="submit" class="btn-gradient">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">app_registration</i> Créer mon compte
        </button>

        <div class="text-center mt-4">
            <span class="text-muted">Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="auth-link ms-2">Se connecter</a>
        </div>
    </form>
@endsection