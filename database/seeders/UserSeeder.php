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
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@auction.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $this->command->info('Admin user created.');

        // Client User (Buyer)
        $clientUser = User::firstOrCreate(
            ['email' => 'client@auction.com'],
            [
                'nom' => 'John',
                'prenom' => 'Doe',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
        Client::firstOrCreate(
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
        $this->command->info('Client user created.');

        // Seller User
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
        Vendeur::firstOrCreate(
            ['client_id' => $sellerClient->id],
            [
                'siret' => '12345678901234',
                'note_moyenne' => 4.5,
                'nombre_ventes' => 25,
            ]
        );
        $this->command->info('Seller user created.');

        // Test Client
        $testUser = User::firstOrCreate(
            ['email' => 'test@auction.com'],
            [
                'nom' => 'Test',
                'prenom' => 'User',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
        Client::firstOrCreate(
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
        $this->command->info('Test user created.');
    }
}