<?php

namespace App\Models;

use App\Services\CmsImageUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'phone', 'avatar', 'bio', 'address', 'city', 'country',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        return app(CmsImageUploader::class)->url(
            $this->avatar,
            'assets/img/account/user.jpg'
        );
    }
}
