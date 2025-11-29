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
        Schema::table('games', function (Blueprint $table) {
            // Remplacer bonus_points par des colonnes détaillées
            $table->boolean('petit_au_bout')->default(false)->after('oudlers');
            $table->string('petit_au_bout_team')->nullable()->after('petit_au_bout'); // attaque ou defense
            $table->enum('poignee', ['aucune', 'simple', 'double', 'triple'])->default('aucune')->after('petit_au_bout_team');
            $table->string('poignee_team')->nullable()->after('poignee'); // attaque ou defense
            $table->enum('chelem', ['aucun', 'annonce_reussi', 'annonce_chute', 'non_annonce'])->default('aucun')->after('poignee_team');
            $table->integer('nombre_joueurs')->default(4)->after('played_at'); // 4 ou 5 joueurs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['petit_au_bout', 'petit_au_bout_team', 'poignee', 'poignee_team', 'chelem', 'nombre_joueurs']);
        });
    }
};
