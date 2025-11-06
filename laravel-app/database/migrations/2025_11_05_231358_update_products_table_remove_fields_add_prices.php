<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new columns first
            $table->decimal('price_per_gram_retail', 10, 2)->after('price_per_gram');
            $table->decimal('price_per_gram_wholesale', 10, 2)->after('price_per_gram_retail');
        });
        
        // Copy existing price_per_gram to price_per_gram_retail
        DB::statement('UPDATE products SET price_per_gram_retail = price_per_gram');
        
        Schema::table('products', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['price_per_gram', 'retail_price', 'wholesale_price', 'weight_per_piece', 'unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back price_per_gram
            $table->decimal('price_per_gram', 10, 2)->after('description');
        });
        
        // Copy price_per_gram_retail back to price_per_gram
        DB::statement('UPDATE products SET price_per_gram = price_per_gram_retail');
        
        Schema::table('products', function (Blueprint $table) {
            // Add back old columns
            $table->decimal('retail_price', 10, 2)->nullable()->after('price_per_gram');
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('retail_price');
            $table->integer('weight_per_piece')->default(40)->after('wholesale_price');
            $table->string('unit', 10)->default('piece')->after('weight_per_piece');
            
            // Drop new columns
            $table->dropColumn(['price_per_gram_retail', 'price_per_gram_wholesale']);
        });
    }
};
