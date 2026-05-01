<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPointsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_points' => $this->total_points,
            'level' => $this->level,
            'streak_days' => $this->streak_days,
            'last_active' => $this->last_active,
        ];
    }
}