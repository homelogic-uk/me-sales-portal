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
        Schema::create('lead_quote', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('product_id');
            $table->longText('extras')->default('{}');
            $table->decimal('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_quote');
    }
};
