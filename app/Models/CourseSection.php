<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = 'courses_sections';

    protected $fillable = [
        'course_id',
        'section_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function studentEnrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'section_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_sections', 'section_id', 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments', 'section_id', 'student_id');
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
