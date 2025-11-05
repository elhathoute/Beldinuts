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
        Schema::table('order_items', function (Blueprint $table) {
            // Type de vente: 'retail' (particulier) ou 'wholesale' (gros)
            $table->string('sale_type', 10)->default('retail')->after('product_id');
            // Quantité en pièces (optionnel, pour les ventes par pièce)
            $table->integer('quantity_pieces')->nullable()->after('quantity_grams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['sale_type', 'quantity_pieces']);
        });
    }
};
