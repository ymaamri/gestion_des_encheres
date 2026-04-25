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

    public function publish(Annonce $annonce)
    {
        $annonce->statut = 'ACTIVE';
        $annonce->date_debut = now();
        $annonce->date_fin = now()->addDays(7); // Default 7 days auction
        $annonce->save();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce publiée avec succès');
    }

    public function block(Annonce $annonce)
    {
        $annonce->statut = 'BLOQUEE';
        $annonce->save();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce bloquée avec succès');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Annonce supprimée avec succès');
    }
}