<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'dni',
        'birth_date',
        'photo_url',
        'phone',
        'address',
        'last_sign_in',
        'role_id',
    ];

    protected $casts = [
        'last_sign_in' => 'datetime',
        'birth_date' => 'date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        'role_name',
        'age_name',
    ];

    // Solo para obtener nombres de guardianes con relación
    public function getGuardiansNamesAttribute()
    {
        return $this->guardians->map(function ($guardian) {
            return $guardian->full_name . ' (' . $guardian->pivot->relationship . ')';
        })->implode(', ');
    }

    // Solo para obtener teléfonos de guardianes
    public function getGuardiansPhonesAttribute()
    {
        return $this->guardians->pluck('phone')->filter()->implode(', ');
    }

    // Información completa del primer guardián (si solo necesitas uno)
    public function getPrimaryGuardianAttribute()
    {
        $guardian = $this->guardians->first();
        return $guardian ? [
            'name' => $guardian->full_name,
            'phone' => $guardian->phone,
            'relationship' => $guardian->pivot->relationship
        ] : null;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->name : null;
    }

    public function getAgeNameAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'published_by');
    }

    public function courseMaterialsPublished()
    {
        return $this->hasMany(CourseMaterial::class, 'published_by');
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class, 'created_by');
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->belongsToMany(Message::class, 'message_recipients', 'recipient_id', 'message_id');
    }

    public function grades()
    {
        return $this->hasMany(EvaluationGrade::class, 'graded_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'graded_by');
    }

    public function studentGuardianRelationsAsStudent()
    {
        return $this->hasMany(StudentGuardian::class, 'student_user_id');
    }

    public function studentGuardianRelationsAsGuardian()
    {
        return $this->hasMany(StudentGuardian::class, 'guardian_user_id');
    }

    public function guardians()
    {
        return $this->belongsToMany(User::class, 'students_guardians', 'student_user_id', 'guardian_user_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'students_guardians', 'guardian_user_id', 'student_user_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function periodSectionUsers()
    {
        return $this->hasMany(PeriodSectionUser::class, 'user_id');
    }
}
