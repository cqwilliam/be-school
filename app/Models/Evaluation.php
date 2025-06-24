<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_period_id',
        'evaluation_type_id',
        'title',
        'description',
        'due_date'
    ];

    protected $casts = [
        'weight' => 'float',
    ];
    
    public static function validateTotalWeight($newWeight, $sectionId, $periodId, $excludeId = null)
    {
        $total = self::where('section_id', $sectionId)
            ->where('academic_period_id', $periodId)
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
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
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
    public function academicPeriod()
    {
        return $this->belongsTo(Period::class, 'academic_period_id');
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
        return $this->belongsTo(CourseSection::class);
    }
}
