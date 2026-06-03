<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SitemapService $sitemap): Response
    {
        return response()
            ->view('sitemap.xml', ['urls' => $sitemap->urls()])
            ->header('Content-Type', 'application/xml');
    }
}
