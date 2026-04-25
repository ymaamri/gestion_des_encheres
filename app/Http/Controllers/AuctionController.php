<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Enchere;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function active()
    {
        $activeAuctions = Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '>', now())
            ->with(['produit', 'vendeur.client', 'encheres'])
            ->orderBy('date_fin', 'asc')
            ->paginate(12);

        return view('auctions.active', compact('activeAuctions'));
    }

    public function show(Annonce $annonce)
    {
        $annonce->load(['produit', 'vendeur.client', 'encheres.client']);

        $userHighestBid = null;
        if (auth()->check() && auth()->user()->client) {
            $userHighestBid = Enchere::where('annonce_id', $annonce->id)
                ->where('client_id', auth()->user()->client->id)
                ->orderBy('montant', 'desc')
                ->first();
        }

        return view('auctions.show', compact('annonce', 'userHighestBid'));
    }
}