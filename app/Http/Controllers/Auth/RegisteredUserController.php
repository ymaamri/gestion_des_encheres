<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client; // Add this line
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Split the name into nom and prenom (assume first word is prenom, rest is nom)
        $fullName = explode(' ', $request->name, 2);
        $prenom = $fullName[0] ?? '';
        $nom = $fullName[1] ?? $fullName[0] ?? '';

        $user = User::create([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // default role
        ]);

        // Create corresponding client record
        Client::create([
            'user_id' => $user->id,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => null,
            'adresse_livraison' => null,
            'solde' => 0,
            'statut' => 'ACTIF',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}