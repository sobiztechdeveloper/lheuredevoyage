<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('bookable');
            $table->string('reference')->unique();
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('booking_data')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
