<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    public const STATUSES = ['active', 'withdrawn', 'suspended'];

    protected $fillable = [
        'student_id',
        'section_id',
        'academic_period_id',
        'enrolled_at',
        'status',
    ];
    // En Enrollment.php

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /**
     * Get the student for this enrollment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course section for this enrollment.
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }

    /**
     * Get the academic period for this enrollment.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
