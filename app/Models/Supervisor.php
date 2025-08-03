<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'name',
        'gender',
        'country',
        'mobile',
        'programme_id',
        'hospital_id',
        'avatar',
    ];

    // Optional: Define relationships if needed
    public function programme()
    {
        return $this->belongsTo(TrainingProgramme::class, 'programme_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class, 'hospital_id');
    }
}
