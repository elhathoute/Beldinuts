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
        Schema::table('products', function (Blueprint $table) {
            // Prix pour vente particulière (par pièce)
            $table->decimal('retail_price', 10, 2)->nullable()->after('price_per_gram');
            // Prix pour vente en gros (par pièce)
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('retail_price');
            // Poids par pièce en grammes
            $table->integer('weight_per_piece')->default(40)->after('wholesale_price');
            // Unité de vente: 'piece' ou 'gram'
            $table->string('unit', 10)->default('piece')->after('weight_per_piece');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['retail_price', 'wholesale_price', 'weight_per_piece', 'unit']);
        });
    }
};
