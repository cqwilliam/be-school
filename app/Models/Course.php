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
    ];

    public function academicPeriod()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function courseSections()
    {
        return $this->hasMany(CourseSection::class, 'section_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluation_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assignment_id');
    }


    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'course_id');
    }
}
