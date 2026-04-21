@extends('layouts.app')
@section('title', $info['nome'] . ' — Carte in vendita | TCG SafeSwap')
@section('description', 'Acquista carte ' . $info['nome'] . ' certificate su TCG SafeSwap. ' . $info['desc'] . ' Pagamento
    sicuro con sistema escrow.')
@section('keywords', strtolower($info['nome']) . ', carte ' . strtolower($info['nome']) . ' rare, acquista ' .
    strtolower($info['nome']) . ', ' . strtolower($info['nome']) . ' marketplace')

@section('title', $info['nome'] . ' — TCG SafeSwap')

@section('content')

    {{-- HERO CATEGORIA --}}
    <div class="relative overflow-hidden py-16 px-6 text-center">

        {{-- Immagine di sfondo categoria --}}
        @if ($category === 'pokemon')
            <div class="absolute inset-0 "
                style="background-image: url('https://images.unsplash.com/photo-1613771404784-3a5686aa2be3?w=1200'); background-size: cover; background-position: center;">
            </div>
        @endif

        <div class="absolute inset-0"
            style="background: linear-gradient(180deg, rgba(45,17,84,0.7) 0%, rgba(0,0,0,0.9) 100%);">
        </div>

        <div class="relative z-10">

            <h1 class="text-4xl md:text-5xl font-black text-white mb-3">{{ $info['nome'] }}</h1>
            <p class="text-gray-400 text-lg mb-2">{{ $info['desc'] }}</p>
            <p class="text-gray-600 text-sm">{{ $cards->total() }} carte disponibili</p>
        </div>
    </div>

    {{-- FILTRI --}}
    <div class="max-w-7xl mx-auto px-3 md:px-6 py-4 md:py-6">
        <form method="GET" action="{{ route('marketplace.category', $category) }}"
            class="grid grid-cols-2 md:flex gap-2 flex-wrap items-center">
            <select name="type" onchange="this.form.submit()"
                class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2 rounded-lg text-sm focus:outline-none">
                <option value="">Vendita & Permuta</option>
                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Solo vendita</option>
                <option value="trade" {{ request('type') == 'trade' ? 'selected' : '' }}>Solo permuta</option>
            </select>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Prezzo max €"
                step="0.01"
                class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2 rounded-lg text-sm focus:outline-none w-36">
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Filtra
            </button>
            @if (request()->hasAny(['type', 'max_price']))
                <a href="{{ route('marketplace.category', $category) }}"
                    class="px-4 py-2 rounded-lg text-sm text-gray-400 border border-gray-700 hover:text-white transition">
                    Reset
                </a>
            @endif
            @auth
                <a href="{{ route('cards.create') }}" class="ml-auto px-4 py-2 rounded-lg text-sm font-semibold text-white"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    + Vendi una carta
                </a>
            @endauth
        </form>
    </div>

    {{-- GRID CARTE --}}
    <div class="max-w-7xl mx-auto px-6 pb-16">
        @forelse($cards as $card)
            @if ($loop->first)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-10">
            @endif

            <a href="{{ route('marketplace.show', $card) }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group block"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="aspect-[2/3] bg-black/40 overflow-hidden relative">
                    @if ($card->images && count($card->images) > 0)
                        <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : (str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0])) }}"
                            alt="{{ $card->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <span class="text-4xl">{{ $info['icona'] }}</span>
                        </div>
                    @endif
                    @if ($card->price)
                        <div class="absolute top-2 right-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                            style="background: rgba(124, 58, 237, 0.9);">
                            €{{ number_format($card->price, 2) }}
                        </div>
                    @endif
                    @if ($card->available_for_trade)
                        <div class="absolute top-2 left-2 px-2 py-1 rounded-lg text-xs font-bold text-white"
                            style="background: rgba(16, 185, 129, 0.9);">
                            🔄
                        </div>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-purple-400 text-xs font-mono uppercase mb-1">{{ $card->condition }}</p>
                    <h3 class="text-white text-sm font-bold leading-tight truncate">{{ $card->name }}</h3>
                    <p class="text-gray-600 text-xs truncate mt-0.5">{{ $card->set_name }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-gray-600 text-xs">{{ $card->owner->name }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded text-green-400 border border-green-900"
                            style="background: rgba(6, 78, 59, 0.3);">✓</span>
                    </div>
                </div>
            </a>

            @if ($loop->last)
    </div>
    @endif
@empty
    <div class="rounded-2xl p-20 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.2);">

        <h3 class="text-2xl font-bold text-white mb-2">Nessuna carta {{ $info['nome'] }} disponibile</h3>
        <p class="text-gray-500 mb-6">Sii il primo a pubblicare una carta in questa categoria!</p>
        @auth
            <a href="{{ route('cards.create') }}" class="px-6 py-3 rounded-xl font-bold text-white inline-block"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                + Pubblica una carta
            </a>
        @endauth
    </div>
    @endforelse

    @if ($cards->hasPages())
        <div class="flex justify-center mt-8">
            {{ $cards->withQueryString()->links() }}
        </div>
    @endif
    </div>

@endsection

