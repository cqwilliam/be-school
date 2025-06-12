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

    // === RELACIONES ===

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


    // Rol
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Si el usuario es estudiante
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Si el usuario es docente
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    // Publicaciones de anuncios
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    // Publicaciones de tareas
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'published_by');
    }

    // Materiales publicados
    public function courseMaterialsPublished()
    {
        return $this->hasMany(CourseMaterial::class, 'published_by');
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class, 'created_by');
    }

    // Mensajes enviados
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Mensajes recibidos
    public function messagesReceived()
    {
        return $this->belongsToMany(Message::class, 'message_recipients', 'recipient_id', 'message_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'graded_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'graded_by');
    }
}
