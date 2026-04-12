<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enchere', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annonce_id')
                ->constrained('annonces')
                ->onDelete('cascade');

            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');

            $table->decimal('montant', 10, 2);
            $table->timestamp('date_mise')->useCurrent();

            $table->timestamps();

            $table->unique(['annonce_id', 'client_id', 'montant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enchere');
    }
};