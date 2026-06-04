<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('default_breadcrumb_image')->nullable()->after('favicon');
        });

        Schema::table('contact_details', function (Blueprint $table) {
            $table->string('breadcrumb_image')->nullable()->after('whatsapp_number');
            $table->string('form_title')->nullable()->after('breadcrumb_image');
            $table->text('form_subtitle')->nullable()->after('form_title');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('default_breadcrumb_image');
        });

        Schema::table('contact_details', function (Blueprint $table) {
            $table->dropColumn(['breadcrumb_image', 'form_title', 'form_subtitle']);
        });
    }
};
