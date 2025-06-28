<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_user_id',
        'period_section_id', 
        'title',
        'description',
        'due_date',
    ];

    /**
     * La sección del curso a la que pertenece esta tarea.
     */
    public function periodSection()
    {
        return $this->belongsTo(PeriodSection::class, 'period_section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
