<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Enchere;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnchereController extends Controller
{
    /**
     * Display the current user's bidding history.
     */
    public function myBids()
    {
        $user = auth()->user();
        $client = $user->client;

        $bids = Enchere::where('client_id', $client->id)
            ->with('annonce.produit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Additional stats for the view
        $activeBidsCount = Enchere::where('client_id', $client->id)
            ->whereHas('annonce', function ($q) {
                $q->where('statut', 'ACTIVE')->where('date_fin', '>', now());
            })
            ->count();

        $outbidCount = Enchere::where('client_id', $client->id)
            ->whereHas('annonce', function ($q) {
                $q->where('statut', 'ACTIVE');
            })
            ->get()
            ->filter(function ($bid) {
                $highest = $bid->annonce->getHighestBid();
                return $highest && $highest->id !== $bid->id;
            })
            ->count();

        $wonCount = Enchere::where('client_id', $client->id)
            ->whereHas('annonce', function ($q) {
                $q->where('statut', 'CLOTUREE');
            })
            ->get()
            ->filter(function ($bid) {
                $highest = $bid->annonce->getHighestBid();
                return $highest && $highest->id === $bid->id;
            })
            ->count();

        return view('bids.my-bids', compact('bids', 'activeBidsCount', 'outbidCount', 'wonCount'));
    }

    /**
     * Display auctions won by the current user.
     */
    public function wonAuctions()
    {
        $user = auth()->user();
        $client = $user->client;

        $wonAuctions = Enchere::where('client_id', $client->id)
            ->whereHas('annonce', function ($q) {
                $q->where('statut', 'CLOTUREE');
            })
            ->with('annonce.produit')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($enchere) {
                // Check if this bid is the winning bid (highest montant)
                $highestBid = $enchere->annonce->getHighestBid();
                return $highestBid && $highestBid->id === $enchere->id;
            });

        // Paginate the filtered collection manually (simple pagination)
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $wonAuctions = new \Illuminate\Pagination\LengthAwarePaginator(
            $wonAuctions->forPage($currentPage, $perPage),
            $wonAuctions->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('bids.won-auctions', compact('wonAuctions'));
    }

    /**
     * Place a new bid on an auction.
     */
    public function placeBid(Request $request, Annonce $annonce)
    {
        // 1. Validate bid amount
        $minAllowed = $annonce->getMontantActuel() + $annonce->montant_mise;
        $request->validate([
            'montant' => 'required|numeric|min:' . $minAllowed,
        ]);

        $user = auth()->user();
        $client = $user->client;

        // 2. Check auction status
        if ($annonce->statut !== 'ACTIVE') {
            return back()->with('error', 'Cette enchère n\'est pas active.');
        }

        if ($annonce->date_fin <= now()) {
            return back()->with('error', 'Cette enchère est déjà terminée.');
        }

        // 3. Check auction start date (if defined)
        if ($annonce->date_debut && $annonce->date_debut > now()) {
            return back()->with('error', 'Cette enchère n\'a pas encore commencé.');
        }

        // 4. Prevent self-outbidding (optional but clean)
        $lastUserBid = Enchere::where('annonce_id', $annonce->id)
            ->where('client_id', $client->id)
            ->latest()
            ->first();
        if ($lastUserBid && $request->montant <= $lastUserBid->montant) {
            return back()->with('error', 'Vous devez enchérir un montant supérieur à votre dernière mise.');
        }

        DB::transaction(function () use ($annonce, $client, $request) {
            // Lock the auction row to prevent race conditions
            $annonce = Annonce::where('id', $annonce->id)->lockForUpdate()->first();

            // Handle possible null date_debut (fallback to current time)
            $dateDebut = $annonce->date_debut ?? now();
            $dateFin = $annonce->date_fin;

            // Create the new bid
            $enchere = Enchere::create([
                'annonce_id' => $annonce->id,
                'client_id' => $client->id,
                'montant' => $request->montant,
                'date_mise' => now(),
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
            ]);

            // Update the current price on the annonce
            $annonce->prix_actuel = $request->montant;
            $annonce->save();

            // Notify users who were outbid (excluding current bidder)
            $outbidClientIds = Enchere::where('annonce_id', $annonce->id)
                ->where('client_id', '!=', $client->id)
                ->where('montant', '<', $request->montant)
                ->distinct('client_id')
                ->pluck('client_id');

            foreach ($outbidClientIds as $outbidClientId) {
                Notification::create([
                    'client_id' => $outbidClientId,
                    'message' => "Vous avez été surenchéri sur l'annonce « {$annonce->titre} ». Nouveau montant : " . number_format($request->montant, 2) . " MAD",
                    'date_envoi' => now(),
                    'type' => 'SURENCHERE',
                    'lue' => false,
                ]);
            }
        });

        return back()->with('success', 'Votre enchère a été placée avec succès !');
    }
}