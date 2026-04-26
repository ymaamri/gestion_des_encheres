@extends('layouts.guest')

@section('title', 'Vérification email')

@section('content')
    <div class="text-center mb-4">
        <i class="material-symbols-rounded" style="font-size: 48px; color: #667eea;">mark_email_read</i>
        <h4 class="mt-2">Vérifiez votre email</h4>
        <p class="text-muted">Un lien de vérification a été envoyé à votre adresse email.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            <i class="material-symbols-rounded me-1">check_circle</i> Un nouveau lien a été envoyé.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-gradient mb-3">Renvoyer l'email de vérification</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100" style="border-radius: 50px;">Se déconnecter</button>
    </form>
@endsection