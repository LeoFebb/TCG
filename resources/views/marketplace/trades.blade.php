@extends('layouts.app')

@section('title', 'Permute — TCG SafeSwap')

@section('content')

{{-- HEADER --}}
<div class="py-10 px-6 border-b border-purple-900/30"
     style="background: rgba(45, 17, 84, 0.2);">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Sezione Permute</p>
                <h1 class="text-3xl font-black text-white">
                    🔄 Scambia le tue carte
                    <span class="text-gray-600 font-normal text-xl ml-2">({{ $cards->total() }} disponibili)</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2">Proponi uno scambio con altri collezionisti — nessun pagamento richiesto</p>
            </div>
            @auth
                <a href="{{ route('cards.create') }}"
                   class="px-6 py-3 rounded-xl font-bold text-white text-sm"
                   style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    + Metti in permuta
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- COME FUNZIONA --}}
    <div class="rounded-xl p-6 border border-purple-900/50 mb-8"
         style="background: rgba(45, 17, 84, 0.2);">
        <h2 class="text-lg font-black text-white mb-5 text-center">Come funziona la permuta</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['num' => '01', 'icona' => '🔍', 'titolo' => 'Trova la carta',       'desc' => 'Sfoglia le carte in permuta.'],
                ['num' => '02', 'icona' => '🤝', 'titolo' => 'Proponi scambio',      'desc' => 'Offri una tua carta in cambio.'],
                ['num' => '03', 'icona' => '🔬', 'titolo' => 'Validazione',          'desc' => 'Entrambe le carte vengono verificate.'],
                ['num' => '04', 'icona' => '✅', 'titolo' => 'Scambio completato',   'desc' => 'Le carte arrivano ai nuovi proprietari.'],
            ] as $step)
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-xl"
                         style="background: rgba(124, 58, 237, 0.2); border: 1px solid rgba(168, 85, 247, 0.3);">
                        {{ $step['icona'] }}
                    </div>
                    <div class="text-purple-400 font-mono text-xs mb-1">STEP {{ $step['num'] }}</div>
                    <h3 class="text-white font-bold text-sm mb-1">{{ $step['titolo'] }}</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- GRID CARTE --}}
    @forelse($cards as $card)
        @if($loop->first)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-10">
        @endif

        <a href="{{ route('marketplace.show', $card) }}"
           class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group block"
           style="background: rgba(45, 17, 84, 0.3);">
            <div class="aspect-[2/3] bg-black/40 overflow-hidden relative">
                @if (count(is_array($card->images) ? $card->images : json_decode($card->images, true) ?? []))
                    <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : (str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0])) }}"
                         alt="{{ $card->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                        <span class="text-4xl">🃏</span>
                        <span class="text-gray-600 text-xs uppercase">{{ $card->tcg_category }}</span>
                    </div>
                @endif

                {{-- Badge permuta --}}
                <div class="absolute top-2 left-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                     style="background: rgba(124, 58, 237, 0.9);">
                    🔄 Trade
                </div>

                {{-- Badge prezzo se disponibile anche in vendita --}}
                @if($card->price)
                    <div class="absolute top-2 right-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                         style="background: rgba(16, 185, 129, 0.9);">
                        €{{ number_format($card->price, 2) }}
                    </div>
                @endif

                {{-- Overlay hover --}}
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"
                     style="background: rgba(124, 58, 237, 0.3);">
                    <span class="text-white text-sm font-bold">Vedi dettagli →</span>
                </div>
            </div>
            <div class="p-3">
                <p class="text-purple-400 text-xs font-mono uppercase mb-1">{{ $card->tcg_category }} · {{ $card->condition }}</p>
                <h3 class="text-white text-sm font-bold leading-tight truncate">{{ $card->name }}</h3>
                <p class="text-gray-600 text-xs truncate mt-0.5">{{ $card->set_name }}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-gray-600 text-xs">{{ $card->owner->name }}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded text-purple-400 border border-purple-900"
                          style="background: rgba(88, 28, 135, 0.3);">🔄 Trade</span>
                </div>
            </div>
        </a>

        @if($loop->last)
            </div>
        @endif
    @empty
        <div class="rounded-2xl p-20 text-center border border-purple-900/50"
             style="background: rgba(45, 17, 84, 0.2);">
            <div class="text-6xl mb-4">🔄</div>
            <h3 class="text-2xl font-bold text-white mb-2">Nessuna carta in permuta</h3>
            <p class="text-gray-500 mb-6">Sii il primo a mettere una carta in permuta!</p>
            @auth
                <a href="{{ route('cards.create') }}"
                   class="px-6 py-3 rounded-xl font-bold text-white inline-block"
                   style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    + Pubblica una carta
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="px-6 py-3 rounded-xl font-bold text-white inline-block"
                   style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    Registrati per partecipare
                </a>
            @endauth
        </div>
    @endforelse

    @if($cards->hasPages())
        <div class="flex justify-center mt-8">
            {{ $cards->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection






