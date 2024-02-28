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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idClient');
            $table->double('total_amount');
            $table->date('date_commande');
            $table->unsignedBigInteger('idProduct');
            $table->timestamps();

            $table->foreign('idClient')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('idProduct')->references('id')->on('produits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
