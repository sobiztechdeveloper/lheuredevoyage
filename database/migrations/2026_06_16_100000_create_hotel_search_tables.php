<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotel_searches')) {
            return;
        }

        Schema::create('hotel_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 32)->default('catalog');
            $table->string('destination');
            $table->date('journey_date');
            $table->date('return_date')->nullable();
            $table->unsignedTinyInteger('adult')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedTinyInteger('infant')->default(0);
            $table->unsignedTinyInteger('rooms')->default(1);
            $table->string('room_type')->nullable();
            $table->string('status', 32)->default('completed');
            $table->timestamps();
        });

        Schema::create('hotel_search_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_search_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->nullOnDelete();
            $table->string('slug');
            $table->string('title');
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('star_rating')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->decimal('price', 12, 2);
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_search_results');
        Schema::dropIfExists('hotel_searches');
    }
};
