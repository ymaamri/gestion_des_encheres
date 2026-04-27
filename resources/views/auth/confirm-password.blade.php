{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auth/confirm-password.blade.php --}}
@extends('layouts.app')

@section('title', 'Confirmation du mot de passe')

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
                <i class="material-symbols-rounded">verified</i>
                <h4 class="mt-2 fw-bold">Zone sécurisée</h4>
            </div>
            <div class="auth-body">
                <h5 class="text-center mb-4 fw-bold">Confirmation du mot de passe</h5>
                <p class="text-muted text-center mb-4">Veuillez confirmer votre mot de passe avant de continuer.</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mot de passe</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gradient">
                        <i class="material-symbols-rounded align-middle me-1">check_circle</i> Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection