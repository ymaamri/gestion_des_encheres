<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with(['client.vendeur'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:client,vendeur,admin',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Create client record for non-admin users
        if ($validated['role'] !== 'admin') {
            Client::create([
                'user_id' => $user->id,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? '',
                'statut' => 'ACTIF',
                'solde' => 0,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['client.vendeur']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:client,vendeur,admin',
        ]);

        $user->update($validated);

        // Update client record if exists
        if ($user->client) {
            $user->client->update([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? '',
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Block a user.
     */
    public function block(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de bloquer un administrateur.');
        }

        if ($user->client) {
            $user->client->update(['statut' => 'BLOQUE']);

            // Block all active auctions from this seller
            if ($user->role === 'vendeur' && $user->client->vendeur) {
                $user->client->vendeur->annonces()
                    ->where('statut', 'ACTIVE')
                    ->update(['statut' => 'BLOQUEE']);
            }
        }

        return back()->with('success', 'Utilisateur bloqué avec succès.');
    }

    /**
     * Unblock a user.
     */
    public function unblock(User $user)
    {
        if ($user->client) {
            $user->client->update(['statut' => 'ACTIF']);
        }

        return back()->with('success', 'Utilisateur débloqué avec succès.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}