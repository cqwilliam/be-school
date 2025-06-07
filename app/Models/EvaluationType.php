<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'weight'];

    protected $casts = [
        'weight' => 'float',
    ];

    /**
     * Get all evaluations of this type.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Scope to order evaluation types by weight descending.
     */
    public function scopeOrderedByWeight($query)
    {
        return $query->orderBy('weight', 'desc');
    }

    const MAX_TOTAL_WEIGHT = 100;

    public static function validateTotalWeight($newWeight, $excludeId = null)
    {
        $total = self::when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->sum('weight');
        if (($total + $newWeight) > self::MAX_TOTAL_WEIGHT) {
            abort(response()->json([
                'message' => 'Total weight of evaluation types cannot exceed ' . self::MAX_TOTAL_WEIGHT . '%'
            ], 422));
        }
    }
}
