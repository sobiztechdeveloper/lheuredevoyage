<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('holiday_package_requests', function (Blueprint $table) {
            $table->string('priority', 20)->nullable()->default(null)->change();
            $table->string('preferred_contact_method', 20)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('holiday_package_requests', function (Blueprint $table) {
            $table->string('priority', 20)->default('normal')->nullable(false)->change();
            $table->string('preferred_contact_method', 20)->default('email')->nullable(false)->change();
        });
    }
};
