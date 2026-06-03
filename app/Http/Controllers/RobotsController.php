<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = url('/sitemap.xml');

        $content = "User-agent: *\nAllow: /\n\nSitemap: {$sitemap}\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
