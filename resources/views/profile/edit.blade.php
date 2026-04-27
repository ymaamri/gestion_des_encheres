{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('breadcrumb', 'Profil')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-5">
            <div class="col-12">
                <div
                    class="card card-custom bg-gradient-theme text-white shadow-lg overflow-hidden position-relative rounded-4">
                    <div class="position-absolute"
                        style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>
                    <div class="position-absolute"
                        style="bottom: -50px; left: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>

                    <div class="card-body p-5 position-relative z-index-1">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-3 rounded-circle d-flex align-items-center justify-content-center shadow"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-user-circle text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <div class="ms-4">
                                <h2 class="text-white mb-1 fw-bold">{{ __('Mon Profil') }}</h2>
                                <p class="mb-0 text-white opacity-8">Gérez vos informations personnelles et paramètres de
                                    sécurité</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100 border-0 shadow-sm rounded-4">
                    <div class="card-header pb-0 p-4 bg-transparent border-0">
                        <h5 class="mb-0 text-dark font-weight-bolder">
                            <i class="fas fa-id-card text-primary me-2"></i> Informations du Profil
                        </h5>
                        <p class="text-sm mb-0 mt-2 text-secondary">Mettez à jour les informations de votre compte et votre
                            adresse e-mail.</p>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-6">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="name" class="form-label font-weight-bold">{{ __('Nom Complet') }}</label>
                                <div class="input-group input-group-outline {{ $errors->has('name') ? 'is-invalid' : '' }}">
                                    <input id="name" name="name" type="text" class="form-control rounded-3"
                                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                                        placeholder="Votre nom complet">
                                </div>
                                @error('name')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label font-weight-bold">{{ __('Email') }}</label>
                                <div
                                    class="input-group input-group-outline {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                    <input id="email" name="email" type="email" class="form-control rounded-3"
                                        value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        placeholder="votre@email.com">
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="mt-3 alert alert-warning text-sm text-white rounded-3" role="alert">
                                        <p class="mb-2">
                                            {{ __('Votre adresse email n\'est pas vérifiée.') }}
                                        </p>
                                        <button form="send-verification" class="btn btn-sm btn-white mb-0 rounded-3">
                                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                                        </button>
                                    </div>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-success text-sm font-weight-bold">
                                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                                        </p>
                                    @endif
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-4">
                                <button type="submit"
                                    class="btn btn-gradient rounded-3 mb-0">{{ __('Sauvegarder') }}</button>

                                @if (session('status') === 'profile-updated')
                                    <span x-data="{ show: true }" x-show="show" x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-success font-weight-bold">
                                        <i class="fas fa-check-circle me-1"></i> {{ __('Sauvegardé.') }}
                                    </span>
                                @endif
                            </div>
                        </form>
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100 border-0 shadow-sm rounded-4">
                    <div class="card-header pb-0 p-4 bg-transparent border-0">
                        <h5 class="mb-0 text-dark font-weight-bolder">
                            <i class="fas fa-lock text-primary me-2"></i> Mot de Passe
                        </h5>
                        <p class="text-sm mb-0 mt-2 text-secondary">Assurez-vous que votre compte utilise un mot de passe
                            long et aléatoire.</p>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-6">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password"
                                    class="form-label font-weight-bold">{{ __('Mot de passe actuel') }}</label>
                                <div
                                    class="input-group input-group-outline {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}">
                                    <input id="current_password" name="current_password" type="password"
                                        class="form-control rounded-3" autocomplete="current-password"
                                        placeholder="Saisissez votre mot de passe actuel">
                                </div>
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                    class="form-label font-weight-bold">{{ __('Nouveau mot de passe') }}</label>
                                <div
                                    class="input-group input-group-outline {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}">
                                    <input id="password" name="password" type="password" class="form-control rounded-3"
                                        autocomplete="new-password" placeholder="Nouveau mot de passe">
                                </div>
                                @error('password', 'updatePassword')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation"
                                    class="form-label font-weight-bold">{{ __('Confirmer le mot de passe') }}</label>
                                <div
                                    class="input-group input-group-outline {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }}">
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        class="form-control rounded-3" autocomplete="new-password"
                                        placeholder="Retapez le nouveau mot de passe">
                                </div>
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-4">
                                <button type="submit"
                                    class="btn btn-gradient rounded-3 mb-0">{{ __('Mettre à jour') }}</button>

                                @if (session('status') === 'password-updated')
                                    <span x-data="{ show: true }" x-show="show" x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-success font-weight-bold">
                                        <i class="fas fa-check-circle me-1"></i> {{ __('Sauvegardé.') }}
                                    </span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom border-0 shadow-sm rounded-4"
                    style="border-left: 4px solid #f5365c !important;">
                    <div class="card-header pb-0 p-4 bg-transparent border-bottom-0">
                        <h5 class="mb-0 text-danger font-weight-bolder">
                            <i class="fas fa-exclamation-triangle me-2"></i> Zone Dangereuse
                        </h5>
                        <p class="text-sm mb-0 mt-2 text-secondary">Une fois votre compte supprimé, toutes ses ressources et
                            données seront effacées définitivement.</p>
                    </div>
                    <div class="card-body p-4 pt-0 mt-3">
                        <button type="button" class="btn btn-outline-danger rounded-3" data-bs-toggle="modal"
                            data-bs-target="#confirmUserDeletionModal">
                            {{ __('Supprimer le Compte') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Suppression de compte -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark" id="confirmUserDeletionModalLabel">
                            {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-3">
                        <p class="text-sm text-secondary mb-4">
                            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront effacées définitivement. Veuillez saisir votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}
                        </p>

                        <div class="mb-3">
                            <label for="password_delete" class="form-label visually-hidden">{{ __('Mot de passe') }}</label>
                            <div
                                class="input-group input-group-outline {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}">
                                <input id="password_delete" name="password" type="password" class="form-control rounded-3"
                                    placeholder="{{ __('Mot de passe') }}">
                            </div>
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light mb-0 rounded-3" data-bs-dismiss="modal">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn btn-danger mb-0 rounded-3">
                            {{ __('Supprimer le Compte') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .btn-gradient:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
                color: white;
            }

            .bg-gradient-theme {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            }

            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            }

            .alert-warning {
                background-color: #fff3cd;
                border-color: #ffeaa7;
                color: #856404;
            }

            .alert-warning .btn-white {
                background-color: white;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
        </style>
    @endpush

    @push('scripts')
        @if($errors->userDeletion->isNotEmpty())
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                    myModal.show();
                });
            </script>
        @endif
    @endpush
@endsection