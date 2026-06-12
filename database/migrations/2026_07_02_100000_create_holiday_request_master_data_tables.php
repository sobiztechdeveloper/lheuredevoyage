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
        foreach ([
            'hotel_categories',
            'sea_views',
            'time_preferences',
            'request_priorities',
            'contact_methods',
        ] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $this->masterTable($table);
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_methods');
        Schema::dropIfExists('request_priorities');
        Schema::dropIfExists('time_preferences');
        Schema::dropIfExists('sea_views');
        Schema::dropIfExists('hotel_categories');
    }
};
