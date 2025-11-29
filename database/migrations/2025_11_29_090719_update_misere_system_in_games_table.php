<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer les anciennes colonnes misere de la table games
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['misere', 'misere_team']);
        });
        
        // Créer une table pour les misères individuelles
        Schema::create('game_miseres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['tetes', 'atouts']); // Type de misère
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_miseres');
        
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('misere')->default(false);
            $table->enum('misere_team', ['attaque', 'defense'])->nullable();
        });
    }
};
