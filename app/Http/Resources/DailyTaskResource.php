<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'xp_reward' => $this->xp_reward,
            'completed' => $this->completed,
            'task_date' => $this->task_date,
            'completed_at' => $this->completed_at,
        ];
    }
}