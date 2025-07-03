<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function sectionCourses()
    {
        return $this->hasMany(SectionCourse::class, 'section_id');
    }
}
