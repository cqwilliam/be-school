<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionPeriod extends Model
{
    use HasFactory;

    protected $table = 'sections_periods';

    protected $fillable = [
        'section_id',
        'period_id'
    ];
}
