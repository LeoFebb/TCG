@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-black text-white mb-6">💬 Chat Validatori</h1>

        @if ($rooms->isEmpty())
            <div class="rounded-xl p-10 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.1);">
                <p class="text-gray-500 text-sm">Nessuna chat attiva al momento.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($rooms as $room)
                    @php $other = $room->user_1_id === Auth::id() ? $room->user2 : $room->user1; @endphp
                    <a href="{{ route('chat.show', $room) }}"
                        class="block rounded-xl border border-purple-900/50 p-4 hover:border-purple-500 transition"
                        style="background: rgba(45,17,84,0.2);">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white font-bold">{{ $other->name }}</p>
                                <p class="text-gray-500 text-sm">Transazione #{{ $room->transaction_id }}@if ($room->transaction && $room->transaction->card)
                                        — {{ $room->transaction->card->name }}
                                    @endif
                                </p>
                                @if ($room->messages->first())
                                    <p class="text-gray-600 text-xs mt-1">
                                        {{ Str::limit($room->messages->first()->message, 60) }}</p>
                                @endif
                            </div>
                            @php $unread = $room->messages->where('user_id', '!=', Auth::id())->where('read', false)->count(); @endphp
                            @if ($unread > 0)
                                <span class="px-2 py-1 rounded-full text-xs font-bold text-white"
                                    style="background: #7c3aed;">{{ $unread }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
