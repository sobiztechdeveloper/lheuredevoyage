<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('website_settings', 'copyright_text')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->dropColumn('copyright_text');
            });
        }
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('copyright_text')->nullable()->after('footer_text');
        });
    }
};
