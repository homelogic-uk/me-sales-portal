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
        Schema::table('product_option', function (Blueprint $table) {
            $table->enum('is_quantity', ['Y', 'N'])->default('N')->after('base_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_option', function (Blueprint $table) {
            $table->dropColumn('is_quantity');
        });
    }
};
