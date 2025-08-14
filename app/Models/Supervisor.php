<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get the supervisor's image URL based on environment
     */
    public function getImageUrlAttribute()
    {
        $baseUrl = config('app.url');
        
        if (!$this->avatar) {
            // Handle placeholder when no image is uploaded
            return app()->environment('local') 
                ? $baseUrl . '/storage/app/public/supervisors-photos/placeholder.jpg'
                : $baseUrl . '/storage/supervisors-photos/placeholder.jpg';
        }
        
        // Handle actual uploaded images
        return app()->environment('local') 
            ? $baseUrl . '/storage/app/public/' . $this->avatar
            : $baseUrl . '/storage/' . $this->avatar;
    }

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