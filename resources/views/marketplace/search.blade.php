@extends('layouts.app')

@section('title', 'Risultati per "' . $query . '" — TCG SafeSwap')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-black text-white mb-1">
            Risultati per <span class="text-purple-400">"{{ $query }}"</span>
        </h1>
        <p class="text-gray-500">{{ $cards->total() }} carte trovate</p>
    </div>

    @forelse($cards as $card)
        @if($loop->first)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @endif

        <a href="{{ route('marketplace.show', $card) }}"
           class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group block"
           style="background: rgba(45, 17, 84, 0.3);">
            <div class="aspect-[2/3] bg-black/40 overflow-hidden relative">
                @if($card->images && count($card->images) > 0)
                    <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : (str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0])) }}"
                         alt="{{ $card->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-4xl">🃏</span>
                    </div>
                @endif
                @if($card->price)
                    <div class="absolute top-2 right-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                         style="background: rgba(124, 58, 237, 0.9);">
                        €{{ number_format($card->price, 2) }}
                    </div>
                @endif
                @if($card->available_for_trade)
                    <div class="absolute top-2 left-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                         style="background: rgba(16, 185, 129, 0.9);">
                        🔄
                    </div>
                @endif
            </div>
            <div class="p-3">
                <p class="text-purple-400 text-xs font-mono uppercase mb-1">{{ $card->tcg_category }} · {{ $card->condition }}</p>
                <h3 class="text-white text-sm font-bold leading-tight truncate">{{ $card->name }}</h3>
                <p class="text-gray-600 text-xs truncate mt-0.5">{{ $card->set_name }}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-gray-600 text-xs">{{ $card->owner->name }}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded text-green-400 border border-green-900"
                          style="background: rgba(6, 78, 59, 0.3);">✓</span>
                </div>
            </div>
        </a>

        @if($loop->last)
            </div>
        @endif
    @empty
        <div class="rounded-2xl p-20 text-center border border-purple-900/50"
             style="background: rgba(45, 17, 84, 0.2);">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-bold text-white mb-2">Nessun risultato per "{{ $query }}"</h3>
            <p class="text-gray-500 mb-6">Prova con un termine diverso o sfoglia le categorie.</p>
            <a href="{{ route('home') }}"
               class="px-6 py-3 rounded-xl font-bold text-white inline-block"
               style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Torna alla home
            </a>
        </div>
    @endforelse

    @if($cards->hasPages())
        <div class="flex justify-center mt-8">
            {{ $cards->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection






