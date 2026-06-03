<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_booking_passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_booking_passengers', 'passport_file')) {
                $table->string('passport_file')->nullable()->after('passport_country');
            }
        });

        Schema::table('flight_booking_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_booking_requests', 'contact_passenger_index')) {
                $table->unsignedTinyInteger('contact_passenger_index')->default(0)->after('contact_name');
            }
            if (! Schema::hasColumn('flight_booking_requests', 'preferred_airline')) {
                $table->string('preferred_airline')->nullable()->after('special_requests');
            }
            if (! Schema::hasColumn('flight_booking_requests', 'seat_preference')) {
                $table->string('seat_preference', 30)->nullable()->after('preferred_airline');
            }
            if (! Schema::hasColumn('flight_booking_requests', 'meal_preference')) {
                $table->string('meal_preference', 30)->nullable()->after('seat_preference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_booking_passengers', function (Blueprint $table) {
            if (Schema::hasColumn('flight_booking_passengers', 'passport_file')) {
                $table->dropColumn('passport_file');
            }
        });

        Schema::table('flight_booking_requests', function (Blueprint $table) {
            foreach (['contact_passenger_index', 'preferred_airline', 'seat_preference', 'meal_preference'] as $column) {
                if (Schema::hasColumn('flight_booking_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
