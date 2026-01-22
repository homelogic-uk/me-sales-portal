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
        Schema::create('product_option', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->text('name');
            $table->enum('type', ['RANGE', 'NUMBER', 'RADIO', 'CHECKBOX', 'HIDDEN']);
            $table->text('allowed_values', '');
            $table->integer('min_value')->default(0);
            $table->integer('max_value')->default(0);
            $table->integer('default_value')->nullable()->default(0);
            $table->float('base_cost', 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option');
    }
};
