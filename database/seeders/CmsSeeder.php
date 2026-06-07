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
            'company_phone' => '+41 44 000 00 00',
            'company_address' => "Bahnhofstrasse 1\n8001 Zürich\nSwitzerland",
            'vat_number' => 'CHE-000.000.000 MWST',
            'registration_number' => 'CH-000.0.000.000-0',
            'business_hours' => 'Monday – Friday, 09:00 – 18:00 (CET)',
            'footer_text' => 'Your trusted partner for flights, hotels, cruises, cars, insurance and holiday packages worldwide.',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'linkedin_url' => 'https://linkedin.com',
            'youtube_url' => 'https://youtube.com',
        ]);

        ContactDetail::query()->updateOrCreate(['id' => 1], [
            'address' => "Bahnhofstrasse 1\n8001 Zürich\nSwitzerland",
            'phone' => '+41 44 000 00 00',
            'email' => 'info@lheuredevoyage.com',
            'whatsapp_number' => '+41 44 000 00 00',
            'form_title' => 'Get in Touch',
            'form_subtitle' => 'Have a question about flights, hotels, packages or an existing booking? Send us a message and our team will respond as soon as possible.',
            'google_map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2701.5!2d8.5417!3d47.3769!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDfCsDIyJzM2LjgiTiA4wrAzMiczMC4xIkU!5e0!3m2!1sen!2sch!4v1',
        ]);

        HeroSection::query()->updateOrCreate(['id' => 1], [
            'title' => 'Explore The World Together',
            'subtitle' => 'Find awesome flights, hotels, tours, cars and packages',
            'button_text' => null,
            'button_url' => null,
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
