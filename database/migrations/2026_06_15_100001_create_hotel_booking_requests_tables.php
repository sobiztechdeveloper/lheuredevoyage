<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotel_booking_requests')) {
            Schema::create('hotel_booking_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_id')->nullable()->constrained('hotel_rooms')->nullOnDelete();

                $table->string('status', 30)->default('new');
                $table->date('check_in_date');
                $table->date('check_out_date');
                $table->unsignedTinyInteger('rooms')->default(1);
                $table->unsignedTinyInteger('adults')->default(1);
                $table->unsignedTinyInteger('children')->default(0);
                $table->unsignedTinyInteger('infants')->default(0);

                $table->string('lead_guest_title', 10)->nullable();
                $table->string('lead_guest_name');
                $table->string('lead_guest_email');
                $table->string('lead_guest_phone');
                $table->string('lead_guest_whatsapp')->nullable();
                $table->string('country')->nullable();

                $table->string('bed_preference', 30)->default('no_preference');
                $table->string('smoking_preference', 30)->default('no_preference');
                $table->string('arrival_time')->nullable();

                $table->json('special_request_options')->nullable();
                $table->text('special_requests')->nullable();

                $table->json('selected_hotel')->nullable();
                $table->json('selected_room')->nullable();

                $table->decimal('estimated_amount', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');

                $table->text('agent_notes')->nullable();
                $table->string('voucher_path')->nullable();
                $table->string('invoice_path')->nullable();
                $table->string('transfer_voucher_path')->nullable();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('hotel_booking_guests')) {
            Schema::create('hotel_booking_guests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hotel_booking_request_id');
                $table->foreign('hotel_booking_request_id', 'hb_guests_request_fk')
                    ->references('id')->on('hotel_booking_requests')->cascadeOnDelete();
                $table->string('title', 10)->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('guest_type', 10);
                $table->date('date_of_birth')->nullable();
                $table->string('nationality')->nullable();
                $table->string('passport_number')->nullable();
                $table->date('passport_expiry')->nullable();
                $table->string('passport_file')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('hotel_booking_request_status_histories')) {
            Schema::create('hotel_booking_request_status_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hotel_booking_request_id');
                $table->foreign('hotel_booking_request_id', 'hb_status_hist_request_fk')
                    ->references('id')->on('hotel_booking_requests')->cascadeOnDelete();
                $table->string('old_status', 30)->nullable();
                $table->string('new_status', 30);
                $table->text('notes')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('quotes') && ! Schema::hasColumn('quotes', 'hotel_booking_request_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->unsignedBigInteger('hotel_booking_request_id')->nullable()->after('flight_booking_request_id');
                $table->foreign('hotel_booking_request_id', 'quotes_hotel_booking_request_fk')
                    ->references('id')->on('hotel_booking_requests')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('quotes') && Schema::hasColumn('quotes', 'hotel_booking_request_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->dropForeign('quotes_hotel_booking_request_fk');
                $table->dropColumn('hotel_booking_request_id');
            });
        }

        Schema::dropIfExists('hotel_booking_request_status_histories');
        Schema::dropIfExists('hotel_booking_guests');
        Schema::dropIfExists('hotel_booking_requests');
    }
};
