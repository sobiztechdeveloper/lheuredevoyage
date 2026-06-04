<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Cruise;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHotels();
        $this->seedFlights();
        $this->seedCruises();
        $this->seedRentalCars();
        $this->seedTravelInsurances();
        $this->seedTourPackages();
        $this->seedAbout();
    }

    private function seedHotels(): void
    {
        $hotels = [
            ['slug' => 'western-grant-park', 'title' => 'Western Grant Park Hotel', 'location' => '25/B Milford Road, New York', 'stars' => 5, 'price' => 300, 'image' => 'assets/img/hotel/01.jpg', 'rating' => 5.0, 'review_count' => 2500, 'is_featured' => true],
            ['slug' => 'grand-plaza-resort', 'title' => 'Grand Plaza Resort', 'location' => 'Paris, France', 'stars' => 4, 'price' => 220, 'image' => 'assets/img/hotel/02.jpg', 'rating' => 4.8, 'review_count' => 1800],
            ['slug' => 'seaside-luxury-inn', 'title' => 'Seaside Luxury Inn', 'location' => 'Barcelona, Spain', 'stars' => 5, 'price' => 410, 'image' => 'assets/img/hotel/03.jpg', 'rating' => 4.9, 'review_count' => 920],
            ['slug' => 'mountain-view-lodge', 'title' => 'Mountain View Lodge', 'location' => 'Zurich, Switzerland', 'stars' => 4, 'price' => 350, 'image' => 'assets/img/hotel/04.jpg', 'rating' => 4.7, 'review_count' => 640],
            ['slug' => 'city-center-suites', 'title' => 'City Center Suites', 'location' => 'London, UK', 'stars' => 4, 'price' => 280, 'image' => 'assets/img/hotel/05.jpg', 'rating' => 4.6, 'review_count' => 1100],
            ['slug' => 'tropical-paradise-hotel', 'title' => 'Tropical Paradise Hotel', 'location' => 'Bali, Indonesia', 'stars' => 5, 'price' => 190, 'image' => 'assets/img/hotel/06.jpg', 'rating' => 4.9, 'review_count' => 2100, 'is_featured' => true],
        ];

        foreach ($hotels as $hotel) {
            $model = Hotel::query()->updateOrCreate(['slug' => $hotel['slug']], array_merge([
                'description' => 'Comfortable stay with modern amenities and excellent service.',
                'price_unit' => 'Per Night',
                'is_active' => true,
            ], $hotel));
            $this->seedHotelRooms($model);
        }
    }

    private function seedHotelRooms(Hotel $hotel): void
    {
        $templates = [
            ['name' => 'Standard Room', 'room_type' => 'Standard', 'bed_type' => 'Double Bed', 'meal_plan' => 'Breakfast Included', 'price' => (float) $hotel->price, 'max_adults' => 2],
            ['name' => 'Deluxe Room', 'room_type' => 'Deluxe', 'bed_type' => 'King Bed', 'meal_plan' => 'Half Board', 'price' => (float) $hotel->price * 1.25, 'max_adults' => 2],
            ['name' => 'Family Room', 'room_type' => 'Family', 'bed_type' => 'Twin Beds', 'meal_plan' => 'Breakfast Included', 'price' => (float) $hotel->price * 1.4, 'max_adults' => 2, 'max_children' => 2],
        ];

        foreach ($templates as $i => $template) {
            \App\Models\HotelRoom::query()->updateOrCreate(
                ['hotel_id' => $hotel->id, 'name' => $template['name']],
                array_merge($template, [
                    'description' => 'Comfortable '.$template['name'].' at '.$hotel->name,
                    'room_size' => '28 sqm',
                    'currency' => 'USD',
                    'is_active' => true,
                    'sort_order' => $i,
                    'features' => ['WiFi', 'Air Conditioning'],
                ]),
            );
        }
    }

    private function seedFlights(): void
    {
        $flights = [
            ['slug' => 'nyc-paris-direct', 'title' => 'New York to Paris', 'airline' => 'Air France', 'flight_number' => 'AF007', 'departure_city' => 'New York', 'arrival_city' => 'Paris', 'location' => 'NYC - CDG', 'duration' => '7h 30m', 'departure_time' => '08:15', 'arrival_time' => '21:45', 'price' => 650, 'image' => 'assets/img/flight/01.jpg', 'rating' => 4.8, 'review_count' => 890, 'is_featured' => true, 'refundable_type' => 'refundable', 'baggage_kg' => 23],
            ['slug' => 'london-dubai', 'title' => 'London to Dubai', 'airline' => 'Emirates', 'flight_number' => 'EK001', 'departure_city' => 'London', 'arrival_city' => 'Dubai', 'location' => 'LHR - DXB', 'duration' => '6h 45m', 'departure_time' => '10:30', 'arrival_time' => '20:15', 'price' => 520, 'image' => 'assets/img/flight/02.jpg', 'rating' => 4.9, 'review_count' => 1200, 'refundable_type' => 'as_per_rules', 'baggage_kg' => 25],
            ['slug' => 'tokyo-singapore', 'title' => 'Tokyo to Singapore', 'airline' => 'Singapore Airlines', 'flight_number' => 'SQ637', 'departure_city' => 'Tokyo', 'arrival_city' => 'Singapore', 'location' => 'NRT - SIN', 'duration' => '7h 10m', 'departure_time' => '09:00', 'arrival_time' => '14:10', 'price' => 480, 'image' => 'assets/img/flight/03.jpg', 'rating' => 4.7, 'review_count' => 560, 'refundable_type' => 'non_refundable', 'baggage_kg' => 20],
        ];

        foreach ($flights as $flight) {
            Flight::query()->updateOrCreate(['slug' => $flight['slug']], array_merge([
                'description' => 'Comfortable flight with in-flight entertainment.',
                'flight_class' => 'Economy',
                'stops' => 0,
                'price_unit' => 'Per Person',
                'is_active' => true,
            ], $flight));
        }
    }

    private function seedCruises(): void
    {
        $cruises = [
            ['slug' => 'mediterranean-discovery', 'title' => 'Mediterranean Discovery', 'ship_name' => 'Ocean Voyager', 'departure_port' => 'Barcelona', 'location' => 'Mediterranean', 'duration_days' => 7, 'price' => 1200, 'image' => 'assets/img/cruise/01.jpg', 'rating' => 4.9, 'review_count' => 430, 'is_featured' => true],
            ['slug' => 'caribbean-paradise', 'title' => 'Caribbean Paradise', 'ship_name' => 'Caribbean Star', 'departure_port' => 'Miami', 'location' => 'Caribbean', 'duration_days' => 5, 'price' => 950, 'image' => 'assets/img/cruise/02.jpg', 'rating' => 4.8, 'review_count' => 320],
        ];

        foreach ($cruises as $cruise) {
            Cruise::query()->updateOrCreate(['slug' => $cruise['slug']], array_merge([
                'description' => 'All-inclusive cruise with premium dining and entertainment.',
                'price_unit' => 'Per Person',
                'is_active' => true,
            ], $cruise));
        }
    }

    private function seedRentalCars(): void
    {
        $cars = [
            ['slug' => 'toyota-camry', 'title' => 'Toyota Camry', 'car_type' => 'Sedan', 'location' => 'New York', 'seats' => 5, 'transmission' => 'Automatic', 'price' => 65, 'image' => 'assets/img/car/01.jpg', 'rating' => 4.7, 'review_count' => 210],
            ['slug' => 'bmw-x5', 'title' => 'BMW X5', 'car_type' => 'SUV', 'location' => 'Los Angeles', 'seats' => 7, 'transmission' => 'Automatic', 'price' => 120, 'image' => 'assets/img/car/02.jpg', 'rating' => 4.9, 'review_count' => 180, 'is_featured' => true],
            ['slug' => 'mercedes-e-class', 'title' => 'Mercedes E-Class', 'car_type' => 'Luxury', 'location' => 'Paris', 'seats' => 5, 'transmission' => 'Automatic', 'price' => 150, 'image' => 'assets/img/car/03.jpg', 'rating' => 4.8, 'review_count' => 95],
        ];

        foreach ($cars as $car) {
            RentalCar::query()->updateOrCreate(['slug' => $car['slug']], array_merge([
                'description' => 'Well-maintained vehicle with full insurance options.',
                'price_unit' => 'Per Day',
                'is_active' => true,
            ], $car));
        }
    }

    private function seedTravelInsurances(): void
    {
        $plans = [
            ['slug' => 'basic-travel-cover', 'title' => 'Basic Travel Cover', 'coverage_type' => 'Medical', 'duration_days' => 14, 'location' => 'Worldwide', 'price' => 49, 'image' => 'assets/img/deal/01.jpg', 'rating' => 4.5, 'review_count' => 340],
            ['slug' => 'premium-travel-shield', 'title' => 'Premium Travel Shield', 'coverage_type' => 'Comprehensive', 'duration_days' => 30, 'location' => 'Worldwide', 'price' => 99, 'image' => 'assets/img/deal/02.jpg', 'rating' => 4.8, 'review_count' => 520, 'is_featured' => true],
        ];

        foreach ($plans as $plan) {
            TravelInsurance::query()->updateOrCreate(['slug' => $plan['slug']], array_merge([
                'description' => 'Comprehensive travel protection for peace of mind.',
                'price_unit' => 'Per Trip',
                'is_active' => true,
            ], $plan));
        }
    }

    private function seedTourPackages(): void
    {
        $packages = [
            ['slug' => 'paris-romantic-getaway', 'title' => 'Paris Romantic Getaway', 'destination' => 'Paris', 'location' => 'France', 'duration_days' => 5, 'price' => 899, 'image' => 'assets/img/tour/01.jpg', 'rating' => 4.9, 'review_count' => 780, 'is_featured' => true],
            ['slug' => 'bali-adventure-tour', 'title' => 'Bali Adventure Tour', 'destination' => 'Bali', 'location' => 'Indonesia', 'duration_days' => 7, 'price' => 749, 'image' => 'assets/img/tour/02.jpg', 'rating' => 4.8, 'review_count' => 650],
            ['slug' => 'swiss-alps-explorer', 'title' => 'Swiss Alps Explorer', 'destination' => 'Zurich', 'location' => 'Switzerland', 'duration_days' => 6, 'price' => 1299, 'image' => 'assets/img/tour/03.jpg', 'rating' => 4.9, 'review_count' => 420],
        ];

        foreach ($packages as $package) {
            TourPackage::query()->updateOrCreate(['slug' => $package['slug']], array_merge([
                'description' => 'Curated holiday package with hotels, tours, and transfers.',
                'price_unit' => 'Per Person',
                'is_active' => true,
            ], $package));
        }
    }

    private function seedAbout(): void
    {
        About::query()->updateOrCreate(
            ['id' => 1],
            [
                'heading' => 'We Provide Best Travel Experience',
                'subheading' => 'About L\'Heure De Voyage',
                'content' => 'L\'Heure De Voyage helps you discover flights, hotels, cruises, cars, insurance, and holiday packages with trusted partners worldwide.',
                'image_primary' => 'assets/img/about/01.jpg',
                'image_secondary' => 'assets/img/about/02.jpg',
                'experience_years' => 15,
                'is_active' => true,
            ]
        );
    }
}
