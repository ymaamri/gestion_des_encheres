<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    /**
     * Display a listing of auctions.
     */
    public function index()
    {
        $auctions = Annonce::with(['produit', 'vendeur.client', 'mises'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.auctions.index', compact('auctions'));
    }

    /**
     * Publish an auction (make it active).
     */
    public function publish(Annonce $annonce)
    {
        if ($annonce->statut === 'ACTIVE') {
            return back()->with('error', 'Cette enchère est déjà active.');
        }

        if ($annonce->statut === 'CLOTUREE') {
            return back()->with('error', 'Cette enchère est déjà clôturée.');
        }

        $annonce->publier();

        // Notify the seller that their auction is published
        if ($annonce->vendeur && $annonce->vendeur->client) {
            Notification::create([
                'client_id' => $annonce->vendeur->client->id,
                'message' => 'Votre enchère "' . $annonce->titre . '" a été publiée et est maintenant active !',
                'date_envoi' => now(),
                'type' => 'VALIDATION',
                'lue' => false,
            ]);
        }

        return back()->with('success', 'L\'enchère a été publiée avec succès.');
    }

    /**
     * Block an auction.
     */
    public function block(Annonce $annonce)
    {
        if ($annonce->statut === 'BLOQUEE') {
            return back()->with('error', 'Cette enchère est déjà bloquée.');
        }

        if ($annonce->statut === 'CLOTUREE') {
            return back()->with('error', 'Impossible de bloquer une enchère clôturée.');
        }

        $annonce->statut = 'BLOQUEE';
        $annonce->save();

        // Notify the seller that their auction is blocked
        if ($annonce->vendeur && $annonce->vendeur->client) {
            Notification::create([
                'client_id' => $annonce->vendeur->client->id,
                'message' => 'Votre enchère "' . $annonce->titre . '" a été bloquée par l\'administrateur.',
                'date_envoi' => now(),
                'type' => 'VALIDATION',
                'lue' => false,
            ]);
        }

        return back()->with('success', 'L\'enchère a été bloquée avec succès.');
    }

    /**
     * Display the specified auction.
     */
    public function show(Annonce $auction)
    {
        $auction->load(['produit', 'vendeur.client', 'mises.client']);
        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Remove the specified auction.
     */
    public function destroy(Annonce $annonce)
    {
        $annonce->delete();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Enchère supprimée avec succès.');
    }
}