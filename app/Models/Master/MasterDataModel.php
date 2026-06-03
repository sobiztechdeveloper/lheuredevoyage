<?php

namespace App\Models\Master;

use App\Models\Concerns\IsMasterData;
use Illuminate\Database\Eloquent\Model;

abstract class MasterDataModel extends Model
{
    use IsMasterData;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
