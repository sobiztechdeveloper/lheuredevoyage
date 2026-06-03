<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_booking_passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_booking_passengers', 'gender')) {
                $table->string('gender', 20)->nullable()->after('last_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_booking_passengers', function (Blueprint $table) {
            if (Schema::hasColumn('flight_booking_passengers', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
