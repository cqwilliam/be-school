<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'target',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public const TARGET_GENERAL = 'General';
    public const TARGET_STUDENTS = 'Students';
    public const TARGET_TEACHERS = 'Teachers';
    public const TARGET_GUARDIANS = 'Guardians';

    public static function getTargets(): array
    {
        return [
            self::TARGET_GENERAL,
            self::TARGET_STUDENTS,
            self::TARGET_TEACHERS,
            self::TARGET_GUARDIANS,
        ];
    }

    /**
     * La sección a la que está dirigido el anuncio (puede ser null para anuncios generales).
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * El usuario (docente, administrador, etc.) que publicó el anuncio.
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Reglas de validación para Announcement.
     */
    public static function rules(bool $updating = false): array
    {
        return [
            'section_id' => 'nullable|exists:course_sections,id',
            'title' => ($updating ? 'sometimes|' : 'required|') . 'string|max:255',
            'content' => ($updating ? 'sometimes|' : 'required|') . 'string',
            'target' => ($updating ? 'sometimes|' : 'required|') . 'in:' . implode(',', self::getTargets()),
            'published_at' => 'nullable|date',
            'published_by' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id',
        ];
    }
}
