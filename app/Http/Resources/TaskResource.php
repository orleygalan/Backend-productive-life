<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'project_id' => $this->project_id,
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'created_at' => $this->created_at,
        ];
    }
}