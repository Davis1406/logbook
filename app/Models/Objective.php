<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_code',
        'description',
        'rotation_id',
    ];

    public function rotation()
    {
        return $this->belongsTo(Rotation::class, 'rotation_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'objective_id');
    }
}
