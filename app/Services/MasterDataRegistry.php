<?php

namespace App\Services;

use App\Models\Master\MasterDataModel;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class MasterDataRegistry
{
    public static function types(): array
    {
        return config('master_data.types', []);
    }

    public static function resolveTypeKey(string $routeOrKey): string
    {
        if (isset(self::types()[$routeOrKey])) {
            return $routeOrKey;
        }

        foreach (self::types() as $key => $config) {
            if ($config['route'] === $routeOrKey) {
                return $key;
            }
        }

        throw new InvalidArgumentException("Unknown master data type: {$routeOrKey}");
    }

    public static function config(string $routeOrKey): array
    {
        $key = self::resolveTypeKey($routeOrKey);

        return array_merge(['key' => $key], self::types()[$key]);
    }

    /**
     * @return class-string<MasterDataModel>
     */
    public static function modelClass(string $routeOrKey): string
    {
        return self::config($routeOrKey)['model'];
    }

    public static function routeName(string $routeOrKey, string $action): string
    {
        $key = self::resolveTypeKey($routeOrKey);

        return "admin.master-data.{$key}.{$action}";
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function catalogRelations(string $catalogKey): array
    {
        return config("master_data.catalog.{$catalogKey}", []);
    }
}
