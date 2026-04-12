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

            $table->double('prix_depart');
            $table->double('prix_actuel')->nullable();
            $table->double('montant_mise')->default(0);

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            $table->enum('statut', [
                'EN_ATTENTE',
                'ACTIVE',
                'CLOTUREE',
                'BLOQUEE',
                'ANNULEE'
            ])->default('EN_ATTENTE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};