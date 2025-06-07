<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'evaluation_type_id',
        'academic_period_id',
        'title',
        'description',
        'weight',
        'date',
        'due_date'
    ];

    protected $casts = [
        'weight' => 'float',
        'date' => 'date',
        'due_date' => 'datetime',
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
     * Scope to get upcoming evaluations.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', now())->orderBy('due_date');
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
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    /**
     * Get the grades assigned to this evaluation.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
