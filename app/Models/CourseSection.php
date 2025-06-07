<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'code',
        'classroom',
        'max_capacity',
    ];

    /**
     * Get the course associated with this section.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the enrollments for this section.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'section_id');
    }

    /**
     * Get the teachers assigned to this section.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section', 'section_id', 'teacher_id')
                    ->withPivot('is_primary');
    }

    /**
     * Get the schedules for this section.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'section_id');
    }

    /**
     * Get the assignments for this section.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'section_id');
    }

    /**
     * Get the course materials for this section.
     */
    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class, 'section_id');
    }

    /**
     * Get the class sessions for this section.
     */
    public function classSessions()
    {
        return $this->hasMany(ClassSession::class, 'section_id');
    }
}
