<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_section_id',
        'course_id',
        'teacher_user_id',
        'day_of_week', 
        'start_time',
        'end_time',
    ];


    public function periodSection()
    {
        return $this->belongsTo(PeriodSection::class, 'period_section_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }
}
