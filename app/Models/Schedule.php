<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_period_id',
        'course_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];


    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }
    
    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
}
