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
        'start_time',
        'end_time',
        'is_recurring',
        'specific_date',
    ];

    protected $casts = [
        'start_time' => 'date',
        'end_time' => 'date',
        'specific_date' => 'date',
        'is_recurring' => 'boolean',
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
