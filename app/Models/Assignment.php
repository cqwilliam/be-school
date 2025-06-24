<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'section_period_id',
        'title',
        'description',
        'due_date',
    ];

    /**
     * La sección del curso a la que pertenece esta tarea.
     */
    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
