@extends('layouts.guest')

@section('title', 'Confirmation du mot de passe')

@section('content')
    <div class="text-center mb-4">
        <i class="material-symbols-rounded" style="font-size: 48px; color: #667eea;">verified</i>
        <h4 class="mt-2">Zone sécurisée</h4>
        <p class="text-muted">Veuillez confirmer votre mot de passe avant de continuer.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label fw-semibold">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="material-symbols-rounded">lock</i></span>
                <input type="password" name="password" class="form-control" required autocomplete="current-password"
                    placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn-gradient">Confirmer</button>
    </form>
@endsection