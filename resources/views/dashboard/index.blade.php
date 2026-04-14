@extends('layouts.app')

@section('title', 'Dashboard — TCG Vault')

@section('content')

{{-- Header --}}
<div class="py-10 px-6 border-b border-purple-900/30"
     style="background: rgba(45, 17, 84, 0.2);">
    <div class="max-w-7xl mx-auto">
        <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Account</p>
        <h1 class="text-3xl font-black text-white">
            Ciao, {{ Auth::user()->name }}! 👋
        </h1>
        <p class="text-gray-500 text-sm mt-1">Ecco un riepilogo della tua attività su TCG Vault</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Statistiche --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="rounded-xl p-5 border border-purple-900/50 text-center"
             style="background: rgba(45,17,84,0.3);">
            <p class="text-3xl font-black text-purple-400">{{ $stats['carte_pubblicate'] }}</p>
            <p class="text-gray-500 text-xs mt-1">Carte pubblicate</p>
        </div>
        <div class="rounded-xl p-5 border border-purple-900/50 text-center"
             style="background: rgba(45,17,84,0.3);">
            <p class="text-3xl font-black text-green-400">{{ $stats['carte_disponibili'] }}</p>
            <p class="text-gray-500 text-xs mt-1">Carte disponibili</p>
        </div>
        <div class="rounded-xl p-5 border border-purple-900/50 text-center"
             style="background: rgba(45,17,84,0.3);">
            <p class="text-3xl font-black text-yellow-400">{{ $stats['vendite_attive'] }}</p>
            <p class="text-gray-500 text-xs mt-1">Vendite attive</p>
        </div>
        <div class="rounded-xl p-5 border border-purple-900/50 text-center"
             style="background: rgba(45,17,84,0.3);">
            <p class="text-3xl font-black text-blue-400">{{ $stats['acquisti_attivi'] }}</p>
            <p class="text-gray-500 text-xs mt-1">Acquisti attivi</p>
        </div>
    </div>

    {{-- Guadagni e spese --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
        <div class="rounded-xl p-6 border border-green-900/50"
             style="background: rgba(6,78,59,0.1);">
            <p class="text-green-400 text-xs font-mono uppercase tracking-widest mb-2">💰 Guadagni totali</p>
            <p class="text-4xl font-black text-green-400">€{{ number_format($stats['guadagni_totali'], 2) }}</p>
            <p class="text-gray-500 text-xs mt-1">Da {{ $stats['vendite_completate'] }} vendite completate</p>
        </div>
        <div class="rounded-xl p-6 border border-red-900/50"
             style="background: rgba(127,29,29,0.1);">
            <p class="text-red-400 text-xs font-mono uppercase tracking-widest mb-2">🛒 Spese totali</p>
            <p class="text-4xl font-black text-red-400">€{{ number_format($stats['spese_totali'], 2) }}</p>
            <p class="text-gray-500 text-xs mt-1">Acquisti completati</p>
        </div>
    </div>

    {{-- Link rapidi --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <a href="{{ route('cards.create') }}"
           class="rounded-xl p-5 border border-purple-900/50 text-center hover:border-purple-500 transition"
           style="background: rgba(45,17,84,0.3);">
            <div class="text-3xl mb-2">➕</div>
            <p class="text-white text-sm font-bold">Pubblica carta</p>
        </a>
        <a href="{{ route('cards.my') }}"
           class="rounded-xl p-5 border border-purple-900/50 text-center hover:border-purple-500 transition"
           style="background: rgba(45,17,84,0.3);">
            <div class="text-3xl mb-2">🃏</div>
            <p class="text-white text-sm font-bold">Le mie carte</p>
        </a>
        <a href="{{ route('transactions.index') }}"
           class="rounded-xl p-5 border border-purple-900/50 text-center hover:border-purple-500 transition"
           style="background: rgba(45,17,84,0.3);">
            <div class="text-3xl mb-2">📦</div>
            <p class="text-white text-sm font-bold">Le mie transazioni</p>
        </a>
        <a href="{{ route('marketplace.index') }}"
           class="rounded-xl p-5 border border-purple-900/50 text-center hover:border-purple-500 transition"
           style="background: rgba(45,17,84,0.3);">
            <div class="text-3xl mb-2">🛒</div>
            <p class="text-white text-sm font-bold">Marketplace</p>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Ultime vendite --}}
        <div>
            <h2 class="text-xl font-black text-white mb-4">📤 Ultime vendite</h2>
            @forelse($ultimoVendite as $t)
                <div class="rounded-xl border border-purple-900/50 p-4 mb-3 flex justify-between items-center"
                     style="background: rgba(45,17,84,0.2);">
                    <div>
                        <p class="text-white font-bold text-sm">{{ $t->card->name }}</p>
                        <p class="text-gray-500 text-xs">Acquirente: {{ $t->buyer->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-purple-400 font-bold">€{{ number_format($t->amount, 2) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            @if($t->status === 'completed') bg-green-900/50 text-green-400
                            @elseif($t->status === 'paid_escrow') bg-yellow-900/50 text-yellow-400
                            @elseif($t->status === 'in_validation') bg-purple-900/50 text-purple-400
                            @else bg-gray-900/50 text-gray-400
                            @endif">
                            {{ $t->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Nessuna vendita ancora.</p>
            @endforelse
        </div>

        {{-- Ultimi acquisti --}}
        <div>
            <h2 class="text-xl font-black text-white mb-4">📥 Ultimi acquisti</h2>
            @forelse($ultimiAcquisti as $t)
                <div class="rounded-xl border border-purple-900/50 p-4 mb-3 flex justify-between items-center"
                     style="background: rgba(45,17,84,0.2);">
                    <div>
                        <p class="text-white font-bold text-sm">{{ $t->card->name }}</p>
                        <p class="text-gray-500 text-xs">Venditore: {{ $t->seller->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-purple-400 font-bold">€{{ number_format($t->amount, 2) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            @if($t->status === 'completed') bg-green-900/50 text-green-400
                            @elseif($t->status === 'paid_escrow') bg-yellow-900/50 text-yellow-400
                            @elseif($t->status === 'in_validation') bg-purple-900/50 text-purple-400
                            @else bg-gray-900/50 text-gray-400
                            @endif">
                            {{ $t->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Nessun acquisto ancora.</p>
            @endforelse
        </div>
    </div>

    {{-- Ultime carte pubblicate --}}
    <div class="mt-8">
        <h2 class="text-xl font-black text-white mb-4">🃏 Ultime carte pubblicate</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @forelse($ultimeCarte as $card)
                <a href="{{ route('marketplace.show', $card) }}"
                   class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition"
                   style="background: rgba(45,17,84,0.3);">
                    <div class="aspect-[2/3] bg-black/40 overflow-hidden">
                        @if($card->images && count($card->images) > 0)
                            <img src="{{ (str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0])) }}"
                                 alt="{{ $card->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-3xl">🃏</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-2">
                        <p class="text-white text-xs font-bold truncate">{{ $card->name }}</p>
                        @if($card->price)
                            <p class="text-purple-400 text-xs">€{{ number_format($card->price, 2) }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-sm col-span-5">Nessuna carta pubblicata ancora.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
