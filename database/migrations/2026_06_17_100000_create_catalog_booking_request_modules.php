<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cruise_booking_requests')) {
            Schema::create('cruise_booking_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('cruise_id')->constrained('cruises')->cascadeOnDelete();
                $table->string('status', 30)->default('new');
                $table->date('departure_date');
                $table->date('return_date')->nullable();
                $table->string('cabin_type')->nullable();
                $table->unsignedTinyInteger('adults')->default(1);
                $table->unsignedTinyInteger('children')->default(0);
                $table->unsignedTinyInteger('infants')->default(0);
                $table->string('contact_name');
                $table->string('contact_email');
                $table->string('contact_phone');
                $table->string('contact_whatsapp')->nullable();
                $table->string('country')->nullable();
                $table->string('dining_preference')->nullable();
                $table->text('dietary_requirements')->nullable();
                $table->boolean('wheelchair_assistance')->default(false);
                $table->text('medical_conditions')->nullable();
                $table->text('additional_notes')->nullable();
                $table->json('selected_cruise')->nullable();
                $table->decimal('estimated_amount', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');
                $table->text('agent_notes')->nullable();
                $table->string('voucher_path')->nullable();
                $table->string('invoice_path')->nullable();
                $table->string('ticket_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('cruise_booking_passengers')) {
            Schema::create('cruise_booking_passengers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cruise_booking_request_id');
                $table->foreign('cruise_booking_request_id', 'cr_passengers_request_fk')
                    ->references('id')->on('cruise_booking_requests')->cascadeOnDelete();
                $table->string('title', 10)->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('passenger_type', 10)->default('adult');
                $table->string('gender', 20)->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('nationality')->nullable();
                $table->string('passport_number')->nullable();
                $table->date('passport_expiry')->nullable();
                $table->string('passport_country')->nullable();
                $table->string('passport_file')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cruise_booking_request_status_histories')) {
            Schema::create('cruise_booking_request_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cruise_booking_request_id');
                $table->foreign('cruise_booking_request_id', 'cr_status_hist_request_fk')
                    ->references('id')->on('cruise_booking_requests')->cascadeOnDelete();
                $table->string('old_status', 30)->nullable();
                $table->string('new_status', 30);
                $table->text('notes')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('car_booking_requests')) {
            Schema::create('car_booking_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('rental_car_id')->constrained('rental_cars')->cascadeOnDelete();
                $table->string('status', 30)->default('new');
                $table->string('pickup_location');
                $table->string('dropoff_location')->nullable();
                $table->date('pickup_date');
                $table->string('pickup_time')->nullable();
                $table->date('return_date');
                $table->string('return_time')->nullable();
                $table->string('contact_email');
                $table->string('contact_phone');
                $table->string('contact_whatsapp')->nullable();
                $table->text('address')->nullable();
                $table->string('country')->nullable();
                $table->boolean('extra_gps')->default(false);
                $table->boolean('extra_child_seat')->default(false);
                $table->boolean('extra_additional_driver')->default(false);
                $table->string('insurance_option')->nullable();
                $table->text('notes')->nullable();
                $table->json('selected_vehicle')->nullable();
                $table->decimal('estimated_amount', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');
                $table->text('agent_notes')->nullable();
                $table->string('voucher_path')->nullable();
                $table->string('invoice_path')->nullable();
                $table->string('rental_agreement_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('car_booking_drivers')) {
            Schema::create('car_booking_drivers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_booking_request_id');
                $table->foreign('car_booking_request_id', 'car_drivers_request_fk')
                    ->references('id')->on('car_booking_requests')->cascadeOnDelete();
                $table->string('title', 10)->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->date('date_of_birth')->nullable();
                $table->string('nationality')->nullable();
                $table->string('license_number')->nullable();
                $table->date('license_expiry')->nullable();
                $table->string('passport_number')->nullable();
                $table->string('license_file')->nullable();
                $table->string('passport_file')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('car_booking_request_status_histories')) {
            Schema::create('car_booking_request_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_booking_request_id');
                $table->foreign('car_booking_request_id', 'car_status_hist_request_fk')
                    ->references('id')->on('car_booking_requests')->cascadeOnDelete();
                $table->string('old_status', 30)->nullable();
                $table->string('new_status', 30);
                $table->text('notes')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('insurance_booking_requests')) {
            Schema::create('insurance_booking_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('travel_insurance_id')->constrained('travel_insurances')->cascadeOnDelete();
                $table->string('status', 30)->default('new');
                $table->string('destination')->nullable();
                $table->date('travel_start');
                $table->date('travel_end');
                $table->string('coverage_type')->nullable();
                $table->boolean('pre_existing_conditions')->default(false);
                $table->text('medical_notes')->nullable();
                $table->string('contact_email');
                $table->string('contact_phone');
                $table->string('contact_whatsapp')->nullable();
                $table->text('address')->nullable();
                $table->string('country')->nullable();
                $table->json('selected_policy')->nullable();
                $table->decimal('estimated_amount', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');
                $table->text('agent_notes')->nullable();
                $table->string('policy_path')->nullable();
                $table->string('invoice_path')->nullable();
                $table->string('coverage_document_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('insurance_booking_travelers')) {
            Schema::create('insurance_booking_travelers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('insurance_booking_request_id');
                $table->foreign('insurance_booking_request_id', 'ins_travelers_request_fk')
                    ->references('id')->on('insurance_booking_requests')->cascadeOnDelete();
                $table->string('title', 10)->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->date('date_of_birth')->nullable();
                $table->string('nationality')->nullable();
                $table->string('passport_number')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('insurance_booking_request_status_histories')) {
            Schema::create('insurance_booking_request_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('insurance_booking_request_id');
                $table->foreign('insurance_booking_request_id', 'ins_status_hist_request_fk')
                    ->references('id')->on('insurance_booking_requests')->cascadeOnDelete();
                $table->string('old_status', 30)->nullable();
                $table->string('new_status', 30);
                $table->text('notes')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('quotes')) {
            Schema::table('quotes', function (Blueprint $table) {
                if (! Schema::hasColumn('quotes', 'cruise_booking_request_id')) {
                    $table->unsignedBigInteger('cruise_booking_request_id')->nullable()->after('hotel_booking_request_id');
                    $table->foreign('cruise_booking_request_id', 'quotes_cruise_booking_request_fk')
                        ->references('id')->on('cruise_booking_requests')->nullOnDelete();
                }
                if (! Schema::hasColumn('quotes', 'car_booking_request_id')) {
                    $table->unsignedBigInteger('car_booking_request_id')->nullable()->after('cruise_booking_request_id');
                    $table->foreign('car_booking_request_id', 'quotes_car_booking_request_fk')
                        ->references('id')->on('car_booking_requests')->nullOnDelete();
                }
                if (! Schema::hasColumn('quotes', 'insurance_booking_request_id')) {
                    $table->unsignedBigInteger('insurance_booking_request_id')->nullable()->after('car_booking_request_id');
                    $table->foreign('insurance_booking_request_id', 'quotes_insurance_booking_request_fk')
                        ->references('id')->on('insurance_booking_requests')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('quotes')) {
            Schema::table('quotes', function (Blueprint $table) {
                foreach (['quotes_insurance_booking_request_fk', 'quotes_car_booking_request_fk', 'quotes_cruise_booking_request_fk'] as $fk) {
                    if (Schema::hasColumn('quotes', str_replace('quotes_', '', str_replace('_fk', '', $fk)))) {
                        try {
                            $table->dropForeign($fk);
                        } catch (\Throwable) {
                        }
                    }
                }
                foreach (['insurance_booking_request_id', 'car_booking_request_id', 'cruise_booking_request_id'] as $col) {
                    if (Schema::hasColumn('quotes', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        Schema::dropIfExists('insurance_booking_request_status_histories');
        Schema::dropIfExists('insurance_booking_travelers');
        Schema::dropIfExists('insurance_booking_requests');
        Schema::dropIfExists('car_booking_request_status_histories');
        Schema::dropIfExists('car_booking_drivers');
        Schema::dropIfExists('car_booking_requests');
        Schema::dropIfExists('cruise_booking_request_status_histories');
        Schema::dropIfExists('cruise_booking_passengers');
        Schema::dropIfExists('cruise_booking_requests');
    }
};
