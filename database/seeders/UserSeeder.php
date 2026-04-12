<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Vendeur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@auction.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('Admin user created: admin@auction.com / password');

        // Create Client User (Buyer)
        $clientUser = User::create([
            'nom' => 'John',
            'prenom' => 'Doe',
            'email' => 'client@auction.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Create Client record for the buyer
        $client = Client::create([
            'user_id' => $clientUser->id,
            'nom' => 'John',
            'prenom' => 'Doe',
            'telephone' => '0612345678',
            'adresse_livraison' => '123 Main Street, Casablanca',
            'solde' => 10000.00,
            'statut' => 'ACTIF',
        ]);

        $this->command->info('Client user created: client@auction.com / password');

        // Create Seller User
        $sellerUser = User::create([
            'nom' => 'Jane',
            'prenom' => 'Smith',
            'email' => 'seller@auction.com',
            'password' => Hash::make('password'),
            'role' => 'vendeur',
        ]);

        // Create Client record for the seller
        $sellerClient = Client::create([
            'user_id' => $sellerUser->id,
            'nom' => 'Jane',
            'prenom' => 'Smith',
            'telephone' => '0698765432',
            'adresse_livraison' => '456 Market Street, Rabat',
            'solde' => 5000.00,
            'statut' => 'ACTIF',
        ]);

        // Create Vendeur record linked to the seller's client
        Vendeur::create([
            'client_id' => $sellerClient->id,
            'siret' => '12345678901234',
            'note_moyenne' => 4.5,
            'nombre_ventes' => 25,
        ]);

        $this->command->info('Seller user created: seller@auction.com / password');

        // Create additional test client
        $testUser = User::create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@auction.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        Client::create([
            'user_id' => $testUser->id,
            'nom' => 'Test',
            'prenom' => 'User',
            'telephone' => '0678945612',
            'adresse_livraison' => '789 Test Avenue, Tangier',
            'solde' => 5000.00,
            'statut' => 'ACTIF',
        ]);

        $this->command->info('Test user created: test@auction.com / password');
        $this->command->info('All users seeded successfully!');
    }
}