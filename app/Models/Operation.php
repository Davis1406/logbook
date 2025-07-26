<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'rotation_id',
        'objective_id',
        'supervisor_id',
        'supervisor_name',
        'procedure_date',
        'procedure_time',
        'duration',
        'participation_type',
        'procedure_notes',
        'status',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainees::class, 'trainee_id');
    }

    public function rotation()
    {
        return $this->belongsTo(Rotation::class, 'rotation_id');
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }
}
