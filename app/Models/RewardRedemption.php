<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'reward_id',
        'weekly_summary_id',
        'redeemed_on'
    ];

    protected $casts = [
        'redeemed_on' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function weeklySummary()
    {
        return $this->belongsTo(WeeklyPointsSummary::class, 'weekly_summary_id');
    }
}
