<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'status',
        'team_id'
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    // Tareas agrupadas por estado para el Kanban
    public function taskByStatus(){
        return $this->tasks()->get()->groupBy('status');
    }
}
