<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'evaluation_id',
        'score',
        'graded_by',
        'comment',
        'graded_at',
    ];

    protected $casts = [
        'score' => 'float',
        'graded_at' => 'datetime',
    ];

    /**
     * Scope to filter grades by student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope to filter grades by evaluation.
     */
    public function scopeForEvaluation($query, $evaluationId)
    {
        return $query->where('evaluation_id', $evaluationId);
    }

    /**
     * User (or Teacher) who graded this.
     * Replace User::class with Teacher::class if needed.
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Student who received the grade.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Evaluation this grade belongs to.
     */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
