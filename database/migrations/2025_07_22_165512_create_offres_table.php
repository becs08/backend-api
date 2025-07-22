<?php
// database/migrations/xxxx_create_offres_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->string('categorie');
            $table->decimal('prix_min', 10, 2)->nullable();
            $table->decimal('prix_max', 10, 2)->nullable();
            $table->string('localisation');
            $table->enum('type_offre', ['service', 'produit', 'formation']);
            $table->enum('statut', ['active', 'suspendue', 'expiree'])->default('active');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('images')->nullable();
            $table->timestamp('date_expiration')->nullable();
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
