<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'points_cost'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }
}
