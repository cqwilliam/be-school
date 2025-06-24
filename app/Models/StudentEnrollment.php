<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'section_period_id',
        'status',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function sectionPeriod()
    {
        return $this->belongsTo(SectionPeriod::class, 'section_period_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
