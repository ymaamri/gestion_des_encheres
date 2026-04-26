<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Vendeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the seller's annonces.
     */
    public function index()
    {
        if (Auth::user()->role !== 'vendeur') {
            abort(403, 'Only sellers can access this page.');
        }

        $client = Auth::user()->client;
        if (!$client || !$client->vendeur) {
            abort(403, 'Seller profile not found.');
        }

        $vendeur = $client->vendeur;
        $annonces = Annonce::with(['produit', 'produit.categorie'])
            ->where('vendeur_id', $vendeur->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('annonces.index', compact('annonces'));
    }

    /**
     * Show the form for creating a new annonce.
     */
    public function create()
    {
        if (Auth::user()->role !== 'vendeur') {
            abort(403, 'Only sellers can access this page.');
        }

        $categories = Categorie::all();

        // Get seller's own products (via the vendeur_id column)
        $vendeur = Auth::user()->client->vendeur;
        $sellerProducts = Produit::where('vendeur_id', $vendeur->id)->get();

        return view('annonces.create', compact('categories', 'sellerProducts'));
    }

    /**
     * Store a newly created annonce in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'vendeur') {
            abort(403, 'Only sellers can access this page.');
        }

        // Common auction validation
        $commonRules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_depart' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'date_fin' => 'required|date|after:now',
            'montant_mise' => 'nullable|numeric|min:1',
        ];

        $validated = $request->validate($commonRules);

        // Product source specific validation
        if ($request->product_source === 'existing') {
            $request->validate([
                'existing_product_id' => 'required|exists:produits,id',
            ]);
            $product = Produit::findOrFail($request->existing_product_id);

            // Verify this product belongs to the seller (using vendeur_id)
            $vendeur = Auth::user()->client->vendeur;
            if ($product->vendeur_id != $vendeur->id) {
                return back()->withErrors(['existing_product_id' => 'Ce produit ne vous appartient pas.'])->withInput();
            }
        } else // new product
        {
            $request->validate([
                'produit_nom' => 'required|string|max:255',
                'produit_description' => 'nullable|string',
                'produit_marque' => 'nullable|string|max:255',
                'produit_modele' => 'nullable|string|max:255',
                'produit_etat' => 'required|in:NEUF,TRES_BON_ETAT,BON_ETAT,ACCEPTABLE',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        }

        try {
            DB::beginTransaction();

            $vendeur = Auth::user()->client->vendeur;

            // Determine the product (existing or new)
            if ($request->product_source === 'existing') {
                $product = Produit::findOrFail($request->existing_product_id);
            } else {
                // Handle photo uploads for new product
                $photos = [];
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('products', $filename, 'public');
                        $photos[] = $path;
                    }
                }

                $product = Produit::create([
                    'nom' => $request->produit_nom,
                    'description' => $request->produit_description,
                    'marque' => $request->produit_marque,
                    'modele' => $request->produit_modele,
                    'etat' => $request->produit_etat,
                    'photos' => $photos,
                    'vendeur_id' => $vendeur->id,
                ]);
            }

            $annonce = Annonce::create([
                'vendeur_id' => $vendeur->id,
                'produit_id' => $product->id,
                'titre' => $validated['titre'],
                'description' => $validated['description'],
                'prix_depart' => $validated['prix_depart'],
                'montant_mise' => $request->montant_mise ?? 1,
                'date_debut' => null, // will be set by admin during validation
                'date_fin' => $validated['date_fin'],
                'statut' => 'EN_ATTENTE',
            ]);

            DB::commit();

            return redirect()->route('annonces.index')
                ->with('success', 'Votre annonce a été créée avec succès et est en attente de validation par un administrateur.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified annonce.
     */
    public function show(Annonce $annonce)
    {
        $annonce->load(['produit', 'produit.sousCategorie.categorie', 'vendeur.client.user']);

        $currentHighestBid = $annonce->getMontantActuel();

        $userBid = null;
        if (Auth::check() && Auth::user()->client) {
            $userBid = $annonce->encheres()
                ->where('client_id', Auth::user()->client->id)
                ->latest()
                ->first();
        }

        return view('annonces.show', compact('annonce', 'currentHighestBid', 'userBid'));
    }

    /**
     * Show the form for editing the specified annonce.
     */
    public function edit(Annonce $annonce)
    {
        $user = Auth::user();
        if ($user->role !== 'vendeur' || $annonce->vendeur_id !== $user->client->vendeur->id) {
            abort(403);
        }

        if (!in_array($annonce->statut, ['EN_ATTENTE'])) {
            return redirect()->route('annonces.index')
                ->with('error', 'Cette annonce ne peut plus être modifiée.');
        }

        $categories = Categorie::all();
        return view('annonces.edit', compact('annonce', 'categories'));
    }

    /**
     * Update the specified annonce in storage.
     */
    public function update(Request $request, Annonce $annonce)
    {
        $user = Auth::user();
        if ($user->role !== 'vendeur' || $annonce->vendeur_id !== $user->client->vendeur->id) {
            abort(403);
        }

        if ($annonce->statut !== 'EN_ATTENTE') {
            return back()->with('error', 'Cette annonce ne peut pas être modifiée car elle est déjà active ou terminée.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_depart' => 'required|numeric|min:0',
            'date_fin' => 'required|date|after:now',
            'montant_mise' => 'nullable|numeric|min:1',
            'produit_nom' => 'required|string|max:255',
            'produit_description' => 'nullable|string',
            'produit_marque' => 'nullable|string|max:255',
            'produit_modele' => 'nullable|string|max:255',
            'produit_etat' => 'required|in:NEUF,TRES_BON_ETAT,BON_ETAT,ACCEPTABLE',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $produit = $annonce->produit;
            $produit->update([
                'nom' => $validated['produit_nom'],
                'description' => $validated['produit_description'],
                'marque' => $validated['produit_marque'],
                'modele' => $validated['produit_modele'],
                'etat' => $validated['produit_etat'],
            ]);

            if ($request->hasFile('photos')) {
                $existingPhotos = $produit->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('products', $filename, 'public');
                    $existingPhotos[] = $path;
                }
                $produit->photos = $existingPhotos;
                $produit->save();
            }

            $annonce->update([
                'titre' => $validated['titre'],
                'description' => $validated['description'],
                'prix_depart' => $validated['prix_depart'],
                'montant_mise' => $validated['montant_mise'],
                'date_fin' => $validated['date_fin'],
            ]);

            DB::commit();

            return redirect()->route('annonces.index')
                ->with('success', 'Annonce mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified annonce from storage.
     */
    public function destroy(Annonce $annonce)
    {
        $user = Auth::user();
        if ($user->role !== 'vendeur' || $annonce->vendeur_id !== $user->client->vendeur->id) {
            abort(403);
        }

        if ($annonce->statut !== 'EN_ATTENTE') {
            return back()->with('error', 'Vous ne pouvez supprimer que les annonces en attente de validation.');
        }

        try {
            $produit = $annonce->produit;
            $annonce->delete();

            // Only delete the product if it is not used in any other auction
            if ($produit->annonces()->count() === 0) {
                $produit->delete();
                if ($produit->photos && is_array($produit->photos)) {
                    foreach ($produit->photos as $photo) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            return redirect()->route('annonces.index')
                ->with('success', 'Annonce supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}