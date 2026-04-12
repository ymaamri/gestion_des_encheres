<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Vendeur;
use App\Models\Produit;
use App\Models\Annonce;
use App\Models\Mise;
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
                'date_fin' => Carbon::now()->addDays(2),
            ],
            [
                'nom' => 'Nike Air Max',
                'description' => 'Comfortable running shoes',
                'marque' => 'Nike',
                'modele' => 'Air Max',
                'etat' => 'TRES_BON_ETAT',
                'categorie' => 'Fashion',
                'sous_categorie' => null, // No subcategory for Fashion items
                'titre' => 'Nike Air Max 90 - Size 42',
                'prix_depart' => 800,
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
                'sous_categorie_id' => $sousCategorieId, // This can now be null
                'photos' => [],
            ]);

            // Create auction
            $annonce = Annonce::create([
                'vendeur_id' => $vendeur->id,
                'produit_id' => $produit->id,
                'titre' => $productData['titre'],
                'description' => $productData['description'],
                'prix_depart' => $productData['prix_depart'],
                'prix_actuel' => $productData['prix_depart'],
                'montant_mise' => $productData['prix_depart'],
                'date_debut' => Carbon::now(),
                'date_fin' => $productData['date_fin'],
                'statut' => 'ACTIVE',
            ]);

            // Add some sample bids for the iPhone auction
            if ($productData['nom'] == 'iPhone 14 Pro') {
                Mise::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 8500,
                    'date_mise' => Carbon::now()->subHours(2),
                ]);

                Mise::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $testClient->id,
                    'montant' => 9000,
                    'date_mise' => Carbon::now()->subHour(),
                ]);

                Mise::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 9500,
                    'date_mise' => Carbon::now()->subMinutes(30),
                ]);

                $annonce->prix_actuel = 9500;
                $annonce->save();
            }

            // Add sample bid for MacBook
            if ($productData['nom'] == 'MacBook Pro M2') {
                Mise::create([
                    'annonce_id' => $annonce->id,
                    'client_id' => $client->id,
                    'montant' => 15500,
                    'date_mise' => Carbon::now()->subDay(),
                ]);

                $annonce->prix_actuel = 15500;
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
            'photos' => [],
        ]);

        $closedAuction = Annonce::create([
            'vendeur_id' => $vendeur->id,
            'produit_id' => $closedProduct->id,
            'titre' => 'PlayStation 5 - Complete package',
            'description' => 'Includes 2 controllers and 3 games',
            'prix_depart' => 4000,
            'prix_actuel' => 5200,
            'montant_mise' => 4000,
            'date_debut' => Carbon::now()->subDays(10),
            'date_fin' => Carbon::now()->subDays(2),
            'statut' => 'CLOTUREE',
        ]);

        // Add winning bid for closed auction
        Mise::create([
            'annonce_id' => $closedAuction->id,
            'client_id' => $client->id,
            'montant' => 5200,
            'date_mise' => Carbon::now()->subDays(3),
        ]);

        // Add notification for winning auction
        Notification::create([
            'client_id' => $client->id,
            'message' => 'Félicitations ! Vous avez gagné l\'enchère pour la PlayStation 5 avec une offre de 5 200 MAD.',
            'date_envoi' => Carbon::now()->subDays(2),
            'type' => 'VICTOIRE',
            'lue' => false,
        ]);

        $this->command->info('✅ Test auctions and bids created successfully!');
    }
}