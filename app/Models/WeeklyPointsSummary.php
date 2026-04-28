<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WeeklyPointsSummary extends Model
{
    use HasUuids;

    protected $fillable = [
        'total_points',
        'week_start',
        'week_end',
        'redeemed',
        'closed_at',
        'user_id'
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'redeemed' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class, 'weekly_summary_id');
    }

    // Verifica si hoy es domingo y se puede canjear
    public function canRedeem(): bool
    {
        return now()->isSunday() && !$this->redeemed;
    }
}
