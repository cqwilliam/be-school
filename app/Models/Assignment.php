<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'description',
        'due_date',
        'published_at',
        'published_by',
    ];

    /**
     * La sección del curso a la que pertenece esta tarea.
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * Las entregas realizadas por los estudiantes para esta tarea.
     */
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * El docente que publicó esta tarea.
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
