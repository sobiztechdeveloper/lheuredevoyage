<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->upgradeHotels();
        $this->upgradeTourPackages();
        $this->upgradeCruises();
        $this->upgradeRentalCars();
        $this->upgradeTravelInsurances();
        $this->createFlightSearchTables();
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_search_results');
        Schema::dropIfExists('flight_searches');

        foreach (['travel_insurances', 'rental_cars', 'cruises', 'tour_packages', 'hotels'] as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropSoftDeletes();
                    $blueprint->dropConstrainedForeignId('created_by');
                    $blueprint->dropConstrainedForeignId('updated_by');
                });
            }
        }
    }

    private function upgradeHotels(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug');
            $table->text('short_description')->nullable()->after('description');
            $table->string('featured_image')->nullable()->after('image');
            $table->json('gallery_json')->nullable();
            $table->unsignedTinyInteger('star_rating')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        foreach (DB::table('hotels')->orderBy('id')->get() as $row) {
            DB::table('hotels')->where('id', $row->id)->update([
                'name' => $row->title,
                'featured_image' => $row->image,
                'star_rating' => $row->stars,
                'status' => $row->is_active,
                'featured' => $row->is_featured,
            ]);
        }
    }

    private function upgradeTourPackages(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug');
            $table->string('duration')->nullable();
            $table->text('short_description')->nullable();
            $table->string('featured_image')->nullable()->after('image');
            $table->json('gallery_json')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        foreach (DB::table('tour_packages')->orderBy('id')->get() as $row) {
            DB::table('tour_packages')->where('id', $row->id)->update([
                'name' => $row->title,
                'duration' => $row->duration_days ? $row->duration_days.' Days' : null,
                'featured_image' => $row->image,
                'status' => $row->is_active,
                'featured' => $row->is_featured,
            ]);
        }
    }

    private function upgradeCruises(): void
    {
        Schema::table('cruises', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug');
            $table->string('destination')->nullable();
            $table->string('duration')->nullable();
            $table->text('short_description')->nullable();
            $table->string('featured_image')->nullable()->after('image');
            $table->json('gallery_json')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        foreach (DB::table('cruises')->orderBy('id')->get() as $row) {
            DB::table('cruises')->where('id', $row->id)->update([
                'name' => $row->title,
                'destination' => $row->location ?? null,
                'duration' => $row->duration_days ? $row->duration_days.' Days' : null,
                'featured_image' => $row->image,
                'status' => $row->is_active,
                'featured' => $row->is_featured,
            ]);
        }
    }

    private function upgradeRentalCars(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug');
            $table->string('vehicle_type')->nullable();
            $table->unsignedTinyInteger('passenger_capacity')->nullable();
            $table->decimal('price_per_day', 12, 2)->nullable();
            $table->text('short_description')->nullable();
            $table->string('featured_image')->nullable()->after('image');
            $table->json('gallery_json')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        foreach (DB::table('rental_cars')->orderBy('id')->get() as $row) {
            DB::table('rental_cars')->where('id', $row->id)->update([
                'name' => $row->title,
                'vehicle_type' => $row->car_type,
                'passenger_capacity' => $row->seats,
                'price_per_day' => $row->price,
                'featured_image' => $row->image,
                'status' => $row->is_active,
                'featured' => $row->is_featured,
            ]);
        }
    }

    private function upgradeTravelInsurances(): void
    {
        Schema::table('travel_insurances', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug');
            $table->string('coverage')->nullable();
            $table->text('short_description')->nullable();
            $table->string('featured_image')->nullable()->after('image');
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
        });

        foreach (DB::table('travel_insurances')->orderBy('id')->get() as $row) {
            DB::table('travel_insurances')->where('id', $row->id)->update([
                'name' => $row->title,
                'coverage' => $row->coverage_type,
                'featured_image' => $row->image,
                'status' => $row->is_active,
                'featured' => $row->is_featured,
            ]);
        }
    }

    private function createFlightSearchTables(): void
    {
        Schema::create('flight_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('trip_type')->default('one_way');
            $table->string('from_destination');
            $table->string('to_destination');
            $table->date('journey_date');
            $table->date('return_date')->nullable();
            $table->unsignedTinyInteger('adult')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedTinyInteger('infant')->default(0);
            $table->string('cabin_class')->default('economy');
            $table->timestamps();
        });

        Schema::create('flight_search_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_search_id')->constrained()->cascadeOnDelete();
            $table->string('airline');
            $table->string('flight_number');
            $table->string('from_destination');
            $table->string('to_destination');
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->string('duration');
            $table->unsignedTinyInteger('stops')->default(0);
            $table->string('cabin_class');
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });
    }
};
