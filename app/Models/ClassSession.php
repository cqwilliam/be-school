<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'topic',
        'date',
        'start_time',
        'end_time',
        'created_by'
    ];
    protected $casts = [
        'date' => 'date',
        'start_time' => 'date',
        'end_time' => 'date',
    ];


    /**
     * Get the section this class session belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
