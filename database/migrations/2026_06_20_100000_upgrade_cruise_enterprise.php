<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cruises', function (Blueprint $table) {
            if (! Schema::hasColumn('cruises', 'cruise_code')) {
                $table->string('cruise_code')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('cruises', 'cruise_line')) {
                $table->string('cruise_line')->nullable()->after('name');
            }
            if (! Schema::hasColumn('cruises', 'ship_class')) {
                $table->string('ship_class', 40)->nullable()->after('ship_name');
            }
            if (! Schema::hasColumn('cruises', 'ship_capacity')) {
                $table->unsignedInteger('ship_capacity')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'launch_year')) {
                $table->unsignedSmallInteger('launch_year')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'cruise_region')) {
                $table->string('cruise_region', 40)->nullable();
            }
            if (! Schema::hasColumn('cruises', 'arrival_port')) {
                $table->string('arrival_port')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'duration_nights')) {
                $table->unsignedSmallInteger('duration_nights')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'short_description')) {
                $table->text('short_description')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'brochure_pdf')) {
                $table->string('brochure_pdf')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'deck_plan_pdf')) {
                $table->string('deck_plan_pdf')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'terms_pdf')) {
                $table->string('terms_pdf')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'included_services')) {
                $table->json('included_services')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'not_included_services')) {
                $table->json('not_included_services')->nullable();
            }
            if (! Schema::hasColumn('cruises', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0);
            }
            if (! Schema::hasColumn('cruises', 'featured')) {
                $table->boolean('featured')->default(false);
            }
            if (! Schema::hasColumn('cruises', 'status')) {
                $table->boolean('status')->default(true);
            }
        });

        if (! Schema::hasTable('cruise_itinerary_days')) {
            Schema::create('cruise_itinerary_days', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_id')->constrained('cruises')->cascadeOnDelete();
                $table->unsignedSmallInteger('day_number');
                $table->string('port_name');
                $table->string('arrival_time')->nullable();
                $table->string('departure_time')->nullable();
                $table->text('description')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cruise_cabins')) {
            Schema::create('cruise_cabins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_id')->constrained('cruises')->cascadeOnDelete();
                $table->string('cabin_type', 40);
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedTinyInteger('max_adults')->default(2);
                $table->unsignedTinyInteger('max_children')->default(0);
                $table->unsignedTinyInteger('max_occupancy')->default(2);
                $table->string('size')->nullable();
                $table->decimal('starting_price', 12, 2)->nullable();
                $table->boolean('featured')->default(false);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cruise_gallery_images')) {
            Schema::create('cruise_gallery_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_id')->constrained('cruises')->cascadeOnDelete();
                $table->string('path');
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('cruise_booking_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('cruise_booking_requests', 'cruise_cabin_id')) {
                $table->foreignId('cruise_cabin_id')->nullable()->after('cruise_id')->constrained('cruise_cabins')->nullOnDelete();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'contact_title')) {
                $table->string('contact_title', 10)->nullable()->after('status');
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'city')) {
                $table->string('city')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'bed_preference')) {
                $table->string('bed_preference', 40)->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'celebration')) {
                $table->string('celebration', 40)->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'mobility_assistance')) {
                $table->boolean('mobility_assistance')->default(false);
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'special_needs')) {
                $table->boolean('special_needs')->default(false);
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'emergency_contact_email')) {
                $table->string('emergency_contact_email')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'special_requests')) {
                $table->text('special_requests')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'privacy_accepted')) {
                $table->boolean('privacy_accepted')->default(false);
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'terms_accepted_at')) {
                $table->timestamp('terms_accepted_at')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'boarding_instructions_path')) {
                $table->string('boarding_instructions_path')->nullable();
            }
            if (! Schema::hasColumn('cruise_booking_requests', 'excursion_details_path')) {
                $table->string('excursion_details_path')->nullable();
            }
        });

        Schema::table('cruise_booking_passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('cruise_booking_passengers', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('cruise_booking_request_id');
            }
        });

        if (! Schema::hasTable('cruise_request_documents')) {
            Schema::create('cruise_request_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_booking_request_id')->constrained('cruise_booking_requests')->cascadeOnDelete();
                $table->string('document_type', 60);
                $table->string('disk', 20)->default('local');
                $table->string('path');
                $table->string('original_name')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        $statusMap = [
            'contacted' => 'under_review',
            'awaiting_customer' => 'waiting_documents',
            'confirmed' => 'accepted',
        ];

        foreach ($statusMap as $from => $to) {
            DB::table('cruise_booking_requests')->where('status', $from)->update(['status' => $to]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cruise_request_documents');
        if (Schema::hasColumn('cruise_booking_passengers', 'is_primary')) {
            Schema::table('cruise_booking_passengers', fn (Blueprint $t) => $t->dropColumn('is_primary'));
        }
        Schema::table('cruise_booking_requests', function (Blueprint $t) {
            if (Schema::hasColumn('cruise_booking_requests', 'cruise_cabin_id')) {
                $t->dropConstrainedForeignId('cruise_cabin_id');
            }
            foreach ([
                'contact_title', 'address', 'city', 'bed_preference', 'celebration',
                'mobility_assistance', 'special_needs', 'emergency_contact_name',
                'emergency_contact_relationship', 'emergency_contact_phone', 'emergency_contact_email',
                'special_requests', 'privacy_accepted', 'terms_accepted_at',
                'boarding_instructions_path', 'excursion_details_path',
            ] as $col) {
                if (Schema::hasColumn('cruise_booking_requests', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
        Schema::dropIfExists('cruise_gallery_images');
        Schema::dropIfExists('cruise_cabins');
        Schema::dropIfExists('cruise_itinerary_days');
        Schema::table('cruises', function (Blueprint $t) {
            foreach ([
                'cruise_code', 'cruise_line', 'ship_class', 'ship_capacity', 'launch_year',
                'cruise_region', 'arrival_port', 'duration_nights', 'short_description',
                'brochure_pdf', 'deck_plan_pdf', 'terms_pdf', 'included_services',
                'not_included_services', 'sort_order',
            ] as $col) {
                if (Schema::hasColumn('cruises', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
