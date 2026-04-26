<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a list of completed auctions (sales) for the authenticated seller.
     */
    public function index()
    {
        $vendeur = Auth::user()->client->vendeur;

        // Get all closed auctions belonging to this seller, with winning bid and buyer
        $sales = Annonce::with(['produit', 'vendeur.client', 'encheres.client.user'])
            ->where('vendeur_id', $vendeur->id)
            ->where('statut', 'CLOTUREE')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($annonce) {
                $winningBid = $annonce->getHighestBid();
                $annonce->winning_bid_amount = $winningBid ? $winningBid->montant : $annonce->prix_depart;
                $annonce->winner = $winningBid ? $winningBid->client : null;
                return $annonce;
            });

        // Paginate manually because we added a calculated field
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $salesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sales->forPage($currentPage, $perPage),
            $sales->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('seller.sales.index', compact('salesPaginated'));
    }
}