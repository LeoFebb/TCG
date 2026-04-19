<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['chat_room_id', 'user_id', 'message', 'read'];

    public function user() { return $this->belongsTo(User::class); }
    public function room() { return $this->belongsTo(ChatRoom::class); }
}