<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sous_categorie_id')
                ->nullable()
                ->constrained('sous_categories')
                ->onDelete('cascade');

            // Simple column – no foreign key constraint
            $table->unsignedBigInteger('vendeur_id')->nullable();

            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();

            $table->enum('etat', [
                'NEUF',
                'TRES_BON_ETAT',
                'BON_ETAT',
                'ACCEPTABLE'
            ])->default('BON_ETAT');

            $table->json('photos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};