<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_user_id',
        'period_section_id',
        'topic',
        'date',
        'start_time',
        'end_time',
    ];
    // protected $casts = [
    //     'date' => 'date',
    //     'start_time' => 'date',
    //     'end_time' => 'date',
    // ];


    /**
     * Get the section this class session belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodSection()
    {
        return $this->belongsTo(PeriodSection::class, 'period_section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
