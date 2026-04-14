@extends('layouts.app')
@section('title', 'Marketplace — Tutte le carte | TCG Vault')
@section('description',
    'Sfoglia migliaia di carte da gioco disponibili su TCG Vault. Pokémon, Magic, Yu-Gi-Oh!, One
    Piece e molto altro. Acquisto sicuro con sistema escrow.')
@section('keywords',
    'marketplace carte, acquista carte pokemon, magic the gathering vendita, yugioh compra, carte rare
    online')

@section('title', 'Marketplace — TCG Vault')

@section('content')

    {{-- HEADER MARKETPLACE --}}
    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Marketplace</p>
                    <h1 class="text-3xl font-black text-white">
                        Tutte le carte
                        <span class="text-gray-600 font-normal text-xl ml-2">({{ $cards->total() }} disponibili)</span>
                    </h1>
                </div>
                @auth
                    <a href="{{ route('cards.create') }}"
                        class="px-6 py-3 rounded-xl font-bold text-white text-sm flex items-center gap-2"
                        style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        + Pubblica una carta
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- FILTRI --}}
        <div class="rounded-xl p-5 border border-purple-900/50 mb-8" style="background: rgba(45, 17, 84, 0.2);">
            <form method="GET" action="{{ route('marketplace.index') }}" class="flex flex-wrap gap-3 items-end">

                <div>
                    <label class="block text-gray-500 text-xs mb-1 uppercase tracking-wide">Categoria</label>
                    <select name="category"
                        class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2.5 rounded-lg text-sm focus:outline-none focus:border-purple-500">
                        <option value="">Tutte</option>
                        <option value="pokemon" {{ request('category') == 'pokemon' ? 'selected' : '' }}>🔴 Pokémon
                        </option>
                        <option value="mtg" {{ request('category') == 'mtg' ? 'selected' : '' }}>🪄 Magic: TG
                        </option>
                        <option value="yugioh" {{ request('category') == 'yugioh' ? 'selected' : '' }}>👁 Yu-Gi-Oh!
                        </option>
                        <option value="digimon" {{ request('category') == 'digimon' ? 'selected' : '' }}>🐉 Digimon
                        </option>
                        <option value="lorcana" {{ request('category') == 'lorcana' ? 'selected' : '' }}>✨ Lorcana</option>
                        <option value="onepiece" {{ request('category') == 'onepiece' ? 'selected' : '' }}>☠️ One Piece
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 text-xs mb-1 uppercase tracking-wide">Tipo</label>
                    <select name="type" class="border text-white text-sm focus:outline-none px-3 py-2.5 rounded-lg"
                        style="background: rgba(45,17,84,0.95); border-color: rgba(124,58,237,0.5); color: #e8e6e0;">
                        <option value="">Tutte le carte</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>&#128176; In vendita
                        </option>
                        <option value="trade" {{ request('type') == 'trade' ? 'selected' : '' }}>&#128260; In permuta
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-500 text-xs mb-1 uppercase tracking-wide">Condizione</label>
                    <select name="condition"
                        class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2.5 rounded-lg text-sm focus:outline-none focus:border-purple-500">
                        <option value="">Tutte</option>
                        <option value="NM" {{ request('condition') == 'NM' ? 'selected' : '' }}>NM - Near Mint
                        </option>
                        <option value="LP" {{ request('condition') == 'LP' ? 'selected' : '' }}>LP - Lightly Played
                        </option>
                        <option value="MP" {{ request('condition') == 'MP' ? 'selected' : '' }}>MP - Moderately Played
                        </option>
                        <option value="HP" {{ request('condition') == 'HP' ? 'selected' : '' }}>HP - Heavily Played
                        </option>
                        <option value="DMG" {{ request('condition') == 'DMG' ? 'selected' : '' }}>DMG - Damaged</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 text-xs mb-1 uppercase tracking-wide">Prezzo max €</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Es. 50"
                        step="0.01"
                        class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2.5 rounded-lg text-sm focus:outline-none focus:border-purple-500 w-28">
                </div>

                <div>
                    <label class="block text-gray-500 text-xs mb-1 uppercase tracking-wide">Ordina per</label>
                    <select name="sort"
                        class="bg-black/40 border border-purple-800/50 text-gray-300 px-3 py-2.5 rounded-lg text-sm focus:outline-none focus:border-purple-500">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Più recenti</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prezzo crescente
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prezzo
                            decrescente</option>
                    </select>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    🔍 Filtra
                </button>

                @if (request()->hasAny(['category', 'type', 'max_price', 'condition', 'sort']))
                    <a href="{{ route('marketplace.index') }}"
                        class="px-5 py-2.5 rounded-lg text-sm text-gray-400 border border-gray-700 hover:text-white transition">
                        ✕ Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- GRID CARTE --}}
        @forelse($cards as $card)
            @if ($loop->first)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-10">
            @endif

            <a href="{{ route('marketplace.show', $card) }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group block"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="aspect-[2/3] bg-black/40 overflow-hidden relative">
                    @if ($card->images && count($card->images) > 0)
                        <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0]) }}"
                            alt="{{ $card->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <span class="text-4xl">🃏</span>
                            <span class="text-gray-600 text-xs">{{ strtoupper($card->tcg_category) }}</span>
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

                    {{-- Overlay hover --}}
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"
                        style="background: rgba(124, 58, 237, 0.3);">
                        <span class="text-white text-sm font-bold">Vedi dettagli →</span>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-purple-400 text-xs font-mono uppercase mb-1">{{ $card->tcg_category }} ·
                        {{ $card->condition }}</p>
                    <h3 class="text-white text-sm font-bold leading-tight truncate">{{ $card->name }}</h3>
                    <p class="text-gray-600 text-xs truncate mt-0.5">{{ $card->set_name }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-gray-600 text-xs">{{ $card->owner->name }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded text-green-400 border border-green-900"
                            style="background: rgba(6, 78, 59, 0.3);">⏳ Da validare</span>
                    </div>
                </div>
            </a>

            @if ($loop->last)
    </div>
    @endif
@empty
    <div class="rounded-2xl p-20 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.2);">
        <div class="text-6xl mb-4">🃏</div>
        <h3 class="text-2xl font-bold text-white mb-2">Nessuna carta trovata</h3>
        <p class="text-gray-500 mb-6">Prova a modificare i filtri di ricerca.</p>
        @if (request()->hasAny(['category', 'type', 'max_price', 'condition']))
            <a href="{{ route('marketplace.index') }}" class="px-6 py-3 rounded-xl font-bold text-white inline-block mr-3"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Reset filtri
            </a>
        @endif
        @auth
            <a href="{{ route('cards.create') }}"
                class="px-6 py-3 rounded-xl font-bold text-white inline-block border border-purple-700">
                + Pubblica una carta
            </a>
        @endauth
    </div>
    @endforelse

    {{-- Paginazione --}}
    @if ($cards->hasPages())
        <div class="flex justify-center mt-8">
            {{ $cards->withQueryString()->links() }}
        </div>
    @endif
    </div>

@endsection
