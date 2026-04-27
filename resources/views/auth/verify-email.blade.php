{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('title', 'Vérification email')

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

        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            font-weight: 600;
            padding: 0.7rem;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
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
                <i class="material-symbols-rounded">mark_email_read</i>
                <h4 class="mt-2 fw-bold">Vérification email</h4>
            </div>
            <div class="auth-body">
                <div class="text-center mb-4">
                    <i class="material-symbols-rounded" style="font-size: 48px; color: #667eea;">mail</i>
                    <h5 class="mt-3 fw-bold">Vérifiez votre email</h5>
                    <p class="text-muted">Un lien de vérification a été envoyé à votre adresse email.</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-3">
                        <i class="material-symbols-rounded align-middle me-1">check_circle</i> Un nouveau lien a été envoyé.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                    @csrf
                    <button type="submit" class="btn-gradient">
                        <i class="material-symbols-rounded align-middle me-1">send</i> Renvoyer l’email de vérification
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline">
                        <i class="material-symbols-rounded align-middle me-1">logout</i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection