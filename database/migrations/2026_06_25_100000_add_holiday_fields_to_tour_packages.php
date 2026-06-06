<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->string('country')->nullable()->after('destination');
            $table->string('holiday_type')->nullable()->after('country');
            $table->unsignedSmallInteger('duration_nights')->nullable()->after('duration_days');
            $table->json('included_services')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->dropColumn(['country', 'holiday_type', 'duration_nights', 'included_services']);
        });
    }
};
