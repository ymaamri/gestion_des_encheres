@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
    <div class="text-center mb-4">
        <i class="material-symbols-rounded" style="font-size: 48px; color: #667eea;">lock_reset</i>
        <h4 class="mt-2">Mot de passe oublié ?</h4>
        <p class="text-muted">Entrez votre email, nous vous enverrons un lien de réinitialisation.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="material-symbols-rounded">email</i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus
                    placeholder="votre@email.com">
            </div>
        </div>

        <button type="submit" class="btn-gradient">Envoyer le lien</button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="auth-link">
                <i class="material-symbols-rounded me-1" style="font-size: 16px;">arrow_back</i> Retour à la connexion
            </a>
        </div>
    </form>
@endsection