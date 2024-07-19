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
        Schema::create('pivot_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCommande');
            $table->unsignedBigInteger('idProduct');
            $table->integer('quantity');
            $table->double('totale');
            $table->timestamps();

            $table->foreign('idCommande')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('idProduct')->references('id')->on('produits')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_commandes');
    }
};
