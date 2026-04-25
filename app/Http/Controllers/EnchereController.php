<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Enchere;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnchereController extends Controller
{
    public function myBids()
    {
        $user = auth()->user();
        $client = $user->client;

        $encheres = Enchere::where('client_id', $client->id)
            ->with('annonce.produit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('my-bids', compact('encheres'));
    }

    public function wonAuctions()
    {
        $user = auth()->user();
        $client = $user->client;

        $wonEncheres = Enchere::where('client_id', $client->id)
            ->whereHas('annonce', function ($q) {
                $q->where('statut', 'CLOTUREE');
            })
            ->with('annonce.produit')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($enchere) {
                // Check if this bid is the winning bid
                return $enchere->annonce->getHighestBid() &&
                    $enchere->annonce->getHighestBid()->id === $enchere->id;
            });

        return view('my-won', compact('wonEncheres'));
    }

    public function placeBid(Request $request, Annonce $annonce)
    {
        $request->validate([
            'montant' => 'required|numeric|min:' . ($annonce->getMontantActuel() + $annonce->montant_mise),
        ]);

        $user = auth()->user();
        $client = $user->client;

        // Check if auction is active
        if ($annonce->statut !== 'ACTIVE') {
            return back()->with('error', 'Cette enchère n\'est pas active.');
        }

        // Check if auction hasn't ended
        if ($annonce->date_fin < now()) {
            return back()->with('error', 'Cette enchère est déjà terminée.');
        }

        DB::transaction(function () use ($annonce, $client, $request) {
            // Create the bid
            $enchere = Enchere::create([
                'annonce_id' => $annonce->id,
                'client_id' => $client->id,
                'montant' => $request->montant,
                'date_mise' => now(),
            ]);

            // Update the current price
            $annonce->prix_actuel = $request->montant;
            $annonce->save();

            // Send notifications to outbid users
            $previousBids = Enchere::where('annonce_id', $annonce->id)
                ->where('client_id', '!=', $client->id)
                ->where('montant', '<', $request->montant)
                ->with('client')
                ->get();

            foreach ($previousBids as $previousBid) {
                Notification::create([
                    'client_id' => $previousBid->client_id,
                    'message' => "Vous avez été surenchéri sur l'annonce '{$annonce->titre}'. Nouveau montant: {$request->montant} DH",
                    'date_envoi' => now(),
                    'type' => 'SURENCHERE',
                    'lue' => false,
                ]);
            }
        });

        return back()->with('success', 'Enchère placée avec succès!');
    }
}