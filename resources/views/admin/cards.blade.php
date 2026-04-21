@extends('layouts.admin')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
        
        <h1 class="text-2xl font-black text-white">🃏 Gestione Carte</h1>
    </div>

    <div class="space-y-3">
        @foreach($cards as $card)
        <div class="rounded-xl border border-purple-900/50 p-4 flex items-center justify-between" style="background: rgba(45,17,84,0.2);">
            <div class="flex items-center gap-4">
                @if($card->images && count($card->images) > 0)
                    <img src="{{ Storage::url($card->images[0]) }}" class="w-12 h-16 object-cover rounded-lg">
                @endif
                <div>
                    <p class="text-white font-bold">{{ $card->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $card->tcg_category }} · {{ $card->condition }} · €{{ $card->price }}</p>
                    <p class="text-gray-600 text-xs">Venditore: {{ $card->owner->name }}</p>
@php
    $lastTx = $card->transactions->first();
@endphp
@if($lastTx)
    <p class="text-gray-600 text-xs mt-1">
        Ultima transazione: 
        <span class="text-purple-400">{{ $lastTx->type === 'trade' ? '🔄 Permuta' : '💰 Vendita' }}</span>
        · <span class="text-yellow-400">{{ $lastTx->status }}</span>
        · {{ $lastTx->created_at->format('d/m/Y') }}
    </p>
@endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
    $hasActiveTx = $card->transactions->whereNotIn('status', ['rejected', 'disputed'])->whereNotNull('status')->count() > 0;
@endphp
@if(!$hasActiveTx)
    @if($card->status === 'unavailable' || !$card->is_validated)
                    <span class="px-3 py-1 rounded-full text-xs text-red-400 border border-red-800/50">Rimossa</span>
                    <form method="POST" action="{{ route('admin.cards.restore', $card) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.5);">
                            ✅ Ripristina
                        </button>
                    </form>
                @else
                    <span class="px-3 py-1 rounded-full text-xs text-green-400 border border-green-800/50">Attiva</span>
                    <form method="POST" action="{{ route('admin.cards.remove', $card) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white" style="background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.5);">
                            🗑 Rimuovi
                        </button>
                    </form>
                @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $cards->links() }}</div>
</div>
@endsection