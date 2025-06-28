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
        'student_user_id',
        'guardian_user_id',
        'relationship',
    ];

    /**
     * Relación con el estudiante (usuario).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    /**
     * Relación con el apoderado (usuario).
     */
    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_user_id');
    }
}
