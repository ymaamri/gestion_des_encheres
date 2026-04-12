<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function active(Request $request)
    {
        $query = Annonce::with(['produit', 'produit.categorie', 'vendeur.client'])
            ->where('statut', 'ACTIVE')
            ->where('date_fin', '>', now());

        // Apply filters
        if ($request->filled('categorie')) {
            $query->whereHas('produit.categorie', function ($q) use ($request) {
                $q->where('id', $request->categorie);
            });
        }

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

        if ($request->filled('etat')) {
            $query->whereHas('produit', function ($q) use ($request) {
                $q->where('etat', $request->etat);
            });
        }

        $auctions = $query->orderBy('date_fin', 'asc')->paginate(12);
        $categories = Categorie::all();

        return view('auctions.active', compact('auctions', 'categories'));
    }
}