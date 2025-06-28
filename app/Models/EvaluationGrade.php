<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'student_user_id',
        'grade',
        'comment',
        'graded_by'
    ];

    protected $casts = [
        'grade' => 'float',
    ];

    public function scopeForStudent($query, $studentUserId)
    {
        return $query->where('student_user_id', $studentUserId);
    }

    public function scopeForEvaluation($query, $evaluationId)
    {
        return $query->where('evaluation_id', $evaluationId);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
