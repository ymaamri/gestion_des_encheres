<?php

namespace App\Console\Commands;

use App\Models\Annonce;
use App\Models\Notification;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired {--once : Run only once without looping}';
    protected $description = 'Ferme les enchères dont la date_fin est dépassée (en boucle toutes les 15 secondes)';

    public function handle()
    {
        $once = $this->option('once');

        do {
            // Utilisation de whereRaw pour éviter les problèmes de timezone
            $expiredAuctions = Annonce::where('statut', 'ACTIVE')
                ->whereRaw('date_fin < NOW()')
                ->get();

            foreach ($expiredAuctions as $annonce) {
                // Clôturer l'annonce (utilise la méthode du modèle)
                $annonce->cloturer();

                // Notifications
                $winningBid = $annonce->getHighestBid();
                if ($winningBid && $winningBid->client) {
                    $winnerClient = $winningBid->client;
                    Notification::create([
                        'client_id' => $winnerClient->id,
                        'message' => "Félicitations ! Vous avez remporté l'enchère « {$annonce->titre} » avec un montant de " . number_format($winningBid->montant, 2) . " MAD.",
                        'date_envoi' => now(),
                        'type' => 'VICTOIRE',
                        'lue' => false,
                    ]);

                    $sellerClient = $annonce->vendeur->client;
                    Notification::create([
                        'client_id' => $sellerClient->id,
                        'message' => "Votre enchère « {$annonce->titre} » est terminée. Le gagnant est {$winnerClient->nom} {$winnerClient->prenom} avec un montant de " . number_format($winningBid->montant, 2) . " MAD.",
                        'date_envoi' => now(),
                        'type' => 'FIN_ENCHERE',
                        'lue' => false,
                    ]);
                } else {
                    Notification::create([
                        'client_id' => $annonce->vendeur->client->id,
                        'message' => "Votre enchère « {$annonce->titre} » s'est terminée sans aucune offre.",
                        'date_envoi' => now(),
                        'type' => 'FIN_ENCHERE',
                        'lue' => false,
                    ]);
                }
                $this->info("Enchère #{$annonce->id} (« {$annonce->titre} ») clôturée.");
            }

            if ($expiredAuctions->count() > 0) {
                $this->info(count($expiredAuctions) . ' enchère(s) clôturée(s).');
            }

            if ($once) {
                break;
            }

            // Attendre 15 secondes avant la prochaine vérification
            sleep(15);

        } while (!$once);

        return Command::SUCCESS;
    }
}