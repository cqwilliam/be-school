<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_url',
        'comment',
        'submitted_at',
        'grade',
        'feedback',
        'graded_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'decimal:2',
    ];

    /**
     * La tarea a la que corresponde esta entrega.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * El estudiante que realizó esta entrega.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El usuario que calificó esta entrega.
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
