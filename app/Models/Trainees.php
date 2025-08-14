<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * Get the trainee's image URL based on environment
     */
    public function getImageUrlAttribute()
    {
        $baseUrl = config('app.url');

        if (!$this->upload) {
            // Handle placeholder when no image is uploaded
            return app()->environment('local')
                ? $baseUrl . '/storage/app/public/trainees-photos/placeholder.jpg'
                : $baseUrl . '/storage/trainees-photos/placeholder.jpg';
        }

        // Handle actual uploaded images
        return app()->environment('local')
            ? $baseUrl . '/storage/app/public/' . $this->upload
            : Storage::url($this->upload);
    }
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
