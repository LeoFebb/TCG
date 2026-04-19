<?php
namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::where('user_1_id', Auth::id())
            ->orWhere('user_2_id', Auth::id())
            ->with(['transaction.card', 'user1', 'user2', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()
            ->get();

        return view('chat.index', compact('rooms'));
    }

    public function show(ChatRoom $room)
    {
        abort_if($room->user_1_id !== Auth::id() && $room->user_2_id !== Auth::id(), 403);

        $messages = $room->messages()->with('user')->orderBy('created_at', 'asc')->get();
        $room
            ->messages()
            ->where('user_id', '!=', Auth::id())
            ->update(['read' => true]);
            // Cancella le notifiche lette
Auth::user()->notifications()
    ->where('type', 'App\Notifications\NewChatMessage')
    ->where('data->chat_room_id', $room->id)
    ->delete();
        $other = $room->user_1_id === Auth::id() ? $room->user2 : $room->user1;

        return view('chat.show', compact('room', 'messages', 'other'));
    }

    public function send(Request $request, ChatRoom $room)
    {
        abort_if($room->user_1_id !== Auth::id() && $room->user_2_id !== Auth::id(), 403);

        $request->validate(['message' => 'required|string|max:1000']);

        $message = ChatMessage::create([
            'chat_room_id' => $room->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $message->load(['user', 'room.transaction']);

        // Notifica l'altro validatore
        $other = $room->user_1_id === Auth::id() ? $room->user2 : $room->user1;
        $other->notify(new \App\Notifications\NewChatMessage($message));

        return redirect()->back();
    }
}
