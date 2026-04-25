{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('breadcrumb', 'Profil')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Photo de profil</h6>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="avatar avatar-xl bg-gradient-{{ Auth::user()->role == 'admin' ? 'danger' : (Auth::user()->role == 'vendeur' ? 'warning' : 'info') }} border-radius-lg d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px;">
                    <i class="material-symbols-rounded text-white" style="font-size: 60px;">
                        @switch(Auth::user()->role)
                            @case('admin') admin_panel_settings @break
                            @case('vendeur') store @break
                            @default account_circle
                        @endswitch
                    </i>
                </div>
                <h5 class="mt-3 mb-0">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h5>
                <p class="text-sm text-muted mb-0">
                    @switch(Auth::user()->role)
                        @case('admin')
                            <span class="badge badge-sm bg-gradient-danger">Administrateur</span>
                            @break
                        @case('vendeur')
                            <span class="badge badge-sm bg-gradient-warning">Vendeur</span>
                            @break
                        @default
                            <span class="badge badge-sm bg-gradient-info">Client</span>
                    @endswitch
                </p>
                <p class="text-xs text-muted mt-2">Membre depuis {{ Auth::user()->created_at->format('F Y') }}</p>

                @if(Auth::user()->client)
                <div class="mt-3 pt-3 border-top">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mb-0 text-success">{{ number_format(Auth::user()->client->solde ?? 0, 2) }}</h6>
                            <small class="text-muted">Solde (MAD)</small>
                        </div>
                        <div class="col-6">
                            @php
                                $statut = Auth::user()->client->statut ?? 'ACTIF';
                            @endphp
                            <span class="badge badge-sm bg-gradient-{{ $statut == 'ACTIF' ? 'success' : 'danger' }}">
                                {{ $statut }}
                            </span>
                            <br>
                            <small class="text-muted">Statut</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="card">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Informations complémentaires</h6>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user()->client)
                <div class="mb-3">
                    <small class="text-muted d-block">Téléphone</small>
                    <p class="mb-0">{{ Auth::user()->client->telephone ?? 'Non renseigné' }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Adresse de livraison</small>
                    <p class="mb-0">{{ Auth::user()->client->adresse_livraison ?? 'Non renseignée' }}</p>
                </div>
                @endif

                @if(Auth::user()->role == 'vendeur' && Auth::user()->client && Auth::user()->client->vendeur)
                <div class="border-top pt-3">
                    <h6 class="text-primary">Informations vendeur</h6>
                    <div class="mb-2">
                        <small class="text-muted d-block">SIRET</small>
                        <p class="mb-0">{{ Auth::user()->client->vendeur->siret ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Note moyenne</small>
                        <div class="d-flex align-items-center">
                            <span class="me-2">{{ number_format(Auth::user()->client->vendeur->note_moyenne ?? 0, 1) }}</span>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="material-symbols-rounded" style="font-size: 16px; color: {{ $i <= round(Auth::user()->client->vendeur->note_moyenne ?? 0) ? '#ffc107' : '#dee2e6' }}">star</i>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Nombre de ventes</small>
                        <p class="mb-0">{{ Auth::user()->client->vendeur->nombre_ventes ?? 0 }}</p>
                    </div>
                    <div>
                        <small class="text-muted d-block">Annonces</small>
                        <p class="mb-0">{{ Auth::user()->client->vendeur->annonces()->count() }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Update Profile Information -->
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Informations du compte</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required autofocus>
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->get('nom')" />
                        </div>

                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" id="prenom" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->get('prenom')" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="alert alert-warning mt-2">
                                    <p class="mb-0">
                                        Votre adresse email n'est pas vérifiée.
                                        <button form="send-verification" class="btn btn-link text-primary p-0">Cliquez ici pour renvoyer le lien de vérification</button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-success">Un nouveau lien de vérification a été envoyé à votre adresse email.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->client)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone', Auth::user()->client->telephone ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Adresse de livraison</label>
                                <input type="text" id="adresse_livraison" name="adresse_livraison" class="form-control" value="{{ old('adresse_livraison', Auth::user()->client->adresse_livraison ?? '') }}">
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <button type="submit" class="btn bg-gradient-dark">
                            <i class="material-symbols-rounded me-1">save</i> Enregistrer
                        </button>

                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success m-0 py-2 px-3">
                                <i class="material-symbols-rounded me-1">check_circle</i> Profil mis à jour !
                            </div>
                        @endif
                    </div>
                </form>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Changer le mot de passe</h6>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password">
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->updatePassword->get('current_password')" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->updatePassword->get('password')" />
                        </div>

                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                            </div>
                            <x-input-error class="mt-2 text-danger small" :messages="$errors->updatePassword->get('password_confirmation')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="btn bg-gradient-dark">
                            <i class="material-symbols-rounded me-1">lock_reset</i> Changer le mot de passe
                        </button>

                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success m-0 py-2 px-3">
                                <i class="material-symbols-rounded me-1">check_circle</i> Mot de passe mis à jour !
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-danger shadow-danger border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Zone dangereuse</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-danger mb-4">
                    <i class="material-symbols-rounded me-2">warning</i>
                    Une fois votre compte supprimé, toutes ses données seront définitivement supprimées.
                </div>

                <!-- Delete Button -->
                <button type="button" class="btn bg-gradient-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="material-symbols-rounded me-1">delete_forever</i> Supprimer mon compte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title">Supprimer mon compte</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
                    <p class="text-muted small">Cette action est irréversible. Toutes vos données seront supprimées définitivement.</p>
                    <div class="input-group input-group-outline mt-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" id="delete_password" name="password" class="form-control" placeholder="Entrez votre mot de passe pour confirmer" required>
                    </div>
                    <x-input-error class="mt-2 text-danger small" :messages="$errors->userDeletion->get('password')" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn bg-gradient-danger">Supprimer mon compte</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .input-group-outline {
        margin-top: 0 !important;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }
</style>
@endpush