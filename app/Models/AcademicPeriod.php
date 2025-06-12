<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'active' // opcional si quieres permitir modificarlo desde código
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all enrollments for this academic period.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get all evaluations during this academic period.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get all class sessions for this academic period.
     */
    public function sessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
