<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            if (! Schema::hasColumn('flights', 'flight_number')) {
                $table->string('flight_number', 20)->nullable()->after('airline');
            }
            if (! Schema::hasColumn('flights', 'refundable_type')) {
                $table->string('refundable_type', 30)->default('as_per_rules')->after('stops');
            }
            if (! Schema::hasColumn('flights', 'baggage_kg')) {
                $table->unsignedSmallInteger('baggage_kg')->default(23)->after('refundable_type');
            }
        });

        Schema::table('flight_search_results', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_search_results', 'flight_id')) {
                $table->foreignId('flight_id')->nullable()->after('flight_search_id')->constrained('flights')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_search_results', function (Blueprint $table) {
            if (Schema::hasColumn('flight_search_results', 'flight_id')) {
                $table->dropConstrainedForeignId('flight_id');
            }
        });

        Schema::table('flights', function (Blueprint $table) {
            foreach (['flight_number', 'refundable_type', 'baggage_kg'] as $column) {
                if (Schema::hasColumn('flights', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
