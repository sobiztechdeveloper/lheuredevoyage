<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use App\Models\UserBookingHistory;
use App\Models\UserMyBooking;
use App\Models\UserNotification;
use App\Models\UserProfile;
use App\Models\UserSetting;
use App\Models\UserWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            CmsSeeder::class,
            HomeBlockSeeder::class,
            LegalPageSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@lheuredevoyage.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        foreach (User::query()->where('is_admin', false)->get() as $user) {
            UserProfile::query()->updateOrCreate(['user_id' => $user->id], [
                'phone' => '+1 234 567 890',
                'city' => 'New York',
                'country' => 'USA',
            ]);
            UserWallet::query()->updateOrCreate(['user_id' => $user->id], [
                'balance' => 1250.00,
                'currency' => 'USD',
            ]);
            UserSetting::query()->updateOrCreate(['user_id' => $user->id]);

            UserNotification::query()->create([
                'user_id' => $user->id,
                'title' => 'Welcome to L\'Heure De Voyage',
                'body' => 'Your account is ready. Start exploring our travel offers.',
            ]);
        }

        $customer = User::query()->where('email', 'customer@example.com')->first();
        $hotel = Hotel::query()->first();

        if ($customer && $hotel) {
            $booking = Booking::query()->create([
                'user_id' => $customer->id,
                'bookable_type' => Hotel::class,
                'bookable_id' => $hotel->id,
                'reference' => Booking::generateReference(),
                'status' => 'confirmed',
                'total_amount' => $hotel->price,
                'currency' => 'USD',
                'booking_data' => ['guest_name' => $customer->name, 'guest_email' => $customer->email],
                'booked_at' => now(),
            ]);

            UserMyBooking::query()->create([
                'user_id' => $customer->id,
                'booking_id' => $booking->id,
            ]);

            UserBookingHistory::query()->create([
                'user_id' => $customer->id,
                'booking_id' => $booking->id,
                'status' => 'confirmed',
                'notes' => 'Sample booking seeded',
            ]);
        }
    }
}
