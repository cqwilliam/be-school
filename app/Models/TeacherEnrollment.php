<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEnrollment extends Model
{
    use HasFactory;

    protected $table = 'teacher_enrollment';


    protected $fillable = [
        'teacher_id',
        'section_period_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function sectionPeriod()
    {
        return $this->belongsTo(SectionPeriod::class, 'section_period_id');
    }
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            SectionPeriod::class,
            'id',             // Foreign key en section_periods
            'id',             // Foreign key en courses
            'section_period_id', // Local key en teacher_enrollments
            'course_id'       // Local key en section_periods
        );
    }
}
