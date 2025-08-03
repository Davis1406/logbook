<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainees extends Model
{
    use HasFactory;

    protected $table = 'trainees';

    protected $fillable = [
        'trainee_id',
        'first_name',
        'last_name',
        'gender',
        'country',
        'study_year',
        'email',
        'programme_id',
        'hospital_id',
        'admission_id',
        'phone_number',
        'upload',
    ];

    /**
     * Relationships
     */

    public function programme()
    {
        return $this->belongsTo(TrainingProgramme::class, 'programme_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class, 'hospital_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'trainee_id');
    }
}
