<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Relation with users
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Client-specific fields
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->nullable();
            $table->string('adresse_livraison')->nullable();

            $table->double('solde')->default(0);

            // ENUM for statut
            $table->enum('statut', ['ACTIF', 'BLOQUE', 'INACTIF'])
                ->default('ACTIF');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};