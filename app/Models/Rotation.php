<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotation_name',
        'programme_id',
    ];

    public function programme()
    {
        return $this->belongsTo(TrainingProgramme::class, 'programme_id');
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'rotation_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'rotation_id');
    }
}
