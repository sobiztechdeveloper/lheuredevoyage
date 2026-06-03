<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('aerticket_api_logs')) {
            Schema::create('aerticket_api_logs', function (Blueprint $table) {
                $table->id();
                $table->string('correlation_id', 36)->index();
                $table->string('service');
                $table->string('method', 10);
                $table->string('endpoint');
                $table->unsignedSmallInteger('status_code')->nullable();
                $table->unsignedInteger('duration_ms')->nullable();
                $table->json('request_payload')->nullable();
                $table->json('response_payload')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('flight_searches', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_searches', 'provider')) {
                $table->string('provider')->default('mock')->after('user_id');
            }
            if (! Schema::hasColumn('flight_searches', 'external_session_id')) {
                $table->string('external_session_id')->nullable()->after('provider');
            }
            if (! Schema::hasColumn('flight_searches', 'search_payload')) {
                $table->json('search_payload')->nullable();
            }
            if (! Schema::hasColumn('flight_searches', 'search_response')) {
                $table->json('search_response')->nullable();
            }
            if (! Schema::hasColumn('flight_searches', 'status')) {
                $table->string('status')->default('completed')->after('cabin_class');
            }
        });

        Schema::table('flight_search_results', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_search_results', 'external_offer_id')) {
                $table->string('external_offer_id')->nullable()->after('flight_search_id');
            }
            if (! Schema::hasColumn('flight_search_results', 'raw_offer')) {
                $table->json('raw_offer')->nullable();
            }
        });

        if (! Schema::hasTable('aerticket_flight_offers')) {
            Schema::create('aerticket_flight_offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flight_search_id')->constrained()->cascadeOnDelete();
                $table->string('external_offer_id')->index();
                $table->string('airline')->nullable();
                $table->string('flight_number')->nullable();
                $table->string('from_destination');
                $table->string('to_destination');
                $table->dateTime('departure_at')->nullable();
                $table->dateTime('arrival_at')->nullable();
                $table->string('duration')->nullable();
                $table->unsignedTinyInteger('stops')->default(0);
                $table->string('cabin_class')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->string('currency', 3)->default('USD');
                $table->json('summary')->nullable();
                $table->json('detail_response')->nullable();
                $table->json('fare_rules_response')->nullable();
                $table->timestamp('detail_fetched_at')->nullable();
                $table->timestamp('fare_rules_fetched_at')->nullable();
                $table->timestamps();

                $table->unique(['flight_search_id', 'external_offer_id'], 'aerticket_offer_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('aerticket_flight_offers');
        Schema::dropIfExists('aerticket_api_logs');

        Schema::table('flight_search_results', function (Blueprint $table) {
            if (Schema::hasColumn('flight_search_results', 'external_offer_id')) {
                $table->dropColumn(['external_offer_id', 'raw_offer']);
            }
        });

        Schema::table('flight_searches', function (Blueprint $table) {
            if (Schema::hasColumn('flight_searches', 'provider')) {
                $table->dropColumn(['provider', 'external_session_id', 'search_payload', 'search_response', 'status']);
            }
        });
    }
};
