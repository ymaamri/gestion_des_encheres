<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('encheres', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annonce_id')
                ->nullable()
                ->constrained('annonces')
                ->onDelete('set null');
            $table->foreignId('client_id')
                ->nullable()
                ->constrained('clients')
                ->onDelete('set null');

            // Montant de la mise
            $table->decimal('montant', 10, 2);

            // Date de la mise
            $table->timestamp('date_mise')->useCurrent();

            $table->timestamps();

            // Un client ne peut pas avoir deux mises avec le même montant sur la même annonce
            $table->unique(['annonce_id', 'client_id', 'montant']);

            // Index pour optimiser les recherches
            $table->index(['annonce_id', 'date_mise']);
            $table->index(['annonce_id', 'montant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encheres');
    }
};