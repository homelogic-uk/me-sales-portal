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
        Schema::create('lead_documents', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('status');
            $table->integer('lead_id');
            $table->enum('provider', ['PANDADOC'])->default('PANDADOC');
            $table->string('document_id');
            $table->enum('uploaded', ['Y', 'N'])->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_documents');
    }
};
