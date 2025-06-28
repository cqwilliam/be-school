<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_user_id',
        'student_user_id',
        'class_session_id',
        'status',
        'justification',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

}
