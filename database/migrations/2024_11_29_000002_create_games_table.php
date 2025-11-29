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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->dateTime('played_at');
            $table->string('contract_type'); // petite, garde, garde_sans, garde_contre
            $table->boolean('contract_success');
            $table->integer('points'); // Points du preneur
            $table->integer('oudlers'); // Nombre de bouts (0-3)
            $table->integer('bonus_points')->default(0); // Poignée, chelem, petit au bout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
