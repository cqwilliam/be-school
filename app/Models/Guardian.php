<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    /**
     * The students that belong to the guardian.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_guardians');
    }
    /**
     * Get the user record associated with the guardian.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
