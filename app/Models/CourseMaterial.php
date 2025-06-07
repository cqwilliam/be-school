<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'description',
        'type',
        'url',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'type' => 'string',
        'url' => 'string',
    ];


    public const TYPE_DOCUMENT = 'Document';
    public const TYPE_VIDEO = 'Video';
    public const TYPE_LINK = 'Link';
    public const TYPE_OTHER = 'Other';

    public static function getTypes(): array
    {
        return [
            self::TYPE_DOCUMENT,
            self::TYPE_VIDEO,
            self::TYPE_LINK,
            self::TYPE_OTHER,
        ];
    }

    /**
     * La sección del curso a la que pertenece este material.
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * El docente que publicó este material.
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Reglas de validación para CourseMaterial.
     */
    public static function rules(bool $updating = false): array
    {
        $rules = [
            'section_id' => ($updating ? 'sometimes|' : 'required|') . 'exists:course_sections,id',
            'title' => ($updating ? 'sometimes|' : 'required|') . 'string|max:100',
            'description' => 'nullable|string',
            'type' => ($updating ? 'sometimes|' : 'required|') . 'in:' . implode(',', self::getTypes()),
            'url' => ($updating ? 'sometimes|' : 'required|') . 'string',
            'published_at' => 'nullable|date',
            'published_by' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id',
        ];


        return $rules;
    }
}
