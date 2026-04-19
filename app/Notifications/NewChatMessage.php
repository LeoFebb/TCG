<?php
namespace App\Notifications;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(public ChatMessage $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => $this->message->message,
            'sender' => $this->message->user->name,
            'chat_room_id' => $this->message->chat_room_id,
            'transaction_id' => $this->message->room?->transaction_id,
        ];
    }
}