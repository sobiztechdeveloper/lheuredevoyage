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
        $masters = [
            'hotel_facilities',
            'hotel_sports',
            'hotel_wellness',
            'hotel_beach_types',
            'room_types',
            'room_facilities',
            'meal_plans',
            'package_categories',
            'package_themes',
            'cruise_categories',
            'cruise_facilities',
            'vehicle_types',
            'vehicle_features',
            'insurance_types',
            'insurance_coverage_types',
        ];

        foreach ($masters as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $this->masterTable($table);
                });
            }
        }

        if (! Schema::hasTable('hotel_facility_hotel')) {
            Schema::create('hotel_facility_hotel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('hotel_facility_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'hotel_facility_id'], 'hotel_facility_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_hotel_sport')) {
            Schema::create('hotel_hotel_sport', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('hotel_sport_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'hotel_sport_id'], 'hotel_sport_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_hotel_wellness')) {
            Schema::create('hotel_hotel_wellness', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('hotel_wellness_id')->constrained('hotel_wellness')->cascadeOnDelete();
                $table->unique(['hotel_id', 'hotel_wellness_id'], 'hotel_wellness_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_hotel_beach_type')) {
            Schema::create('hotel_hotel_beach_type', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('hotel_beach_type_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'hotel_beach_type_id'], 'hotel_beach_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_room_type')) {
            Schema::create('hotel_room_type', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'room_type_id'], 'hotel_room_type_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_room_facility')) {
            Schema::create('hotel_room_facility', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_facility_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'room_facility_id'], 'hotel_room_fac_pivot_unique');
            });
        }

        if (! Schema::hasTable('hotel_meal_plan')) {
            Schema::create('hotel_meal_plan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->foreignId('meal_plan_id')->constrained()->cascadeOnDelete();
                $table->unique(['hotel_id', 'meal_plan_id'], 'hotel_meal_plan_pivot_unique');
            });
        }

        if (! Schema::hasTable('package_category_tour_package')) {
            Schema::create('package_category_tour_package', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tour_package_id')->constrained()->cascadeOnDelete();
                $table->foreignId('package_category_id')->constrained()->cascadeOnDelete();
                $table->unique(['tour_package_id', 'package_category_id'], 'pkg_cat_tour_pkg_unique');
            });
        }

        if (! Schema::hasTable('package_theme_tour_package')) {
            Schema::create('package_theme_tour_package', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tour_package_id')->constrained()->cascadeOnDelete();
                $table->foreignId('package_theme_id')->constrained()->cascadeOnDelete();
                $table->unique(['tour_package_id', 'package_theme_id'], 'pkg_theme_tour_pkg_unique');
            });
        }

        if (! Schema::hasTable('cruise_category_cruise')) {
            Schema::create('cruise_category_cruise', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_id')->constrained()->cascadeOnDelete();
                $table->foreignId('cruise_category_id')->constrained()->cascadeOnDelete();
                $table->unique(['cruise_id', 'cruise_category_id'], 'cruise_cat_pivot_unique');
            });
        }

        if (! Schema::hasTable('cruise_cruise_facility')) {
            Schema::create('cruise_cruise_facility', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cruise_id')->constrained()->cascadeOnDelete();
                $table->foreignId('cruise_facility_id')->constrained()->cascadeOnDelete();
                $table->unique(['cruise_id', 'cruise_facility_id'], 'cruise_fac_pivot_unique');
            });
        }

        if (! Schema::hasTable('rental_car_vehicle_type')) {
            Schema::create('rental_car_vehicle_type', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rental_car_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_type_id')->constrained()->cascadeOnDelete();
                $table->unique(['rental_car_id', 'vehicle_type_id'], 'car_vehicle_type_unique');
            });
        }

        if (! Schema::hasTable('rental_car_vehicle_feature')) {
            Schema::create('rental_car_vehicle_feature', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rental_car_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_feature_id')->constrained()->cascadeOnDelete();
                $table->unique(['rental_car_id', 'vehicle_feature_id'], 'car_vehicle_feat_unique');
            });
        }

        if (! Schema::hasTable('insurance_type_travel_insurance')) {
            Schema::create('insurance_type_travel_insurance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_insurance_id');
                $table->unsignedBigInteger('insurance_type_id');
                $table->foreign('travel_insurance_id', 'ins_travel_ins_fk')->references('id')->on('travel_insurances')->cascadeOnDelete();
                $table->foreign('insurance_type_id', 'ins_type_fk')->references('id')->on('insurance_types')->cascadeOnDelete();
                $table->unique(['travel_insurance_id', 'insurance_type_id'], 'ins_type_travel_ins_unique');
            });
        }

        if (! Schema::hasTable('coverage_type_travel_insurance')) {
            Schema::create('coverage_type_travel_insurance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_insurance_id');
                $table->unsignedBigInteger('insurance_coverage_type_id');
                $table->foreign('travel_insurance_id', 'cov_travel_ins_fk')->references('id')->on('travel_insurances')->cascadeOnDelete();
                $table->foreign('insurance_coverage_type_id', 'cov_type_fk')->references('id')->on('insurance_coverage_types')->cascadeOnDelete();
                $table->unique(['travel_insurance_id', 'insurance_coverage_type_id'], 'cov_type_travel_ins_unique');
            });
        }
    }

    public function down(): void
    {
        $pivots = [
            'coverage_type_travel_insurance',
            'insurance_type_travel_insurance',
            'rental_car_vehicle_feature',
            'rental_car_vehicle_type',
            'cruise_cruise_facility',
            'cruise_category_cruise',
            'package_theme_tour_package',
            'package_category_tour_package',
            'hotel_meal_plan',
            'hotel_room_facility',
            'hotel_room_type',
            'hotel_hotel_beach_type',
            'hotel_hotel_wellness',
            'hotel_hotel_sport',
            'hotel_facility_hotel',
        ];

        foreach ($pivots as $table) {
            Schema::dropIfExists($table);
        }

        foreach (array_reverse([
            'insurance_coverage_types',
            'insurance_types',
            'vehicle_features',
            'vehicle_types',
            'cruise_facilities',
            'cruise_categories',
            'package_themes',
            'package_categories',
            'meal_plans',
            'room_facilities',
            'room_types',
            'hotel_beach_types',
            'hotel_wellness',
            'hotel_sports',
            'hotel_facilities',
        ]) as $table) {
            Schema::dropIfExists($table);
        }
    }
};
