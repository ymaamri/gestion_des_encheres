<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Mise;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function myBids()
    {
        $client = Auth::user()->client;

        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $bids = Mise::with(['annonce', 'annonce.produit', 'annonce.vendeur.client'])
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $activeBidsCount = 0;
        $outbidCount = 0;
        $wonCount = 0;

        foreach ($bids as $mise) {
            if ($mise->annonce->statut == 'ACTIVE') {
                $highestBid = $mise->annonce->mises()->max('montant');
                if ($highestBid == $mise->montant) {
                    $activeBidsCount++;
                } else {
                    $outbidCount++;
                }
            } elseif ($mise->annonce->statut == 'CLOTUREE') {
                $highestBid = $mise->annonce->mises()->max('montant');
                if ($highestBid == $mise->montant) {
                    $wonCount++;
                }
            }
        }

        return view('bids.my-bids', compact('bids', 'activeBidsCount', 'outbidCount', 'wonCount'));
    }

    public function wonAuctions()
    {
        $client = Auth::user()->client;

        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        // Get all bids where the user was the winner on closed auctions
        $wonAuctions = Mise::with(['annonce', 'annonce.produit', 'annonce.vendeur.client'])
            ->where('client_id', $client->id)
            ->whereHas('annonce', function ($query) {
                $query->where('statut', 'CLOTUREE');
            })
            ->get()
            ->filter(function ($mise) {
                $highestBid = $mise->annonce->mises()->max('montant');
                return $highestBid == $mise->montant;
            });

        // Paginate manually (or use a different approach)
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $wonAuctions->slice(($currentPage - 1) * $perPage, $perPage);
        $wonAuctions = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $wonAuctions->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('bids.won-auctions', compact('wonAuctions'));
    }

    public function placeBid(Request $request, Annonce $annonce)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        // Check if auction is active
        if ($annonce->statut !== 'ACTIVE') {
            return back()->with('error', 'Cette enchère n\'est pas active.');
        }

        // Check if auction has ended
        if (now() > $annonce->date_fin) {
            $annonce->cloturer();
            return back()->with('error', 'Cette enchère est déjà terminée.');
        }

        $client = Auth::user()->client;

        if (!$client) {
            return back()->with('error', 'Client profile not found.');
        }

        $currentHighestBid = $annonce->mises()->max('montant') ?? $annonce->prix_depart;

        // Check if bid is high enough
        if ($request->montant <= $currentHighestBid) {
            return back()->with('error', 'Votre enchère doit être supérieure à ' . number_format($currentHighestBid, 2) . ' MAD.');
        }

        // Check if user has sufficient balance (optional - depends on your business logic)
        // if ($client->solde < $request->montant) {
        //     return back()->with('error', 'Solde insuffisant pour placer cette enchère.');
        // }

        DB::beginTransaction();

        try {
            // Create the bid
            $mise = Mise::create([
                'annonce_id' => $annonce->id,
                'client_id' => $client->id,
                'montant' => $request->montant,
                'date_mise' => now(),
            ]);

            // Update current price in annonce
            $annonce->prix_actuel = $request->montant;
            $annonce->save();

            // Notify the previous highest bidder that they've been outbid
            $previousHighestBid = $annonce->mises()
                ->where('montant', $currentHighestBid)
                ->where('client_id', '!=', $client->id)
                ->first();

            if ($previousHighestBid && $previousHighestBid->client_id != $client->id) {
                Notification::create([
                    'client_id' => $previousHighestBid->client_id,
                    'message' => 'Vous avez été dépassé sur l\'enchère "' . $annonce->titre . '". Nouveau montant: ' . number_format($request->montant, 2) . ' MAD',
                    'date_envoi' => now(),
                    'type' => 'SURENCHERE',
                    'lue' => false,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Votre enchère de ' . number_format($request->montant, 2) . ' MAD a été placée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}