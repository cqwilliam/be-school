<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionCourse extends Model
{
    use HasFactory;
    protected $table = 'sections_courses';

    protected $fillable = [
        'course_id',
        'section_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'section_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'section_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'section_id');
    }

    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class, 'section_id');
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class, 'section_id');
    }
}
