<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserPoints extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'total_points',
        'level',
        'streak_days',
        'last_active'
    ];

    protected function casts(): array
    {
        return [
            'last_active' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calcula el nivel según los puntos totales
    public function calculateLevel(): int
    {
        return (int) floor($this->total_points / 500) + 1;
    }
}
