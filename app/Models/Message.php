<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_user_id',
        'target_user_id',
        'content',
        'is_read'
    ];


    /**
     * El usuario que envió el mensaje.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * El usuario que recibió el mensaje.
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Reglas de validación para Message.
     */
    public static function rules(bool $updating = false): array
    {
        return [
            'sender_user_id' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id',
            'target_user_id' => ($updating ? 'sometimes|' : 'required|') . 'exists:users,id|different:sender_user_id',
            'content' => ($updating ? 'sometimes|' : 'required|') . 'string',
            'is_read' => 'sometimes|boolean',
        ];
    }
}
