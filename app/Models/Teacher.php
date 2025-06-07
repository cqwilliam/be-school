<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'academic_degree',
    ];

    /**
     * Relación con el usuario base.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Secciones en las que enseña.
     */
    public function sections()
    {
        return $this->belongsToMany(CourseSection::class, 'teacher_section', 'teacher_id', 'section_id')
                    ->withPivot('is_primary');
    }

    /**
     * Tareas publicadas por el docente.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'published_by');
    }

    /**
     * Materiales de curso publicados por el docente.
     */
    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class, 'published_by');
    }

    /**
     * Calificaciones asignadas por el docente.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'graded_by');
    }
}
