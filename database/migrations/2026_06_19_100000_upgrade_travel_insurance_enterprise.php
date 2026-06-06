<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_insurances', function (Blueprint $table) {
            $table->string('insurance_company')->nullable()->after('slug');
            $table->string('plan_code')->nullable()->after('name');
            $table->string('plan_type', 40)->nullable()->after('plan_code');
            $table->string('logo')->nullable()->after('featured_image');
            $table->string('brochure_pdf')->nullable();
            $table->string('policy_wording_pdf')->nullable();
            $table->string('terms_pdf')->nullable();
            $table->decimal('medical_coverage_amount', 14, 2)->nullable();
            $table->decimal('emergency_medical_expenses', 14, 2)->nullable();
            $table->decimal('hospitalization', 14, 2)->nullable();
            $table->decimal('emergency_evacuation', 14, 2)->nullable();
            $table->decimal('medical_repatriation', 14, 2)->nullable();
            $table->decimal('trip_cancellation', 14, 2)->nullable();
            $table->decimal('trip_interruption', 14, 2)->nullable();
            $table->decimal('flight_delay', 14, 2)->nullable();
            $table->decimal('baggage_delay', 14, 2)->nullable();
            $table->decimal('baggage_loss', 14, 2)->nullable();
            $table->decimal('missed_connection', 14, 2)->nullable();
            $table->decimal('personal_liability', 14, 2)->nullable();
            $table->decimal('legal_assistance', 14, 2)->nullable();
            $table->boolean('covid_coverage')->default(false);
            $table->boolean('adventure_sports_coverage')->default(false);
            $table->boolean('winter_sports_coverage')->default(false);
            $table->string('coverage_currency', 3)->default('CHF');
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->text('nationality_restrictions')->nullable();
            $table->text('covered_regions')->nullable();
            $table->text('covered_countries')->nullable();
            $table->boolean('schengen_covered')->default(false);
            $table->boolean('worldwide_covered')->default(false);
            $table->boolean('student_eligible')->default(false);
            $table->boolean('family_eligible')->default(false);
            $table->decimal('base_premium', 12, 2)->nullable();
            $table->string('premium_currency', 3)->default('CHF');
            $table->boolean('price_per_person')->default(true);
            $table->boolean('price_per_family')->default(false);
            $table->decimal('annual_price', 12, 2)->nullable();
            $table->decimal('student_price', 12, 2)->nullable();
            $table->decimal('child_price', 12, 2)->nullable();
            $table->decimal('senior_price', 12, 2)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('insurance_plan_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_insurance_id')->constrained('travel_insurances')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('insurance_plan_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_insurance_id')->constrained('travel_insurances')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('insurance_plan_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_insurance_id')->constrained('travel_insurances')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('insurance_booking_requests', function (Blueprint $table) {
            $table->string('destination_country')->nullable()->after('destination');
            $table->string('purpose_of_travel', 40)->nullable()->after('destination_country');
            $table->string('city')->nullable()->after('country');
            $table->boolean('pregnancy')->default(false)->after('pre_existing_conditions');
            $table->boolean('adventure_sports')->default(false);
            $table->boolean('winter_sports')->default(false);
            $table->boolean('long_stay')->default(false);
            $table->text('additional_notes')->nullable();
            $table->boolean('privacy_accepted')->default(false);
            $table->timestamp('terms_accepted_at')->nullable();
            $table->string('claim_instructions_path')->nullable();
        });

        Schema::table('insurance_booking_travelers', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('insurance_booking_request_id');
            $table->string('relationship')->nullable()->after('passport_number');
            $table->date('passport_expiry')->nullable()->after('passport_number');
        });

        Schema::create('insurance_request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_booking_request_id')->constrained('insurance_booking_requests', indexName: 'ins_req_docs_request_fk')->cascadeOnDelete();
            $table->string('document_type', 40);
            $table->string('disk', 20)->default('local');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('insurance_cms_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('block_key', 40)->unique();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $this->migrateLegacyStatuses();
        $this->seedCmsBlocks();
    }

    protected function seedCmsBlocks(): void
    {
        $blocks = [
            'faq' => ['Insurance FAQs', '<p>Common questions about travel insurance coverage and claims.</p>'],
            'terms' => ['Insurance Terms', '<p>General insurance terms applicable to quote requests.</p>'],
            'claims' => ['Claim Instructions', '<p>How to notify us and submit a claim after an insured event.</p>'],
            'emergency' => ['Emergency Contacts', '<p>24/7 emergency assistance numbers for insured travelers.</p>'],
            'advice' => ['Travel Advice', '<p>Health and safety recommendations before you travel.</p>'],
        ];

        foreach ($blocks as $key => [$title, $content]) {
            DB::table('insurance_cms_blocks')->updateOrInsert(
                ['block_key' => $key],
                ['title' => $title, 'content' => $content, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_cms_blocks');
        Schema::dropIfExists('insurance_request_documents');
        Schema::dropIfExists('insurance_plan_gallery_images');
        Schema::dropIfExists('insurance_plan_exclusions');
        Schema::dropIfExists('insurance_plan_benefits');

        Schema::table('insurance_booking_travelers', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'relationship', 'passport_expiry']);
        });

        Schema::table('insurance_booking_requests', function (Blueprint $table) {
            $table->dropColumn([
                'destination_country', 'purpose_of_travel', 'city', 'pregnancy',
                'adventure_sports', 'winter_sports', 'long_stay', 'additional_notes',
                'privacy_accepted', 'terms_accepted_at', 'claim_instructions_path',
            ]);
        });

        Schema::table('travel_insurances', function (Blueprint $table) {
            $table->dropColumn([
                'insurance_company', 'plan_code', 'plan_type', 'logo',
                'brochure_pdf', 'policy_wording_pdf', 'terms_pdf',
                'medical_coverage_amount', 'emergency_medical_expenses', 'hospitalization',
                'emergency_evacuation', 'medical_repatriation', 'trip_cancellation',
                'trip_interruption', 'flight_delay', 'baggage_delay', 'baggage_loss',
                'missed_connection', 'personal_liability', 'legal_assistance',
                'covid_coverage', 'adventure_sports_coverage', 'winter_sports_coverage',
                'coverage_currency', 'min_age', 'max_age', 'nationality_restrictions',
                'covered_regions', 'covered_countries', 'schengen_covered', 'worldwide_covered',
                'student_eligible', 'family_eligible', 'base_premium', 'premium_currency',
                'price_per_person', 'price_per_family', 'annual_price', 'student_price',
                'child_price', 'senior_price', 'sort_order',
            ]);
        });
    }

    protected function migrateLegacyStatuses(): void
    {
        $map = [
            'contacted' => 'under_review',
            'awaiting_customer' => 'waiting_customer_documents',
            'confirmed' => 'accepted',
            'policy_sent' => 'policy_issued',
        ];

        foreach ($map as $from => $to) {
            DB::table('insurance_booking_requests')->where('status', $from)->update(['status' => $to]);
            DB::table('insurance_booking_request_status_histories')->where('old_status', $from)->update(['old_status' => $to]);
            DB::table('insurance_booking_request_status_histories')->where('new_status', $from)->update(['new_status' => $to]);
        }
    }
};
