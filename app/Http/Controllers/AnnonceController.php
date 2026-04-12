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

class AnnonceController extends Controller
{
    /**
     * Display a listing of the user's annonces.
     */
    public function index()
    {
        // Check if user is seller
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
        // Check if user is seller
        if (Auth::user()->role !== 'vendeur') {
            abort(403, 'Only sellers can access this page.');
        }

        $categories = Categorie::all();
        return view('annonces.create', compact('categories'));
    }

    /**
     * Store a newly created annonce in storage.
     */
    public function store(Request $request)
    {
        // Check if user is seller
        if (Auth::user()->role !== 'vendeur') {
            abort(403, 'Only sellers can access this page.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_depart' => 'required|numeric|min:0',
            'date_fin' => 'required|date|after:now',
            'categorie_id' => 'required|exists:categories,id',
            'produit_nom' => 'required|string|max:255',
            'produit_description' => 'nullable|string',
            'produit_marque' => 'nullable|string|max:255',
            'produit_modele' => 'nullable|string|max:255',
            'produit_etat' => 'required|in:NEUF,TRES_BON_ETAT,BON_ETAT,ACCEPTABLE',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create product
            $produit = Produit::create([
                'nom' => $validated['produit_nom'],
                'description' => $validated['produit_description'],
                'marque' => $validated['produit_marque'],
                'modele' => $validated['produit_modele'],
                'etat' => $validated['produit_etat'],
                'categorie_id' => $validated['categorie_id'],
                'photos' => [],
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('products', 'public');
                    $photos[] = $path;
                }
                $produit->photos = $photos;
                $produit->save();
            }

            // Create annonce
            $vendeur = Auth::user()->client->vendeur;
            $annonce = Annonce::create([
                'vendeur_id' => $vendeur->id,
                'produit_id' => $produit->id,
                'titre' => $validated['titre'],
                'description' => $validated['description'],
                'prix_depart' => $validated['prix_depart'],
                'date_debut' => now(),
                'date_fin' => $validated['date_fin'],
                'statut' => 'ACTIVE', // Set to ACTIVE for testing
                'montant_mise' => $validated['prix_depart'],
            ]);

            DB::commit();

            return redirect()->route('annonces.index')
                ->with('success', 'Your listing has been created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified annonce.
     */
    public function show(Annonce $annonce)
    {
        $annonce->load(['produit', 'produit.categorie', 'vendeur.client.user']);

        $currentHighestBid = $annonce->mises()->max('montant') ?? $annonce->prix_depart;
        $userBid = null;

        if (Auth::check() && Auth::user()->client) {
            $userBid = $annonce->mises()
                ->where('client_id', Auth::user()->client->id)
                ->latest()
                ->first();
        }

        return view('annonces.show', compact('annonce', 'currentHighestBid', 'userBid'));
    }
}