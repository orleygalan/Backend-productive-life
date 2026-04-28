<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Organization extends Model
{
    use HasUuids;
    protected $fillable = [
        'name',
        'owner_id'
    ];

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function teams(){
        return $this->hasMany(Team::class);
    }

}
