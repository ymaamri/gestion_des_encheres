<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendeurs', function (Blueprint $table) {
            $table->id();

            // Relation with users (Breeze)
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Vendeur specific fields
            $table->string('prenom');
            $table->string('siret')->nullable();
            $table->double('note_moyenne')->default(0);
            $table->integer('nombre_ventes')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendeurs');
    }
};