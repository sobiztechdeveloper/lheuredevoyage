<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('vat_number')->nullable()->after('company_address');
            $table->string('registration_number')->nullable()->after('vat_number');
            $table->string('business_hours')->nullable()->after('registration_number');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['vat_number', 'registration_number', 'business_hours']);
        });
    }
};
