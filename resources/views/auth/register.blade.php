{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Inscription')

@push('styles')
    <style>
        .auth-container {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: -1.5rem;
            padding: 1.5rem;
        }

        .auth-card {
            max-width: 500px;
            width: 100%;
            border-radius: 20px;
            background: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: none;
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            text-align: center;
        }

        .auth-header i {
            font-size: 3rem;
            color: white;
        }

        .auth-header h4 {
            color: white;
            margin-bottom: 0;
        }

        .auth-body {
            padding: 2rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        input {
            padding: 5px !important;
        }
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="material-symbols-rounded">person_add</i>
                <h4 class="mt-2 fw-bold">BidMaster</h4>
            </div>
            <div class="auth-body">
                <h5 class="text-center mb-4 fw-bold">Créer un compte</h5>
                <p class="text-muted text-center mb-4">Rejoignez la meilleure plateforme d'enchères</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nom</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                value="{{ old('prenom') }}">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mot de passe</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-gradient mb-3">
                        <i class="material-symbols-rounded align-middle me-1">app_registration</i> Créer mon compte
                    </button>

                    <div class="text-center">
                        <span class="text-muted">Déjà inscrit ?</span>
                        <a href="{{ route('login') }}" class="auth-link ms-2">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection