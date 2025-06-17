<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'day_of_week',
        'start_date',
        'end_date',
        'is_recurring',
        'specific_date',
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
