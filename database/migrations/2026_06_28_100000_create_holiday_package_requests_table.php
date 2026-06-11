<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holiday_package_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('status', 30)->default('new');
            $table->string('locale', 5)->default('en');

            $table->string('departure_airport')->nullable();
            $table->string('destination');
            $table->date('earliest_departure_date')->nullable();
            $table->date('latest_return_date')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedTinyInteger('adults')->default(2);
            $table->unsignedTinyInteger('children')->default(0);
            $table->json('child_ages')->nullable();

            $table->decimal('budget_amount', 12, 2)->nullable();
            $table->string('budget_currency', 3)->nullable();

            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('country')->nullable();

            $table->json('room_types')->nullable();
            $table->json('board_types')->nullable();

            $table->string('preferred_airline')->nullable();
            $table->string('travel_class', 30)->nullable();
            $table->string('outbound_time_preference', 30)->nullable();
            $table->string('return_time_preference', 30)->nullable();
            $table->boolean('direct_flight_only')->default(false);
            $table->boolean('transfer_allowed')->default(false);
            $table->boolean('rail_and_fly')->default(false);

            $table->string('hotel_category', 30)->nullable();
            $table->string('hotel_recommendation', 10)->nullable();
            $table->string('sea_view', 30)->nullable();
            $table->json('hotel_features')->nullable();
            $table->json('beach_preferences')->nullable();
            $table->json('sports')->nullable();
            $table->json('wellness')->nullable();
            $table->boolean('kids_club')->nullable();
            $table->boolean('babysitting')->nullable();
            $table->json('room_amenities')->nullable();

            $table->text('additional_notes')->nullable();
            $table->text('agent_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday_package_requests');
    }
};
