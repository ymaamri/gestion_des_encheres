<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use App\Models\Enchere;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display list of active auctions with filtering and sorting.
     */
    public function active(Request $request)
    {
        $query = Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '>', now())
            ->with(['produit', 'vendeur.client', 'encheres']);

        // Filter by category
        if ($request->filled('categorie')) {
            $query->whereHas('produit.categorie', function ($q) use ($request) {
                $q->where('id', $request->categorie);
            });
        }

        // Filter by price range
        if ($request->filled('prix_min')) {
            $query->where(function ($q) use ($request) {
                $q->where('prix_actuel', '>=', $request->prix_min)
                    ->orWhere('prix_depart', '>=', $request->prix_min);
            });
        }
        if ($request->filled('prix_max')) {
            $query->where(function ($q) use ($request) {
                $q->where('prix_actuel', '<=', $request->prix_max)
                    ->orWhere('prix_depart', '<=', $request->prix_max);
            });
        }

        // Filter by product condition
        if ($request->filled('etat')) {
            $query->whereHas('produit', function ($q) use ($request) {
                $q->where('etat', $request->etat);
            });
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(prix_actuel, prix_depart) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(prix_actuel, prix_depart) DESC');
                break;
            case 'ending_soon':
                $query->orderBy('date_fin', 'asc');
                break;
            case 'most_bids':
                $query->withCount('encheres')->orderBy('encheres_count', 'desc');
                break;
            default: // 'recent'
                $query->orderBy('created_at', 'desc');
        }

        $auctions = $query->paginate(12);
        $categories = Categorie::all();

        return view('auctions.active', compact('auctions', 'categories'));
    }

    /**
     * Display details of a specific auction.
     * Note: This method is kept for reference; the main show route likely uses AnnonceController@show.
     */
    public function show(Annonce $annonce)
    {
        // Load necessary relationships
        $annonce->load(['produit', 'vendeur.client', 'encheres.client']);

        // Get authenticated user's highest bid (if any)
        $userHighestBid = null;
        if (auth()->check() && auth()->user()->client) {
            $userHighestBid = Enchere::where('annonce_id', $annonce->id)
                ->where('client_id', auth()->user()->client->id)
                ->orderBy('montant', 'desc')
                ->first();
        }

        // You may also want to pass the current highest bid amount
        $currentHighestBid = $annonce->getMontantActuel();

        return view('auctions.show', compact('annonce', 'userHighestBid', 'currentHighestBid'));
    }
}