<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CatalogUrls
{
    public static function bookUrl(string $routePrefix, Model $item, ?Request $request = null): string
    {
        $request ??= request();

        $url = route($routePrefix.'.book', $item->slug);
        $query = array_filter(
            $request->only(['adult', 'children', 'infant', 'travel_month', 'travelers']),
            fn ($value) => $value !== null && $value !== '' && $value !== [],
        );

        if ($query !== []) {
            $url .= '?'.http_build_query($query);
        }

        return $url;
    }
}
