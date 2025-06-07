<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'sent_at',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * El usuario que envió el mensaje.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * El usuario que recibió el mensaje.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Reglas de validación para Message.
     */
    public static function rules(bool $updating = false): array
    {
        return [
            'sender_id' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id',
            'recipient_id' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id|different:sender_id',
            'content' => ($updating ? 'sometimes|' : 'required|') . 'string',
            'sent_at' => 'nullable|date',
            'is_read' => 'sometimes|boolean',
            'read_at' => 'nullable|date',
        ];
    }
}
