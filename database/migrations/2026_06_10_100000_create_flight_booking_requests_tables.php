<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('flight_booking_requests')) {
            Schema::create('flight_booking_requests', function (Blueprint $table) {
                $table->id();
                $table->string('booking_reference')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users', indexName: 'fb_req_user_fk')->nullOnDelete();
                $table->foreignId('flight_search_id')->nullable()->constrained('flight_searches', indexName: 'fb_req_search_fk')->nullOnDelete();
                $table->foreignId('flight_search_result_id')->nullable()->constrained('flight_search_results', indexName: 'fb_req_search_result_fk')->nullOnDelete();

                $table->string('trip_type', 20);
                $table->string('origin');
                $table->string('destination');
                $table->date('departure_date');
                $table->date('return_date')->nullable();

                $table->unsignedTinyInteger('adults')->default(1);
                $table->unsignedTinyInteger('children')->default(0);
                $table->unsignedTinyInteger('infants')->default(0);
                $table->string('cabin_class', 30);

                $table->json('selected_flight')->nullable();

                $table->string('contact_name');
                $table->string('email');
                $table->string('phone');
                $table->string('whatsapp')->nullable();
                $table->string('country')->nullable();

                $table->string('billing_address')->nullable();
                $table->string('billing_city')->nullable();
                $table->string('billing_country')->nullable();
                $table->string('postal_code')->nullable();

                $table->json('special_assistance')->nullable();
                $table->text('special_requests')->nullable();

                $table->decimal('estimated_price', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');

                $table->string('status', 30)->default('new');
                $table->text('agent_notes')->nullable();
                $table->string('ticket_path')->nullable();
                $table->string('invoice_path')->nullable();

                $table->timestamps();
            });
        }

        if (! Schema::hasTable('flight_booking_passengers')) {
            Schema::create('flight_booking_passengers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flight_booking_request_id')->constrained('flight_booking_requests', indexName: 'fb_passengers_request_fk')->cascadeOnDelete();
                $table->string('passenger_type', 10);
                $table->string('title', 10);
                $table->string('first_name');
                $table->string('last_name');
                $table->date('date_of_birth');
                $table->string('nationality')->nullable();
                $table->string('passport_number')->nullable();
                $table->date('passport_expiry')->nullable();
                $table->string('passport_country')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('flight_booking_request_status_histories')) {
            Schema::create('flight_booking_request_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flight_booking_request_id')->constrained('flight_booking_requests', indexName: 'fb_status_hist_request_fk')->cascadeOnDelete();
                $table->string('status', 30);
                $table->text('notes')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users', indexName: 'fb_status_hist_user_fk')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_booking_request_status_histories');
        Schema::dropIfExists('flight_booking_passengers');
        Schema::dropIfExists('flight_booking_requests');
    }
};
