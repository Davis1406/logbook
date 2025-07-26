<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramme extends Model
{
    use HasFactory;

    protected $fillable = [
        'programme_name',
        'duration',
        'status',
    ];

    public function rotations()
    {
        return $this->hasMany(Rotation::class, 'programme_id');
    }
}
