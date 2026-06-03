<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('quotes')) {
            Schema::create('quotes', function (Blueprint $table) {
                $table->id();
                $table->string('quote_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('flight_booking_request_id')->nullable()->constrained()->nullOnDelete();
                $table->string('quote_type', 30);
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('currency', 10)->default('USD');
                $table->decimal('amount', 12, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->nullable();
                $table->decimal('service_fee', 12, 2)->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->date('valid_until');
                $table->string('status', 30)->default('draft');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('quote_items')) {
            Schema::create('quote_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
                $table->string('item_name');
                $table->text('description')->nullable();
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('total_price', 12, 2)->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('quote_status_histories')) {
            Schema::create('quote_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
                $table->string('old_status', 30)->nullable();
                $table->string('new_status', 30);
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_status_histories');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
    }
};
