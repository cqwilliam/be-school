<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSection extends Model
{
    use HasFactory;

    protected $table = 'teacher_sections';

    protected $fillable = [
        'teacher_id',
        'section_id',
        'is_primary',
    ];

    /**
     * Get the teacher assigned to the section.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the course section assigned to the teacher.
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
    /**
     * Get the course associated through the section.
     */
    public function course()
    {
        return $this->section->course; // solo funciona si la sección tiene relación con Course
    }
}
