<?php

namespace App\Support;

class CatalogEmptyState
{
    /**
     * @return array<string, mixed>|null
     */
    public static function config(string $type): ?array
    {
        $configs = [
            'tourpackage' => [
                'icon' => 'fa-umbrella-beach',
                'label_plural' => 'packages',
                'index_route' => 'tourpackage',
                'quote_route' => 'contact',
                'empty_title' => 'No packages match your search',
                'empty_message' => 'Try another destination, adjust filters, or browse all packages.',
                'view_all_label' => 'View All Packages',
                'banner_title' => 'Need a personalised tour package quote?',
                'banner_message' => 'Our consultants will prepare tailored holiday options with no obligation.',
                'banner_cta' => 'Request Package Quote',
            ],
            'flight' => [
                'icon' => 'fa-plane',
                'label_plural' => 'flights',
                'index_route' => 'flight',
                'quote_route' => 'contact',
                'empty_title' => 'No flights match your search',
                'empty_message' => 'Try different dates or airports, adjust filters, or browse all flights.',
                'view_all_label' => 'View All Flights',
                'no_search_title' => 'Search for flights',
                'no_search_message' => 'Use the search form above to find available flights.',
                'banner_title' => 'Need a personalised flight quote?',
                'banner_message' => 'Our consultants will find routing and fare options with no obligation.',
                'banner_cta' => 'Request Flight Quote',
            ],
            'hotel' => [
                'icon' => 'fa-hotel',
                'label_plural' => 'hotels',
                'index_route' => 'hotel',
                'quote_route' => 'contact',
                'empty_title' => 'No hotels match your search',
                'empty_message' => 'Try another destination or dates, adjust filters, or browse all hotels.',
                'view_all_label' => 'View All Hotels',
                'no_search_title' => 'Search for hotels',
                'no_search_message' => 'Use the search form above to find available hotels.',
                'banner_title' => 'Need a personalised hotel quote?',
                'banner_message' => 'Our consultants will prepare stay options and room pricing with no obligation.',
                'banner_cta' => 'Request Hotel Quote',
            ],
            'cruise' => [
                'icon' => 'fa-ship',
                'label_plural' => 'cruises',
                'index_route' => 'cruise',
                'quote_route' => 'cruise.quote.wizard',
                'empty_title' => 'No cruises match your search',
                'empty_message' => 'Try another destination, adjust filters, or browse all cruises.',
                'view_all_label' => 'View All Cruises',
                'banner_title' => 'Need a personalised cruise quote?',
                'banner_message' => 'Our consultants will prepare sailing options and cabin pricing with no obligation.',
                'banner_cta' => 'Request Cruise Quote',
            ],
            'rentalcar' => [
                'icon' => 'fa-car',
                'label_plural' => 'cars',
                'index_route' => 'rentalcar',
                'quote_route' => 'contact',
                'empty_title' => 'No cars match your search',
                'empty_message' => 'Try another pickup location or dates, adjust filters, or browse all cars.',
                'view_all_label' => 'View All Cars',
                'banner_title' => 'Need a personalised car rental quote?',
                'banner_message' => 'Our consultants will prepare vehicle options and pricing with no obligation.',
                'banner_cta' => 'Request Car Quote',
            ],
            'travelinsurance' => [
                'icon' => 'fa-shield-halved',
                'label_plural' => 'plans',
                'index_route' => 'travelinsurance',
                'quote_route' => 'travelinsurance.quote.wizard',
                'empty_title' => 'No insurance plans match your search',
                'empty_message' => 'Try another destination or clear filters. You can also request a personalised quote.',
                'view_all_label' => 'View All Plans',
                'banner_title' => 'Need help choosing a plan?',
                'banner_message' => 'Our Swiss travel consultants will prepare a personalised insurance quote at no obligation.',
                'banner_cta' => 'Request Insurance Quote',
            ],
        ];

        return $configs[$type] ?? null;
    }

    public static function quoteUrl(string $type, array $searchQuery = []): string
    {
        $config = self::config($type);
        if (! $config) {
            return route('contact');
        }

        $url = route($config['quote_route']);
        if ($searchQuery !== []) {
            $url .= '?'.http_build_query($searchQuery);
        }

        return $url;
    }
}
