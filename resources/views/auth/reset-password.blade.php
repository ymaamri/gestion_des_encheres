@extends('layouts.guest')

@section('title', 'Réinitialisation du mot de passe')

@section('content')
    <div class="text-center mb-4">
        <i class="material-symbols-rounded" style="font-size: 48px; color: #667eea;">password</i>
        <h4 class="mt-2">Nouveau mot de passe</h4>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="material-symbols-rounded">email</i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required
                    autofocus readonly>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nouveau mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="material-symbols-rounded">lock</i></span>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Confirmer le mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="material-symbols-rounded">lock</i></span>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn-gradient">Réinitialiser</button>
    </form>
@endsection