<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGuardian extends Model
{
    use HasFactory;

    // Nombre explícito de la tabla (porque es una tabla pivote personalizada)
    protected $table = 'students_guardians';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'student_id',
        'guardian_id',
        'relationship',
    ];

    /**
     * Relación con el estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relación con el apoderado.
     */
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }
}
