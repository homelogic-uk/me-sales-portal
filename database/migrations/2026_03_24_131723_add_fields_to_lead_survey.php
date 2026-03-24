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
        Schema::table('lead_survey', function (Blueprint $table) {
            $table->longText('client_signature')->after('answers');
            $table->longText('rep_signature')->after('client_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_survey', function (Blueprint $table) {
            $table->dropColumn('client_signature');
            $table->dropColumn('rep_signature');
        });
    }
};
