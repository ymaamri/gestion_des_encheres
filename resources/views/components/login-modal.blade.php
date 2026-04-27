<!-- Login Modal Component -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">

            <!-- Modal Header with Close Button only -->
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="font-size: 1.2rem;"></button>
            </div>

            <div class="modal-body px-5 pt-0 pb-5">
                <!-- Welcome Text -->
                <div class="text-center mb-4">
                    <h3 class="font-weight-bolder" style="color: #4a5568;">Bienvenue!</h3>
                    <p class="text-muted" style="font-size: 0.9rem;">Connectez-vous pour gérer votre compte.</p>
                </div>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="alert alert-success text-white text-sm" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-white text-sm" role="alert">
                        Une erreur est survenue lors de la connexion.
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="mt-4">
                    @csrf

                    <!-- Email -->
                    <div class="input-group input-group-outline mb-4 {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        <span class="input-group-text">
                            <i class="material-symbols-rounded" style="color: #a0aec0;">mail</i>
                        </span>
                        <input type="email" name="email" class="form-control px-3" placeholder="Adresse Email"
                            value="{{ old('email') }}" required autofocus
                            style="border-left: none; border-radius: 0 12px 12px 0;">
                    </div>
                    @error('email')
                        <div class="text-danger text-xs mb-4 mt-n3">{{ $message }}</div>
                    @enderror

                    <!-- Password -->
                    <div
                        class="input-group input-group-outline mb-3 {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <span class="input-group-text">
                            <i class="material-symbols-rounded" style="color: #a0aec0;">lock</i>
                        </span>
                        <input type="password" name="password" id="modalPasswordInput" class="form-control px-3"
                            placeholder="Mot de passe" required
                            style="border-left: none; border-radius: 0 12px 12px 0;">
                    </div>
                    @error('password')
                        <div class="text-danger text-xs mb-3 mt-n2">{{ $message }}</div>
                    @enderror

                    <!-- Forgot Password Link -->
                    <div class="text-end mb-4">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm"
                                style="color: #a0aec0; text-decoration: none; border-bottom: 1px dashed #a0aec0;">
                                Mot de passe oublié?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 mb-4"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50px; padding: 12px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                        S'identifier
                    </button>

                    <!-- Registration Link -->
                    <div class="text-center">
                        <p class="text-sm text-muted mb-3">Vous n'avez pas de compte?</p>
                        <a href="{{ route('register') }}" class="btn w-100"
                            style="background-color: #2d3748; color: white; border-radius: 50px; padding: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="material-symbols-rounded" style="font-size: 1.2rem;">person_add</i>
                            Inscrivez-vous gratuitement
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script to automatically show modal if there are login errors -->
@if($errors->has('email') || $errors->has('password'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof bootstrap !== 'undefined') {
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            }
        });
    </script>
@endif