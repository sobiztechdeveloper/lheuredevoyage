<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('holiday_package_requests', function (Blueprint $table) {
            $table->json('holiday_types')->nullable()->after('child_ages');
            $table->string('priority', 20)->default('normal')->after('holiday_types');
            $table->string('preferred_contact_method', 20)->default('email')->after('priority');
            $table->timestamp('gdpr_consent_at')->nullable()->after('preferred_contact_method');
        });
    }

    public function down(): void
    {
        Schema::table('holiday_package_requests', function (Blueprint $table) {
            $table->dropColumn([
                'holiday_types',
                'priority',
                'preferred_contact_method',
                'gdpr_consent_at',
            ]);
        });
    }
};
