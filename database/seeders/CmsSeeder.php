<?php

namespace Database\Seeders;

use App\Models\ContactDetail;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\Testimonial;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSetting::query()->updateOrCreate(['id' => 1], [
            'company_name' => "L'Heure De Voyage",
            'company_email' => 'info@lheuredevoyage.com',
            'company_phone' => '+1 234 567 890',
            'company_address' => '25/B Milford Road, New York, USA',
            'footer_text' => 'Your trusted partner for flights, hotels, cruises, cars, insurance and holiday packages worldwide.',
            'copyright_text' => "L'Heure De Voyage. All Rights Reserved.",
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'linkedin_url' => 'https://linkedin.com',
            'youtube_url' => 'https://youtube.com',
        ]);

        ContactDetail::query()->updateOrCreate(['id' => 1], [
            'address' => '25/B Milford, New York, USA',
            'phone' => '+1 234 567 890',
            'email' => 'info@lheuredevoyage.com',
            'whatsapp_number' => '+1234567890',
            'google_map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin',
        ]);

        HeroSection::query()->updateOrCreate(['id' => 1], [
            'title' => 'Explore The World Together',
            'subtitle' => 'Find awesome flights, hotels, tours, cars and packages',
            'button_text' => 'Start Exploring',
            'button_url' => '/tourpackages',
            'image' => 'assets/img/hero/hero-2x.jpg',
            'status' => true,
            'sort_order' => 0,
        ]);

        $testimonials = [
            ['name' => 'Diana Carter', 'designation' => 'Traveler', 'review' => 'Excellent service and smooth booking experience from start to finish.', 'rating' => 5, 'image' => 'assets/img/testimonial/01.jpg'],
            ['name' => 'Brandon Wigfall', 'designation' => 'Business Client', 'review' => 'Professional team and great prices on international flights.', 'rating' => 5, 'image' => 'assets/img/testimonial/02.jpg'],
            ['name' => 'Sylvia Green', 'designation' => 'Holiday Guest', 'review' => 'Our cruise package exceeded expectations. Highly recommended!', 'rating' => 5, 'image' => 'assets/img/testimonial/03.jpg'],
        ];

        foreach ($testimonials as $i => $data) {
            Testimonial::query()->updateOrCreate(['name' => $data['name']], array_merge($data, ['status' => true]));
        }

        $faqs = [
            ['question' => 'How do I book a trip?', 'answer' => 'Browse our catalog, select your product, and complete the booking form while logged in.'],
            ['question' => 'Can I cancel my booking?', 'answer' => 'Cancellation policies depend on the provider. Contact our team for assistance.'],
            ['question' => 'Do you offer travel insurance?', 'answer' => 'Yes, we offer multiple travel insurance plans on our Travel Insurance page.'],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::query()->updateOrCreate(['question' => $faq['question']], array_merge($faq, ['sort_order' => $i, 'status' => true]));
        }
    }
}
