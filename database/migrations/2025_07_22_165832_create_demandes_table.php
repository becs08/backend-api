<?php
// database/migrations/xxxx_create_demandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->decimal('prix_propose', 10, 2)->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee', 'annulee'])->default('en_attente');
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->foreignId('demandeur_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_reponse')->nullable();
            $table->text('message_reponse')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
