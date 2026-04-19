@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('chat.index') }}" class="text-purple-400 hover:text-purple-300">← Chat</a>
        <h1 class="text-xl font-black text-white">💬 {{ $other->name }}</h1>
        <span class="text-gray-500 text-sm">— Transazione #{{ $room->transaction_id }}</span>
    </div>

    <div class="rounded-xl border border-purple-900/50 mb-4 p-4 space-y-4" style="background: rgba(45,17,84,0.15); min-height: 400px;">
        @forelse($messages as $msg)
            <div class="flex {{ $msg->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs rounded-xl px-4 py-2 {{ $msg->user_id === Auth::id() ? 'bg-purple-700 text-white' : 'bg-gray-800 text-gray-200' }}">
                    <p class="text-xs font-bold mb-1 opacity-70">{{ $msg->user->name }}</p>
                    <p class="text-sm">{{ $msg->message }}</p>
                    <p class="text-xs opacity-50 mt-1 text-right">{{ $msg->created_at->format('H:i') }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-sm text-center pt-10">Nessun messaggio. Inizia la conversazione!</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('chat.send', $room) }}" class="flex gap-3">
        @csrf
        <input type="text" name="message" placeholder="Scrivi un messaggio..." required
               class="flex-1 px-4 py-3 rounded-xl text-white text-sm border"
               style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
        <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white text-sm"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
            Invia
        </button>
    </form>
</div>
@endsection