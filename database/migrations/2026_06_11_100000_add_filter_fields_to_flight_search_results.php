<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_search_results', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_search_results', 'refundable_type')) {
                $table->string('refundable_type', 30)->default('as_per_rules')->after('currency');
            }
            if (! Schema::hasColumn('flight_search_results', 'baggage_kg')) {
                $table->unsignedSmallInteger('baggage_kg')->default(20)->after('refundable_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_search_results', function (Blueprint $table) {
            if (Schema::hasColumn('flight_search_results', 'baggage_kg')) {
                $table->dropColumn('baggage_kg');
            }
            if (Schema::hasColumn('flight_search_results', 'refundable_type')) {
                $table->dropColumn('refundable_type');
            }
        });
    }
};
