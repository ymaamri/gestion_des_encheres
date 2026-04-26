<?php

namespace App\Console\Commands;

use App\Models\Annonce;
use App\Models\Notification;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Ferme les enchères dont la date_fin est dépassée';

    public function handle()
    {
        $expiredAuctions = Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '<', now())
            ->get();

        foreach ($expiredAuctions as $annonce) {
            // 1. Clôturer l'annonce
            $annonce->cloturer(); // méthode existante dans le modèle Annonce

            // 2. Récupérer le gagnant
            $winningBid = $annonce->getHighestBid();
            if ($winningBid && $winningBid->client) {
                $winnerClient = $winningBid->client;
                // Notification au gagnant
                Notification::create([
                    'client_id' => $winnerClient->id,
                    'message' => "Félicitations ! Vous avez remporté l'enchère « {$annonce->titre} » avec un montant de " . number_format($winningBid->montant, 2) . " MAD.",
                    'date_envoi' => now(),
                    'type' => 'VICTOIRE',
                    'lue' => false,
                ]);

                // Notification au vendeur
                $sellerClient = $annonce->vendeur->client;
                Notification::create([
                    'client_id' => $sellerClient->id,
                    'message' => "Votre enchère « {$annonce->titre} » est terminée. Le gagnant est {$winnerClient->nom} {$winnerClient->prenom} avec un montant de " . number_format($winningBid->montant, 2) . " MAD.",
                    'date_envoi' => now(),
                    'type' => 'FIN_ENCHERE',
                    'lue' => false,
                ]);
            } else {
                // Aucune enchère => notification au vendeur
                Notification::create([
                    'client_id' => $annonce->vendeur->client->id,
                    'message' => "Votre enchère « {$annonce->titre} » s'est terminée sans aucune offre.",
                    'date_envoi' => now(),
                    'type' => 'FIN_ENCHERE',
                    'lue' => false,
                ]);
            }
        }

        $this->info(count($expiredAuctions) . ' enchère(s) clôturée(s).');
    }
}