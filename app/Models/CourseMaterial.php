<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'url',
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
            'published_by' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id',
        ];

        return $rules;
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
