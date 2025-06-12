<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    // === ATTRIBUTES ===

    protected $fillable = [
        'user_id',
        'grade',
        'section',
    ];

    // === RELATIONS ===

    /**
     * Get the user associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The guardians associated with the student.
     */
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'students_guardians');
    }

    /**
     * Get the enrollments of the student.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function sections()
    {
        return $this->belongsToMany(CourseSection::class, 'enrollments');
    }
    /**
     * Get the grades received by the student.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendance records of the student.
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the assignment submissions made by the student.
     */
    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
