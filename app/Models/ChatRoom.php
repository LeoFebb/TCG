<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['transaction_id', 'user_1_id', 'user_2_id'];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function user1() { return $this->belongsTo(User::class, 'user_1_id'); }
    public function user2() { return $this->belongsTo(User::class, 'user_2_id'); }
    public function messages() { return $this->hasMany(ChatMessage::class); }
}