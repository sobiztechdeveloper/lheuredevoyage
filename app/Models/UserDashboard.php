<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboard extends Model
{
    protected $fillable = ['user_id', 'total_bookings', 'pending_bookings', 'total_earned'];

    protected function casts(): array
    {
        return ['total_earned' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
