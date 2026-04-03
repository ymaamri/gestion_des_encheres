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
        // Rename this to: 2026_04_03_192435_create_enchere_table.php
        Schema::create('enchere', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annonce_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->decimal('montant', 10, 2);
            $table->timestamp('date_mise')->useCurrent();

            $table->timestamps();

            // Prevent multiple bids at exact same amount by same client
            $table->unique(['annonce_id', 'client_id', 'montant']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enchere');
    }
};
