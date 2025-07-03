<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodSection extends Model
{
    use HasFactory;

    protected $table = 'periods_sections';

    protected $fillable = [
        'section_id',
        'period_id',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'period_section_id');
    }
}
