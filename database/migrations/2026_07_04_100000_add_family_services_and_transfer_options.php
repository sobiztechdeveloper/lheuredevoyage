<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function masterTable(Blueprint $table): void
    {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->string('icon')->nullable();
        $table->unsignedInteger('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
        $table->softDeletes();
    }

    public function up(): void
    {
        foreach (['family_services', 'transfer_options'] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $this->masterTable($table);
                });
            }
        }

        Schema::table('holiday_package_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('holiday_package_requests', 'family_services')) {
                $table->json('family_services')->nullable()->after('wellness');
            }
            if (! Schema::hasColumn('holiday_package_requests', 'transfer_preferences')) {
                $table->json('transfer_preferences')->nullable()->after('family_services');
            }
        });
    }

    public function down(): void
    {
        Schema::table('holiday_package_requests', function (Blueprint $table) {
            $table->dropColumn(['family_services', 'transfer_preferences']);
        });

        Schema::dropIfExists('transfer_options');
        Schema::dropIfExists('family_services');
    }
};
