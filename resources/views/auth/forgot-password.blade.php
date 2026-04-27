{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')

@section('title', 'Mot de passe oublié')

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
            max-width: 450px;
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
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="material-symbols-rounded">lock_reset</i>
                <h4 class="mt-2 fw-bold">BidMaster</h4>
            </div>
            <div class="auth-body">
                <h5 class="text-center mb-4 fw-bold">Mot de passe oublié</h5>
                <p class="text-muted text-center mb-4">Entrez votre email, nous vous enverrons un lien de réinitialisation.
                </p>

                @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gradient mb-3">
                        <i class="material-symbols-rounded align-middle me-1">send</i> Envoyer le lien
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="auth-link">
                            <i class="material-symbols-rounded align-middle me-1">arrow_back</i> Retour à la connexion
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection