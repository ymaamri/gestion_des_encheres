<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Enchere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    /**
     * Afficher toutes les offres reçues sur les annonces du vendeur connecté.
     */
    public function index()
    {
        $vendeur = Auth::user()->client->vendeur;

        // Récupérer tous les IDs des annonces du vendeur
        $annonceIds = $vendeur->annonces()->pluck('id');

        // Récupérer toutes les offres sur ces annonces, avec les relations utiles
        $bids = Enchere::with(['annonce.produit', 'client.user'])
            ->whereIn('annonce_id', $annonceIds)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Pour chaque offre, déterminer si elle est gagnante
        foreach ($bids as $bid) {
            $highestBid = $bid->annonce->getHighestBid();
            $bid->isWinning = ($highestBid && $highestBid->id === $bid->id);
        }

        return view('seller.bids.index', compact('bids'));
    }
}