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
        // Create categories if they don't exist
        $categories = [
            ['nom' => 'Electronics', 'description' => 'Smartphones, laptops, tablets', 'icone' => 'devices'],
            ['nom' => 'Fashion', 'description' => 'Clothing, shoes, accessories', 'icone' => 'checkroom'],
            ['nom' => 'Home & Garden', 'description' => 'Furniture, decor, appliances', 'icone' => 'home'],
            ['nom' => 'Sports', 'description' => 'Sports equipment, fitness gear', 'icone' => 'sports_basketball'],
        ];

        foreach ($categories as $catData) {
            Categorie::firstOrCreate(['nom' => $catData['nom']], $catData);
        }

        // Create subcategories for Electronics
        $electronics = Categorie::where('nom', 'Electronics')->first();
        $smartphonesSubcat = null;
        $laptopsSubcat = null;
        $tabletsSubcat = null;

        if ($electronics) {
            $subcats = [
                ['nom' => 'Smartphones', 'description' => 'Mobile phones and accessories'],
                ['nom' => 'Laptops', 'description' => 'Notebooks and computers'],
                ['nom' => 'Tablets', 'description' => 'Tablets and e-readers'],
            ];

            foreach ($subcats as $subData) {
                $subcat = SousCategorie::firstOrCreate(
                    ['categorie_id' => $electronics->id, 'nom' => $subData['nom']],
                    $subData
                );

                if ($subData['nom'] == 'Smartphones') {
                    $smartphonesSubcat = $subcat;
                } elseif ($subData['nom'] == 'Laptops') {
                    $laptopsSubcat = $subcat;
                } elseif ($subData['nom'] == 'Tablets') {
                    $tabletsSubcat = $subcat;
                }
            }
        }

        // Get or create test users
        // 1. Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@auction.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Client user (buyer)
        $clientUser = User::firstOrCreate(
            ['email' => 'client@auction.com'],
            [
                'nom' => 'John',
                'prenom' => 'Doe',
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
                'solde' => 10000.00,
                'statut' => 'ACTIF',
            ]
        );

        // 3. Seller user
        $sellerUser = User::firstOrCreate(
            ['email' => 'seller@auction.com'],
            [
                'nom' => 'Jane',
                'prenom' => 'Smith',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
            ]
        );

        $sellerClient = Client::firstOrCreate(
            ['user_id' => $sellerUser->id],
            [
                'nom' => 'Jane',
                'prenom' => 'Smith',
                'telephone' => '0698765432',
                'adresse_livraison' => '456 Market Street, Rabat',
                'solde' => 5000.00,
                'statut' => 'ACTIF',
            ]
        );

        $vendeur = Vendeur::firstOrCreate(
            ['client_id' => $sellerClient->id],
            [
                'siret' => '12345678901234',
                'note_moyenne' => 4.5,
                'nombre_ventes' => 25,
            ]
        );

        // 4. Another test buyer
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
                'solde' => 5000.00,
                'statut' => 'ACTIF',
            ]
        );

        // Create sample products and auctions
        $products = [
            [
                'nom' => 'iPhone 14 Pro',
                'description' => 'Latest iPhone with dynamic island, A16 Bionic chip',
                'marque' => 'Apple',
                'modele' => '14 Pro',
                'etat' => 'NEUF',
                'categorie' => 'Electronics',
                'sous_categorie' => 'Smartphones',
                'titre' => 'iPhone 14 Pro - Like New',
                'prix_depart' => 8000,
                'date_debut' => Carbon::now()->subDays(1), // Commencé hier
                'date_fin' => Carbon::now()->addDays(5),
            ],
            [
                'nom' => 'MacBook Pro M2',
                'description' => 'Powerful laptop for professionals',
                'marque' => 'Apple',
                'modele' => 'MacBook Pro M2',
                'etat' => 'TRES_BON_ETAT',
                'categorie' => 'Electronics',
                'sous_categorie' => 'Laptops',
                'titre' => 'MacBook Pro M2 14" - Excellent condition',
                'prix_depart' => 15000,
                'date_debut' => Carbon::now()->subDays(2),
                'date_fin' => Carbon::now()->addDays(3),
            ],
            [
                'nom' => 'Samsung Galaxy S23',
                'description' => 'Premium Android smartphone',
                'marque' => 'Samsung',
                'modele' => 'Galaxy S23',
                'etat' => 'NEUF',
                'categorie' => 'Electronics',
                'sous_categorie' => 'Smartphones',
                'titre' => 'Samsung Galaxy S23 - Brand New',
                'prix_depart' => 6000,
                'date_debut' => Carbon::now()->subHours(12),
                'date_fin' => Carbon::now()->addDays(7),
            ],
            [
                'nom' => 'iPad Air',
                'description' => 'Versatile tablet for work and play',
                'marque' => 'Apple',
                'modele' => 'iPad Air',
                'etat' => 'BON_ETAT',
                'categorie' => 'Electronics',
                'sous_categorie' => 'Tablets',
                'titre' => 'iPad Air 5th Gen',
                'prix_depart' => 4000,
                'date_debut' => Carbon::now()->subDays(3),
                'date_fin' => Carbon::now()->addDays(2),
            ],
            [
                'nom' => 'Nike Air Max',
                'description' => 'Comfortable running shoes',
                'marque' => 'Nike',
                'modele' => 'Air Max',
                'etat' => 'TRES_BON_ETAT',
                'categorie' => 'Fashion',
                'sous_categorie' => null,
                'titre' => 'Nike Air Max 90 - Size 42',
                'prix_depart' => 800,
                'date_debut' => Carbon::now()->subDays(1),
                'date_fin' => Carbon::now()->addDays(10),
            ],
        ];

        foreach ($products as $productData) {
            // Get category
            $categorie = Categorie::where('nom', $productData['categorie'])->first();

            // Get subcategory if specified
            $sousCategorieId = null;
            if ($productData['sous_categorie'] && $categorie) {
                $sousCategorie = SousCategorie::where('nom', $productData['sous_categorie'])
                    ->where('categorie_id', $categorie->id)
                    ->first();
                if ($sousCategorie) {
                    $sousCategorieId = $sousCategorie->id;
                }
            }

            // Create product
            $produit = Produit::create([
                'nom' => $productData['nom'],
                'description' => $productData['description'],
                'marque' => $productData['marque'],
                'modele' => $productData['modele'],
                'etat' => $productData['etat'],
                'sous_categorie_id' => $sousCategorieId,
                'photos' => [],
            ]);

            // Create auction (annonce)
            $annonce = Annonce::create([
                'vendeur_id' => $vendeur->id,
                'produit_id' => $produit->id,
                'titre' => $productData['titre'],
                'description' => $productData['description'],
                'prix_depart' => $productData['prix_depart'],
                'prix_final' => null,
                'statut' => 'ACTIVE',
            ]);

            // Create the first enchere (bid) to start the auction with dates
            $firstEnchere = Enchere::create([
                'annonce_id' => $annonce->id,
                'client_id' => null, // No client for the starting price
                'montant' => $productData['prix_depart'],
                'date_mise' => $productData['date_debut'],
                'date_debut' => $productData['date_debut'],
                'date_fin' => $productData['date_fin'],
            ]);

            // Add sample bids for the iPhone auction
            if ($productData['nom'] == 'iPhone 14 Pro') {
                // Bid 1: Client at 8500
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 8500,
                    'date_mise' => Carbon::now()->subHours(2),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);

                // Bid 2: Test client at 9000
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $testClient->id,
                    'montant' => 9000,
                    'date_mise' => Carbon::now()->subHour(),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);

                // Bid 3: Client at 9500 (current highest)
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 9500,
                    'date_mise' => Carbon::now()->subMinutes(30),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);
            }

            // Add sample bids for MacBook
            if ($productData['nom'] == 'MacBook Pro M2') {
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 15500,
                    'date_mise' => Carbon::now()->subDay(),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);
            }

            // Add sample bids for Samsung Galaxy
            if ($productData['nom'] == 'Samsung Galaxy S23') {
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $testClient->id,
                    'montant' => 6200,
                    'date_mise' => Carbon::now()->subHours(5),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);

                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 6500,
                    'date_mise' => Carbon::now()->subHours(2),
                    'date_debut' => $productData['date_debut'],
                    'date_fin' => $productData['date_fin'],
                ]);
            }
        }

        // Create a closed auction (completed)
        $closedProduct = Produit::create([
            'nom' => 'PlayStation 5',
            'description' => 'Next-gen gaming console',
            'marque' => 'Sony',
            'modele' => 'PS5',
            'etat' => 'NEUF',
            'sous_categorie_id' => null,
            'photos' => [],
        ]);

        $dateDebutClosed = Carbon::now()->subDays(10);
        $dateFinClosed = Carbon::now()->subDays(2);

        $closedAuction = Annonce::create([
            'vendeur_id' => $vendeur->id,
            'produit_id' => $closedProduct->id,
            'titre' => 'PlayStation 5 - Complete package',
            'description' => 'Includes 2 controllers and 3 games',
            'prix_depart' => 4000,
            'prix_final' => 5200,
            'statut' => 'CLOTUREE',
        ]);

        // Starting bid
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => null,
            'montant' => 4000,
            'date_mise' => $dateDebutClosed,
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
        ]);

        // Intermediate bids
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $testClient->id,
            'montant' => 4500,
            'date_mise' => Carbon::now()->subDays(8),
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
        ]);

        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $client->id,
            'montant' => 4800,
            'date_mise' => Carbon::now()->subDays(6),
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
        ]);

        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $testClient->id,
            'montant' => 5000,
            'date_mise' => Carbon::now()->subDays(5),
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
        ]);

        // Winning bid
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $client->id,
            'montant' => 5200,
            'date_mise' => Carbon::now()->subDays(3),
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
        ]);

        // Create notifications
        // Notification for winning auction
        Notification::create([
            'client_id' => $client->id,
            'message' => 'Félicitations ! Vous avez gagné l\'enchère pour la PlayStation 5 avec une offre de 5 200 MAD.',
            'date_envoi' => Carbon::now()->subDays(2),
            'type' => 'VICTOIRE',
            'lue' => false,
        ]);

        // Notification for being outbid on iPhone
        Notification::create([
            'client_id' => $testClient->id,
            'message' => 'Vous avez été surenchéri sur l\'iPhone 14 Pro. Nouveau prix : 9 500 MAD.',
            'date_envoi' => Carbon::now()->subMinutes(30),
            'type' => 'SURENCHERE',
            'lue' => false,
        ]);

        // Notification for auction ending soon
        Notification::create([
            'client_id' => $client->id,
            'message' => 'L\'enchère pour le MacBook Pro M2 se termine dans 3 jours !',
            'date_envoi' => Carbon::now()->subDay(),
            'type' => 'FIN_ENCHERE',
            'lue' => true,
        ]);

        $this->command->info('✅ Test auctions, bids, and notifications created successfully!');
        $this->command->info('📊 Statistics:');
        $this->command->info('   - Categories: ' . Categorie::count());
        $this->command->info('   - Subcategories: ' . SousCategorie::count());
        $this->command->info('   - Products: ' . Produit::count());
        $this->command->info('   - Auctions: ' . Annonce::count());
        $this->command->info('   - Bids (Encheres): ' . Enchere::count());
        $this->command->info('   - Notifications: ' . Notification::count());
    }
}