<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'xp_reward',
        'completed',
        'task_date',
        'completed_at',
        'user_id'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'task_date' => 'date',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointsLog()
    {
        return $this->hasOne(DailyPointsLog::class);
    }

}
