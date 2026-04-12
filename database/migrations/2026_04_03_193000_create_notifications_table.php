<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');

            $table->text('message');
            $table->dateTime('date_envoi')->nullable();

            $table->enum('type', [
                'SURENCHERE',
                'VICTOIRE',
                'FIN_ENCHERE',
                'VALIDATION'
            ]);

            $table->boolean('lue')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};