<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_search_results', function (Blueprint $table) {
            $table->unique(
                ['flight_search_id', 'external_offer_id'],
                'flight_search_results_search_offer_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('flight_search_results', function (Blueprint $table) {
            $table->dropUnique('flight_search_results_search_offer_unique');
        });
    }
};
