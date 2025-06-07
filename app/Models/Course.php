<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'credits',
        'academic_period_id',
    ];

    /**
     * Get the academic period this course belongs to.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * Get the sections of the course.
     */
    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    /**
     * Get the evaluations of the course.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the assignments of the course.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the course materials.
     */
    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class);
    }
}
