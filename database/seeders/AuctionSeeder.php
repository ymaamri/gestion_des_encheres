<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Vendeur;
use App\Models\Produit;
use App\Models\Annonce;
use App\Models\Enchere;
use App\Models\Categorie;
use App\Models\SousCategorie;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        // --- Ensure main seller exists (from UserSeeder) ---
        $sellerUser = User::where('email', 'seller@auction.com')->first();
        if (!$sellerUser) {
            $sellerUser = User::create([
                'nom' => 'Smith',
                'prenom' => 'Jane',
                'email' => 'seller@auction.com',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
            ]);
        }
        $sellerClient = Client::firstOrCreate(
            ['user_id' => $sellerUser->id],
            [
                'nom' => 'Jane',
                'prenom' => 'Smith',
                'telephone' => '0698765432',
                'adresse_livraison' => '456 Avenue des Vendeurs, Casablanca',
                'solde' => 10000,
                'statut' => 'ACTIF',
            ]
        );
        $vendeur = Vendeur::firstOrCreate(
            ['client_id' => $sellerClient->id],
            [
                'siret' => '12345678901234',
                'note_moyenne' => 4.7,
                'nombre_ventes' => 42,
            ]
        );

        // --- Additional seller (second seller for variety) ---
        $secondSellerUser = User::firstOrCreate(
            ['email' => 'electronics@auction.com'],
            [
                'nom' => 'Electro',
                'prenom' => 'Shop',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
            ]
        );
        $secondSellerClient = Client::firstOrCreate(
            ['user_id' => $secondSellerUser->id],
            [
                'nom' => 'Electro',
                'prenom' => 'Shop',
                'telephone' => '0611223344',
                'adresse_livraison' => '22 Rue des Gadgets, Rabat',
                'solde' => 5000,
                'statut' => 'ACTIF',
            ]
        );
        $vendeur2 = Vendeur::firstOrCreate(
            ['client_id' => $secondSellerClient->id],
            [
                'siret' => '98765432109876',
                'note_moyenne' => 4.9,
                'nombre_ventes' => 120,
            ]
        );

        // --- Ensure client users exist (buyers) ---
        $clientUser = User::firstOrCreate(
            ['email' => 'client@auction.com'],
            [
                'nom' => 'Doe',
                'prenom' => 'John',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
        $client = Client::firstOrCreate(
            ['user_id' => $clientUser->id],
            [
                'nom' => 'John',
                'prenom' => 'Doe',
                'telephone' => '0612345678',
                'adresse_livraison' => '123 Main Street, Casablanca',
                'solde' => 15000,
                'statut' => 'ACTIF',
            ]
        );

        $testUser = User::firstOrCreate(
            ['email' => 'test@auction.com'],
            [
                'nom' => 'Test',
                'prenom' => 'User',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
        $testClient = Client::firstOrCreate(
            ['user_id' => $testUser->id],
            [
                'nom' => 'Test',
                'prenom' => 'User',
                'telephone' => '0678945612',
                'adresse_livraison' => '789 Test Avenue, Tangier',
                'solde' => 8000,
                'statut' => 'ACTIF',
            ]
        );

        // Extra buyer for more bid activity
        $extraBuyer = User::firstOrCreate(
            ['email' => 'buyer2@auction.com'],
            [
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
        $extraClient = Client::firstOrCreate(
            ['user_id' => $extraBuyer->id],
            [
                'nom' => 'Sophie',
                'prenom' => 'Martin',
                'telephone' => '0678123456',
                'adresse_livraison' => '45 Rue des Enchères, Marrakech',
                'solde' => 12000,
                'statut' => 'ACTIF',
            ]
        );

        // --- Helper to get a random subcategory ID ---
        $allSubcategories = SousCategorie::pluck('id')->toArray();
        if (empty($allSubcategories)) {
            $this->command->error('No subcategories found! Run SubCategorySeeder first.');
            return;
        }

        // --- Sample product data with high-quality images (external) ---
        $productsData = [
            // Main seller products
            ['nom' => 'iPhone 15 Pro Max', 'description' => 'Latest Apple smartphone, 256GB, Titanium', 'marque' => 'Apple', 'modele' => 'A2849', 'etat' => 'NEUF', 'price_start' => 12000, 'bid_increment' => 100, 'seller' => $vendeur, 'images' => ['https://tokyostores.tn/1635-large_default/iphone-15-pro-max-256go-utilise-titane-naturel.jpg', 'https://i5.walmartimages.com/seo/Restored-Apple-iPhone-15-Pro-Max-256GB-Unlocked-Natural-Titanium-MU683LL-A-Excellent-Condition_5a937350-3f85-41bd-b020-01f822367896.db2538d8c4d95e105ad6b173d5e49d1b.jpeg']],
            ['nom' => 'MacBook Pro 16" M3', 'description' => 'Powerful laptop for professionals', 'marque' => 'Apple', 'modele' => 'M3 Max', 'etat' => 'NEUF', 'price_start' => 25000, 'bid_increment' => 200, 'seller' => $vendeur, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDN5jTNTAhb_GQ3yX-4uUtX_yZutsw1SlvWQ&s', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrmQHr8eoFsp7836Ggj6hvoX_oRfdZUxnGEw&s']],
            ['nom' => 'Samsung Galaxy S24 Ultra', 'description' => 'Android flagship, 512GB', 'marque' => 'Samsung', 'modele' => 'S928B', 'etat' => 'NEUF', 'price_start' => 9500, 'bid_increment' => 100, 'seller' => $vendeur, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpL0E_Yu6-zLKVdQlp2YUcAqpILb2nyaMkUQ&s', 'https://www.technomall.tn/wp-content/uploads/2024/06/smartphone-samsung-galaxy-s24-ultra-12go-256go-violet-titanium.jpg']],
            ['nom' => 'Nike Air Jordan 1 Retro', 'description' => 'Limited edition sneakers, size 42', 'marque' => 'Nike', 'modele' => 'AJ1', 'etat' => 'TRES_BON_ETAT', 'price_start' => 1800, 'bid_increment' => 20, 'seller' => $vendeur, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSdfV2m9pIq44l0ZY_EPHrJYnn1F-PP37Wvwg&s']],
            ['nom' => 'Cafetière Nespresso Vertuo', 'description' => 'Capsule coffee machine, nearly new', 'marque' => 'Nespresso', 'modele' => 'Vertuo Next', 'etat' => 'TRES_BON_ETAT', 'price_start' => 350, 'bid_increment' => 10, 'seller' => $vendeur, 'images' => ['data:image/jpeg;base64,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']],
            ['nom' => 'Vélo électrique Fiido D21', 'description' => 'Foldable electric bike, 25km/h', 'marque' => 'Fiido', 'modele' => 'D21', 'etat' => 'BON_ETAT', 'price_start' => 3200, 'bid_increment' => 50, 'seller' => $vendeur, 'images' => ['https://vcdlsports.com/wp-content/uploads/2024/01/Fiido-D11-Velo-electrique-pliant-450x450.webp']],
            // Second seller products
            ['nom' => 'PlayStation 5', 'description' => 'Next-gen console, 1TB, with extra controller', 'marque' => 'Sony', 'modele' => 'PS5', 'etat' => 'NEUF', 'price_start' => 4000, 'bid_increment' => 50, 'seller' => $vendeur2, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQxkedB8glvLG-1D62horaEOJpaJsVJIwBdcA&s']],
            ['nom' => 'Casque Sony WH-1000XM5', 'description' => 'Noise cancelling headphones', 'marque' => 'Sony', 'modele' => 'XM5', 'etat' => 'NEUF', 'price_start' => 320, 'bid_increment' => 10, 'seller' => $vendeur2, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTNC6oLqsvT21qYXilCVQbRq8x3-KSJqGXa6w&s']],
            ['nom' => 'Canon EOS R10', 'description' => 'Mirrorless camera with 18-45mm lens', 'marque' => 'Canon', 'modele' => 'EOS R10', 'etat' => 'TRES_BON_ETAT', 'price_start' => 5200, 'bid_increment' => 50, 'seller' => $vendeur2, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSD6iG4lU8lHC-hdh_RISEGvTDsKHozyE84NQ&s']],
            ['nom' => 'Show de cuisine (lot de 12)', 'description' => 'Collector edition, never opened', 'marque' => 'Le Creuset', 'modele' => 'Cocotte', 'etat' => 'NEUF', 'price_start' => 1200, 'bid_increment' => 25, 'seller' => $vendeur2, 'images' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbmbFkUnv28Hmfb5-dYzq4g1B0n8bbYFp8Mg&s']],
        ];

        $createdProducts = [];
        foreach ($productsData as $p) {
            // Random subcategory
            $subcatId = $allSubcategories[array_rand($allSubcategories)];
            $produit = Produit::create([
                'nom' => $p['nom'],
                'description' => $p['description'],
                'marque' => $p['marque'],
                'modele' => $p['modele'],
                'etat' => $p['etat'],
                'sous_categorie_id' => $subcatId,
                'vendeur_id' => $p['seller']->id,
                'photos' => $p['images'],
            ]);
            $createdProducts[] = $produit;
        }

        // --- Create auctions with different statuses ---
        $now = Carbon::now();

        // Active auctions (ending in 1-7 days)
        $activeAuctions = [];
        foreach ($createdProducts as $index => $product) {
            if ($index < 5) { // first 5 products become active
                $daysToAdd = rand(1, 7);
                $endDate = $now->copy()->addDays($daysToAdd);
                $annonce = Annonce::create([
                    'vendeur_id' => $product->vendeur_id,
                    'produit_id' => $product->id,
                    'titre' => $product->nom . ' – Enchère exceptionnelle',
                    'description' => $product->description . ' Participez et remportez cet article unique.',
                    'prix_depart' => $product->price_start ?? 1000,
                    'prix_actuel' => $product->price_start,
                    'montant_mise' => $product->bid_increment ?? 50,
                    'date_debut' => $now->copy()->subDays(rand(0, 2)),
                    'date_fin' => $endDate,
                    'statut' => 'ACTIVE',
                ]);
                $activeAuctions[] = $annonce;
            }
        }

        // Pending auctions (EN_ATTENTE) – 3 items
        foreach (array_slice($createdProducts, 5, 3) as $product) {
            Annonce::create([
                'vendeur_id' => $product->vendeur_id,
                'produit_id' => $product->id,
                'titre' => $product->nom . ' – En attente de validation',
                'description' => $product->description . ' Cette annonce sera bientôt active.',
                'prix_depart' => $product->price_start ?? 500,
                'prix_actuel' => null,
                'montant_mise' => $product->bid_increment ?? 50,
                'date_debut' => null,
                'date_fin' => $now->copy()->addDays(14),
                'statut' => 'EN_ATTENTE',
            ]);
        }

        // Blocked auction (BLOQUEE) – 1 item
        $blockedProduct = Produit::create([
            'nom' => 'Smartwatch suspecte',
            'description' => 'Produit signalé, en attente de vérification',
            'marque' => 'Generic',
            'modele' => 'Watch X',
            'etat' => 'BON_ETAT',
            'sous_categorie_id' => $allSubcategories[0],
            'vendeur_id' => $vendeur2->id,
            'photos' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7ivkTrmTqiuBhzWumg2PCv8gcVs6RcDX74g&s'],
        ]);
        Annonce::create([
            'vendeur_id' => $vendeur2->id,
            'produit_id' => $blockedProduct->id,
            'titre' => 'Smartwatch – Contenu bloqué',
            'description' => 'Annonce temporairement bloquée par l’administration.',
            'prix_depart' => 300,
            'prix_actuel' => null,
            'montant_mise' => 10,
            'date_debut' => null,
            'date_fin' => $now->copy()->addDays(5),
            'statut' => 'BLOQUEE',
        ]);

        // Closed auctions (CLOTUREE) – 2 items with winners
        $closedProduct1 = Produit::create([
            'nom' => 'Montre de luxe Rolex',
            'description' => 'Rolex Datejust, état impeccable',
            'marque' => 'Rolex',
            'modele' => 'Datejust 41',
            'etat' => 'TRES_BON_ETAT',
            'sous_categorie_id' => $allSubcategories[0],
            'vendeur_id' => $vendeur->id,
            'photos' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQC69SYL4SVulVhvJprRzWKlm2hE1TfR2gAxg&s', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDGHQshwCw60MmgCVQZA3ti9J4yXrbU6bkQQ&s'],
        ]);
        $closedAuction1 = Annonce::create([
            'vendeur_id' => $vendeur->id,
            'produit_id' => $closedProduct1->id,
            'titre' => 'Rolex Datejust – Enchère terminée',
            'description' => 'Superbe montre, vendue au plus offrant.',
            'prix_depart' => 8500,
            'prix_actuel' => 10250,
            'montant_mise' => 100,
            'date_debut' => $now->copy()->subDays(12),
            'date_fin' => $now->copy()->subDays(2),
            'prix_final' => 10250,
            'statut' => 'CLOTUREE',
        ]);

        $closedProduct2 = Produit::create([
            'nom' => 'Gibson Les Paul Standard',
            'description' => 'Guitare électrique légendaire, très bon état',
            'marque' => 'Gibson',
            'modele' => 'Les Paul',
            'etat' => 'TRES_BON_ETAT',
            'sous_categorie_id' => $allSubcategories[0],
            'vendeur_id' => $vendeur2->id,
            'photos' => ['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRrCLwFKRCVNY-vW90tZvkriT7IKUtfZyXTyQ&s'],
        ]);
        $closedAuction2 = Annonce::create([
            'vendeur_id' => $vendeur2->id,
            'produit_id' => $closedProduct2->id,
            'titre' => 'Gibson Les Paul – Vente terminée',
            'description' => 'Son exceptionnel, collectionneur.',
            'prix_depart' => 2200,
            'prix_actuel' => 2850,
            'montant_mise' => 50,
            'date_debut' => $now->copy()->subDays(10),
            'date_fin' => $now->copy()->subDays(1),
            'prix_final' => 2850,
            'statut' => 'CLOTUREE',
        ]);

        // --- Add bids on active auctions ---
        $buyers = [$client, $testClient, $extraClient];
        foreach ($activeAuctions as $auction) {
            $currentPrice = $auction->prix_depart;
            $numBids = rand(2, 6);
            for ($i = 0; $i < $numBids; $i++) {
                $buyer = $buyers[array_rand($buyers)];
                $increment = $auction->montant_mise * rand(1, 3);
                $bidAmount = $currentPrice + $increment;
                Enchere::create([
                    'annonce_id' => $auction->id,
                    'client_id' => $buyer->id,
                    'montant' => $bidAmount,
                    'date_mise' => $now->copy()->subMinutes(rand(10, 600)),
                ]);
                $currentPrice = $bidAmount;
                // Update auction current price
                $auction->prix_actuel = $currentPrice;
                $auction->save();

                // Create outbid notifications for previous leader
                $previousLeader = Enchere::where('annonce_id', $auction->id)
                    ->where('client_id', '!=', $buyer->id)
                    ->orderBy('montant', 'desc')
                    ->first();
                if ($previousLeader) {
                    Notification::create([
                        'client_id' => $previousLeader->client_id,
                        'message' => "Vous avez été surenchéri sur « {$auction->titre} ». Nouveau montant : " . number_format($bidAmount, 2) . " MAD",
                        'date_envoi' => $now,
                        'type' => 'SURENCHERE',
                        'lue' => false,
                    ]);
                }
            }
        }

        // --- Bids for closed auctions and winners ---
        // Closed auction 1: winner = client (John Doe)
        $win1 = Enchere::create([
            'annonce_id' => $closedAuction1->id,
            'client_id' => $client->id,
            'montant' => 10250,
            'date_mise' => $now->copy()->subDays(3),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction1->id,
            'client_id' => $testClient->id,
            'montant' => 9800,
            'date_mise' => $now->copy()->subDays(4),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction1->id,
            'client_id' => $extraClient->id,
            'montant' => 9200,
            'date_mise' => $now->copy()->subDays(5),
        ]);
        // Winner notification
        Notification::create([
            'client_id' => $client->id,
            'message' => "Félicitations ! Vous avez remporté l'enchère « {$closedAuction1->titre} » avec 10 250 MAD.",
            'date_envoi' => $now->copy()->subDays(2),
            'type' => 'VICTOIRE',
            'lue' => false,
        ]);
        Notification::create([
            'client_id' => $closedAuction1->vendeur->client_id,
            'message' => "Votre enchère « {$closedAuction1->titre} » s'est terminée. Gagnant : John Doe avec 10 250 MAD.",
            'date_envoi' => $now->copy()->subDays(2),
            'type' => 'FIN_ENCHERE',
            'lue' => true,
        ]);

        // Closed auction 2: winner = testClient
        $win2 = Enchere::create([
            'annonce_id' => $closedAuction2->id,
            'client_id' => $testClient->id,
            'montant' => 2850,
            'date_mise' => $now->copy()->subDays(2),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction2->id,
            'client_id' => $extraClient->id,
            'montant' => 2750,
            'date_mise' => $now->copy()->subDays(3),
        ]);
        Notification::create([
            'client_id' => $testClient->id,
            'message' => "Bravo ! Vous avez gagné l'enchère « {$closedAuction2->titre} » avec 2 850 MAD.",
            'date_envoi' => $now->copy()->subDays(1),
            'type' => 'VICTOIRE',
            'lue' => false,
        ]);

        // Additional general notification for extraClient
        Notification::create([
            'client_id' => $extraClient->id,
            'message' => "Bienvenue sur BidMaster ! Explorez des milliers d'enchères passionnantes.",
            'date_envoi' => $now,
            'type' => 'VALIDATION',
            'lue' => false,
        ]);

        $this->command->info('✅ Rich dataset created!');
        $this->command->info('📊 Statistics:');
        $this->command->info('   - Categories: ' . Categorie::count());
        $this->command->info('   - Subcategories: ' . SousCategorie::count());
        $this->command->info('   - Products: ' . Produit::count());
        $this->command->info('   - Auctions: ' . Annonce::count());
        $this->command->info('   - Bids: ' . Enchere::count());
        $this->command->info('   - Notifications: ' . Notification::count());
        $this->command->info('✅ You can now log in with:');
        $this->command->info('   Admin:    admin@auction.com / password');
        $this->command->info('   Seller:   seller@auction.com / password');
        $this->command->info('   Buyer:    client@auction.com / password');
        $this->command->info('   Test:     test@auction.com / password');
    }
}