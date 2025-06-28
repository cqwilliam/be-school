<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodSectionUser extends Model
{
    use HasFactory;

    protected $table = 'periods_sections_users';

    protected $fillable = [
        'user_id',
        'period_section_id', 
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periodSection()
    {
        return $this->belongsTo(PeriodSection::class);
    }
}
