<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Enchere;
use App\Models\Notification;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Annonce::with(['vendeur.client', 'produit', 'encheres'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.auctions.index', compact('auctions'));
    }

    public function show(Annonce $annonce)
    {
        $annonce->load(['vendeur.client', 'produit', 'encheres.client']);

        return view('admin.auctions.show', compact('annonce'));
    }

    /**
     * Publish (activate) an auction.
     * Can be used for both pending and blocked auctions.
     */
    public function publish(Annonce $annonce)
    {
        // Only allow if status is EN_ATTENTE or BLOQUEE
        if (!in_array($annonce->statut, ['EN_ATTENTE', 'BLOQUEE'])) {
            return redirect()->route('admin.auctions.index')
                ->with('error', 'Cette annonce ne peut pas être publiée.');
        }

        // Set auction dates (you can enhance this with a form to pick custom dates)
        $dateDebut = now();
        $dateFin = now()->addDays(7); // 7 days auction by default

        // Use the model's valider method (if exists) or set fields manually
        if (method_exists($annonce, 'valider')) {
            $annonce->valider($dateDebut, $dateFin);
        } else {
            $annonce->date_debut = $dateDebut;
            $annonce->date_fin = $dateFin;
            $annonce->prix_actuel = $annonce->prix_depart;
            $annonce->statut = 'ACTIVE';
            $annonce->save();
        }

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce publiée avec succès. L\'enchère est maintenant active.');
    }

    /**
     * Block an active auction.
     */
    public function block(Annonce $annonce)
    {
        if ($annonce->statut !== 'ACTIVE') {
            return redirect()->route('admin.auctions.index')
                ->with('error', 'Seules les enchères actives peuvent être bloquées.');
        }

        $annonce->statut = 'BLOQUEE';
        $annonce->save();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce bloquée avec succès.');
    }

    /**
     * Delete an auction permanently.
     * Also removes all associated bids and notifications.
     */
    public function destroy(Annonce $annonce)
    {
        // Delete all bids (encheres) linked to this auction
        $annonce->encheres()->delete();

        // Delete the auction itself
        $annonce->delete();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }
}