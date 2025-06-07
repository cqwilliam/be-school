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
    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }

    /**
     * Get all attendance records for this session.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getDurationInMinutesAttribute()
    {
        return \Carbon\Carbon::parse($this->end_time)->diffInMinutes($this->start_time);
    }

    public function scopeOnDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
