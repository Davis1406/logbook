<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospitals extends Model
{
    use HasFactory;

    // Specify fillable fields for mass assignment
    protected $fillable = [
        'hospital_name',
        'address',
        'country',
        'director',
        'status',
    ];

    // Optional: If your table name is not plural 'hospitals', you can define it explicitly
    // protected $table = 'hospitals';

    // Optional: If you want Laravel to manage timestamps automatically
    public $timestamps = true;

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class, 'hospital_id');
    }
}
