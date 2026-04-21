@extends('layouts.app')

@section('title', 'Validatori Certificati — TCG SafeSwap')
@section('description',
    'Scopri i validatori certificati TCG SafeSwap. Esperti indipendenti che certificano autenticità e
    condizioni delle carte.')
@section('keywords', 'validatori tcg, esperti carte, certificazione carte pokemon, magic the gathering validazione')

@section('content')

    {{-- HERO --}}
    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-2">Team</p>
            <h1 class="text-4xl font-black text-white mb-3">
                🛡 Validatori Certificati
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                I nostri validatori sono esperti indipendenti certificati da TCG SafeSwap.
                Ogni carta passa attraverso la loro verifica prima di arrivare all'acquirente.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-3 md:px-6 py-6 md:py-10">

        {{-- Statistiche --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.3);">
                <p class="text-3xl font-black text-purple-400">{{ $validators->count() }}</p>
                <p class="text-gray-500 text-sm mt-1">Validatori attivi</p>
            </div>
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.3);">
                <p class="text-3xl font-black text-purple-400">100%</p>
                <p class="text-gray-500 text-sm mt-1">Carte certificate</p>
            </div>
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.3);">
                <p class="text-3xl font-black text-purple-400">48h</p>
                <p class="text-gray-500 text-sm mt-1">Tempo medio validazione</p>
            </div>
        </div>

        {{-- Lista validatori --}}
        @forelse($validators->chunk(2) as $chunk)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @foreach ($chunk as $validator)
                    <div class="rounded-xl border border-purple-900/50 p-6 flex items-start gap-4"
                        style="background: rgba(45,17,84,0.2);">

                        {{-- Avatar --}}
                        <div class="w-16 h-16 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden border-2 border-purple-700"
                            style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                            @if ($validator->profile_photo)
                                <img src="{{ Storage::url($validator->profile_photo) }}" alt="{{ $validator->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-white text-2xl font-black">
                                    {{ strtoupper(substr($validator->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h2 class="text-white font-black text-lg">{{ $validator->name }}</h2>
                                <span
                                    class="px-2 py-0.5 rounded-full text-xs font-bold text-green-400 border border-green-800"
                                    style="background: rgba(6,78,59,0.3);">
                                    ✅ Certificato
                                </span>
                            </div>

                            {{-- Email --}}
                            <p class="text-gray-500 text-s mb-2">📧 {{ $validator->email }}</p>

                            @if ($validator->vat_number)
                                <p class="text-gray-500 text-xs mb-2">&#127981; P.IVA: {{ $validator->vat_number }}</p>
                            @endif

                            {{-- Categorie TCG --}}
                            @if ($validator->tcg_categories)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach ($validator->tcg_categories as $cat)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-bold text-purple-300 border border-purple-800/50"
                                            style="background: rgba(124,58,237,0.2);">
                                            @if ($cat === 'pokemon')
                                                <img src="https://images.pokemontcg.io/base1/4_hires.png"
                                                    class="w-4 h-4 object-contain inline"> Pokémon
                                            @elseif($cat === 'mtg')
                                                🪄 Magic
                                            @elseif($cat === 'yugioh')
                                                👁 Yu-Gi-Oh!
                                            @elseif($cat === 'onepiece')
                                                ☠️ One Piece
                                            @elseif($cat === 'dragon_ball_super')
                                                🐉 Dragon Ball
                                            @else
                                                {{ strtoupper($cat) }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Note --}}
                            @if ($validator->validation_notes)
                                <p class="text-gray-400 text-sm leading-relaxed truncate">{{ $validator->validation_notes }}
                                </p>
                            @endif

                            {{-- Posizione --}}
                            @if ($validator->city)
                                <p class="text-gray-600 text-xs mt-1">
                                    📍 {{ $validator->city }}{{ $validator->country ? ', ' . $validator->country : '' }}
                                </p>
                            @endif

                            {{-- Carte validate --}}
                            @php
                                $validated = \App\Models\Transaction::where('validator_id', $validator->id)
                                    ->whereIn('status', ['validated', 'shipping', 'completed'])
                                    ->count();
                            @endphp
                            <p class="text-purple-400 text-xs mt-2 font-bold">{{ $validated }} carte validate</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="rounded-2xl p-20 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.2);">
                <div class="text-6xl mb-4">🛡</div>
                <h3 class="text-2xl font-bold text-white mb-2">Nessun validatore ancora</h3>
                <p class="text-gray-500 mb-6">Vuoi diventare un validatore certificato TCG SafeSwap?</p>
                <a href="{{ route('validator.register.form') }}"
                    class="px-6 py-3 rounded-xl font-bold text-white inline-block"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    Candidati ora →
                </a>
            </div>
        @endforelse

        {{-- Banner candidatura --}}
        <div class="rounded-2xl p-8 border border-purple-700/50 text-center mt-10"
            style="background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(168,85,247,0.1));">
            <h2 class="text-2xl font-black text-white mb-2">Sei un esperto TCG?</h2>
            <p class="text-gray-400 mb-6">Unisciti al team di validatori TCG SafeSwap e guadagna certificando carte rare.</p>
            <a href="{{ route('validator.register.form') }}" class="px-8 py-3 rounded-xl font-bold text-white inline-block"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Diventa Validatore →
            </a>
        </div>
    </div>

@endsection
