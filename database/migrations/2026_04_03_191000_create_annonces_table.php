<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendeur_id')
                ->constrained('vendeurs')
                ->onDelete('cascade');

            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('cascade');

            $table->string('titre');
            $table->text('description')->nullable();

            $table->decimal('prix_depart', 10, 2);
            $table->decimal('prix_final', 10, 2)->nullable(); // Prix final après clôture

            $table->enum('statut', [
                'EN_ATTENTE',    // En attente validation admin
                'ACTIVE',        // Enchère en cours
                'CLOTUREE',      // Terminée
                'BLOQUEE',       // Bloquée par admin
                'ANNULEE'        // Annulée par vendeur
            ])->default('EN_ATTENTE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};