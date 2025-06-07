<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
    ];


    // === RELACIONES ===

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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

    // Publicaciones de anuncios
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    // Publicaciones de tareas
    public function assignmentsPublished()
    {
        return $this->hasMany(Assignment::class, 'published_by');
    }

    // Materiales publicados
    public function courseMaterialsPublished()
    {
        return $this->hasMany(CourseMaterial::class, 'published_by');
    }

    // Asistencia registrada
    public function attendanceRecorded()
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    // Calificaciones realizadas si es docente
    public function gradesGiven()
    {
        return $this->hasMany(Grade::class, 'graded_by');
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
}
