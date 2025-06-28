<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SectionCourse;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_section_id',
        'evaluation_type_id',
        'teacher_user_id',
        'title',
        'description',
        'due_date'
    ];

    protected $casts = [
        'weight' => 'float',
    ];
    
    public static function validateTotalWeight($newWeight, $periodSectionId, $excludeId = null)
    {
        $total = self::where('period_section_id', $periodSectionId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->sum('weight');

        if (($total + $newWeight) > 100) {
            abort(response()->json([
                'message' => 'Total weight of evaluations for this section and period cannot exceed 100%'
            ], 422));
        }
    }

    /**
     * Get the section this evaluation belongs to.
     */
    public function periodSection()
    {
        return $this->belongsTo(PeriodSection::class, 'period_section_id');
    }

    /**
     * Get the type of this evaluation.
     */
    public function evaluationType()
    {
        return $this->belongsTo(EvaluationType::class, 'evaluation_type_id');
    }

    /**
     * Get the academic period of the evaluation.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }

    /**
     * Get the grades assigned to this evaluation.
     */
    public function grades()
    {
        return $this->hasMany(EvaluationGrade::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(SectionCourse::class);
    }
}
