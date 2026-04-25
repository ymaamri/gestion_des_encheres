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
            $table->decimal('prix_actuel', 10, 2)->nullable();   // current highest bid
            $table->decimal('montant_mise', 10, 2)->default(1); // minimum bid increment
            $table->decimal('prix_final', 10, 2)->nullable();    // final price after closing

            // Auction time window (directly on the annonce)
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();

            $table->enum('statut', [
                'EN_ATTENTE',    // pending admin validation
                'ACTIVE',        // auction in progress
                'CLOTUREE',      // finished
                'BLOQUEE',       // blocked by admin
                'ANNULEE'        // cancelled by seller
            ])->default('EN_ATTENTE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};