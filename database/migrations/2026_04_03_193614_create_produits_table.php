<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation with categorie
            $table->foreignId('categorie_id')
                ->constrained()
                ->onDelete('cascade');

            // 📌 Core fields
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();

            // 📊 ENUM EtatProduit
            $table->enum('etat', [
                'NEUF',
                'TRES_BON_ETAT',
                'BON_ETAT',
                'ACCEPTABLE'
            ])->default('BON_ETAT');

            // 🖼️ Photos (JSON array)
            $table->json('photos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};