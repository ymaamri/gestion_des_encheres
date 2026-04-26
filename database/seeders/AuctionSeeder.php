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

        if ($electronics) {
            $subcats = [
                ['nom' => 'Smartphones', 'description' => 'Mobile phones and accessories'],
                ['nom' => 'Laptops', 'description' => 'Notebooks and computers'],
                ['nom' => 'Tablets', 'description' => 'Tablets and e-readers'],
            ];

            foreach ($subcats as $subData) {
                SousCategorie::firstOrCreate(
                    ['categorie_id' => $electronics->id, 'nom' => $subData['nom']],
                    $subData
                );
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
        $productDataList = [
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
                'date_debut' => Carbon::now()->subDays(1),
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

        foreach ($productDataList as $data) {
            // Get category
            $categorie = Categorie::where('nom', $data['categorie'])->first();

            // Get subcategory if specified
            $sousCategorieId = null;
            if ($data['sous_categorie'] && $categorie) {
                $sousCategorie = SousCategorie::where('nom', $data['sous_categorie'])
                    ->where('categorie_id', $categorie->id)
                    ->first();
                if ($sousCategorie) {
                    $sousCategorieId = $sousCategorie->id;
                }
            }

            // Create product (with vendeur_id)
            $produit = Produit::create([
                'nom' => $data['nom'],
                'description' => $data['description'],
                'marque' => $data['marque'],
                'modele' => $data['modele'],
                'etat' => $data['etat'],
                'sous_categorie_id' => $sousCategorieId,
                'vendeur_id' => $vendeur->id,
                'photos' => [],
            ]);

            // Create auction (annonce) with dates
            $annonce = Annonce::create([
                'vendeur_id' => $vendeur->id,
                'produit_id' => $produit->id,
                'titre' => $data['titre'],
                'description' => $data['description'],
                'prix_depart' => $data['prix_depart'],
                'prix_actuel' => $data['prix_depart'],
                'montant_mise' => 1,
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'prix_final' => null,
                'statut' => 'ACTIVE',
            ]);

            // Add sample bids (only if auction is active)
            // For iPhone
            if ($data['nom'] == 'iPhone 14 Pro') {
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 8500,
                    'date_mise' => Carbon::now()->subHours(2),
                ]);
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $testClient->id,
                    'montant' => 9000,
                    'date_mise' => Carbon::now()->subHour(),
                ]);
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 9500,
                    'date_mise' => Carbon::now()->subMinutes(30),
                ]);
                // Update annonce current price
                $annonce->prix_actuel = 9500;
                $annonce->save();
            }

            // For MacBook
            if ($data['nom'] == 'MacBook Pro M2') {
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 15500,
                    'date_mise' => Carbon::now()->subDay(),
                ]);
                $annonce->prix_actuel = 15500;
                $annonce->save();
            }

            // For Samsung Galaxy
            if ($data['nom'] == 'Samsung Galaxy S23') {
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $testClient->id,
                    'montant' => 6200,
                    'date_mise' => Carbon::now()->subHours(5),
                ]);
                Enchere::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 6500,
                    'date_mise' => Carbon::now()->subHours(2),
                ]);
                $annonce->prix_actuel = 6500;
                $annonce->save();
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
            'vendeur_id' => $vendeur->id,
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
            'prix_actuel' => 5200,
            'montant_mise' => 1,
            'date_debut' => $dateDebutClosed,
            'date_fin' => $dateFinClosed,
            'prix_final' => 5200,
            'statut' => 'CLOTUREE',
        ]);

        // Bids for closed auction
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $testClient->id,
            'montant' => 4500,
            'date_mise' => Carbon::now()->subDays(8),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $client->id,
            'montant' => 4800,
            'date_mise' => Carbon::now()->subDays(6),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $testClient->id,
            'montant' => 5000,
            'date_mise' => Carbon::now()->subDays(5),
        ]);
        Enchere::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $client->id,
            'montant' => 5200,
            'date_mise' => Carbon::now()->subDays(3),
        ]);

        // Create notifications
        Notification::create([
            'client_id' => $client->id,
            'message' => 'Félicitations ! Vous avez gagné l\'enchère pour la PlayStation 5 avec une offre de 5 200 MAD.',
            'date_envoi' => Carbon::now()->subDays(2),
            'type' => 'VICTOIRE',
            'lue' => false,
        ]);

        Notification::create([
            'client_id' => $testClient->id,
            'message' => 'Vous avez été surenchéri sur l\'iPhone 14 Pro. Nouveau prix : 9 500 MAD.',
            'date_envoi' => Carbon::now()->subMinutes(30),
            'type' => 'SURENCHERE',
            'lue' => false,
        ]);

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