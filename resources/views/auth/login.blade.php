{{-- /resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="material-symbols-rounded">email</i>
                </span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus
                    autocomplete="username" placeholder="exemple@email.com">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="material-symbols-rounded">lock</i>
                </span>
                <input type="password" name="password" class="form-control" required autocomplete="current-password"
                    placeholder="••••••••">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Se souvenir de moi</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit" class="btn-gradient">
            <i class="material-symbols-rounded me-2" style="font-size: 20px;">login</i> Se connecter
        </button>

        <div class="text-center mt-4">
            <span class="text-muted">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="auth-link ms-2">Créer un compte</a>
        </div>
    </form>
@endsection