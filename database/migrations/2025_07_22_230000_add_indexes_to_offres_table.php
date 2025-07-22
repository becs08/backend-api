<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->index('statut');
            $table->index('categorie');
            $table->index('type_offre');
            $table->index('date_expiration');
            $table->index('created_at');
            $table->index(['statut', 'date_expiration']);
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropIndex(['statut']);
            $table->dropIndex(['categorie']);
            $table->dropIndex(['type_offre']);
            $table->dropIndex(['date_expiration']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['statut', 'date_expiration']);
        });
    }
};