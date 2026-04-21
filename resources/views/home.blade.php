@extends('layouts.app')

@section('title', 'TCG SafeSwap - Marketplace TCG Certificato')
@section('description', 'Compra, vendi e scambia carte da gioco rare su TCG SafeSwap. Sistema escrow sicuro, validazione
    certificata.')
@section('keywords', 'marketplace tcg, carte pokemon rare, magic the gathering, yugioh, one piece card game, dragon ball
    super, naruto card game')

@section('content')

    {{-- HERO --}}
    <div class="relative overflow-hidden py-24 px-6 text-center"
        style="background: linear-gradient(180deg, #3b1a6e 0%, transparent 100%);">
        <div class="absolute top-10 left-20 w-64 h-64 rounded-full opacity-10 blur-3xl" style="background: #a855f7;"></div>
        <div class="absolute bottom-0 right-20 w-96 h-96 rounded-full opacity-10 blur-3xl" style="background: #7c3aed;"></div>
        <div class="relative z-10 max-w-4xl mx-auto">
            <div
                class="inline-flex items-center gap-2 bg-purple-900/50 border border-purple-700/50 rounded-full px-4 py-1.5 text-sm text-purple-300 mb-6">
                &#128737; Ogni carta verificata da esperti certificati
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                Il Marketplace TCG
                <span class="block"
                    style="background: linear-gradient(135deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    piu sicuro d'Italia
                </span>
            </h1>
            <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
                Compra, vendi e scambia carte da gioco rare con la protezione del sistema escrow TCG SafeSwap.
                Ogni transazione e validata da esperti indipendenti certificati.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('marketplace.index') }}"
                    class="px-8 py-4 rounded-xl font-bold text-white text-lg transition"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    Sfoglia le carte &#8594;
                </a>
                @guest
                    <a href="{{ route('register') }}"
                        class="px-8 py-4 rounded-xl font-bold text-purple-300 text-lg border border-purple-700/50 hover:bg-purple-900/30 transition">
                        Registrati gratis
                    </a>
                @else
                    <a href="{{ route('cards.create') }}"
                        class="px-8 py-4 rounded-xl font-bold text-purple-300 text-lg border border-purple-700/50 hover:bg-purple-900/30 transition">
                        + Pubblica una carta
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- STATISTICHE --}}
    <div class="max-w-7xl mx-auto px-3 md:px-6 py-8 md:py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.3);">
                <div class="text-3xl mb-2">&#128293;</div>
                <div class="text-2xl font-black text-white">10.000+</div>
                <div class="text-gray-500 text-sm mt-1">Carte disponibili</div>
            </div>
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.3);">
                <div class="text-3xl mb-2">&#128101;</div>
                <div class="text-2xl font-black text-white">500+</div>
                <div class="text-gray-500 text-sm mt-1">Venditori attivi</div>
            </div>
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.3);">
                <div class="text-3xl mb-2">&#128737;</div>
                <div class="text-2xl font-black text-white">100%</div>
                <div class="text-gray-500 text-sm mt-1">Transazioni sicure</div>
            </div>
            <div class="rounded-xl p-6 text-center border border-purple-900/50" style="background: rgba(45, 17, 84, 0.3);">
                <div class="text-3xl mb-2">&#9989;</div>
                <div class="text-2xl font-black text-white">50+</div>
                <div class="text-gray-500 text-sm mt-1">Validatori certificati</div>
            </div>
        </div>
    </div>

    {{-- CATEGORIE --}}
    <div class="max-w-7xl mx-auto px-3 md:px-6 py-8 md:py-12">
        <h2 class="text-2xl font-black text-white mb-6 text-center">Sfoglia per categoria</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">

            <a href="{{ route('marketplace.category', 'pokemon') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <img src="https://images.pokemontcg.io/base1/4_hires.png" alt="Pokemon"
                    class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 transition">
                <div class="absolute inset-0"
                    style="background: linear-gradient(180deg, transparent 30%, rgba(0,0,0,0.7) 100%);"></div>
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">Pokemon</div>
                </div>
            </a>

            <a href="{{ route('marketplace.category', 'mtg') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-4xl mb-2">&#129668;</div>
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">Magic TG</div>
                </div>
            </a>

            <a href="{{ route('marketplace.category', 'yugioh') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-4xl mb-2">&#128065;</div>
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">Yu-Gi-Oh!</div>
                </div>
            </a>

            <a href="{{ route('marketplace.category', 'onepiece') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-4xl mb-2">&#9760;&#65039;</div>
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">One Piece</div>
                </div>
            </a>

            <a href="{{ route('marketplace.category', 'dragon_ball_super') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-4xl mb-2">&#129409;</div>
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">Dragon Ball</div>
                </div>
            </a>

            <a href="{{ route('marketplace.category', 'naruto') }}"
                class="rounded-xl overflow-hidden border border-purple-900/50 hover:border-purple-500 transition group relative h-48"
                style="background: rgba(45, 17, 84, 0.3);">
                <div class="relative z-10 p-4 text-center flex flex-col items-center justify-end h-full">
                    <div class="text-4xl mb-2">&#127811;</div>
                    <div class="text-white text-sm font-bold group-hover:text-purple-300 transition">Naruto</div>
                </div>
            </a>

        </div>
    </div>

    {{-- COME FUNZIONA --}}
    <div class="max-w-7xl mx-auto px-3 md:px-6 py-8 md:py-12">
        <div class="rounded-2xl p-10 border border-purple-900/50" style="background: rgba(45, 17, 84, 0.2);">
            <h2 class="text-2xl font-black text-white text-center mb-10">Come funziona TCG SafeSwap</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"
                        style="background: rgba(124, 58, 237, 0.3); border: 1px solid rgba(168, 85, 247, 0.4);">
                        &#128269;
                    </div>
                    <div class="text-purple-400 font-mono text-xs mb-1">STEP 01</div>
                    <h3 class="text-white font-bold mb-2">Scegli la carta</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sfoglia migliaia di carte disponibili e trova quella
                        che cerchi.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"
                        style="background: rgba(124, 58, 237, 0.3); border: 1px solid rgba(168, 85, 247, 0.4);">
                        &#128274;
                    </div>
                    <div class="text-purple-400 font-mono text-xs mb-1">STEP 02</div>
                    <h3 class="text-white font-bold mb-2">Pagamento escrow</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Il tuo pagamento viene trattenuto dalla piattaforma in
                        modo sicuro.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"
                        style="background: rgba(124, 58, 237, 0.3); border: 1px solid rgba(168, 85, 247, 0.4);">
                        &#128300;
                    </div>
                    <div class="text-purple-400 font-mono text-xs mb-1">STEP 03</div>
                    <h3 class="text-white font-bold mb-2">Validazione esperta</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Un esperto certifica autenticita e condizioni della
                        carta.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl"
                        style="background: rgba(124, 58, 237, 0.3); border: 1px solid rgba(168, 85, 247, 0.4);">
                        &#128230;
                    </div>
                    <div class="text-purple-400 font-mono text-xs mb-1">STEP 04</div>
                    <h3 class="text-white font-bold mb-2">Ricevi la carta</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Solo dopo approvazione ricevi la carta e il venditore
                        i fondi.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BANNER SICUREZZA --}}
    <div class="max-w-7xl mx-auto px-6 py-12 mb-10">
        <div class="rounded-2xl p-10 border border-purple-700/50 text-center"
            style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(168, 85, 247, 0.1));">
            <h2 class="text-3xl font-black text-white mb-3">I tuoi soldi sono al sicuro &#128274;</h2>
            <p class="text-gray-400 max-w-2xl mx-auto mb-8 leading-relaxed">
                TCG SafeSwap usa un sistema di pagamento in escrow. I fondi vengono
                trattenuti dalla piattaforma e rilasciati al venditore solo dopo che un validatore
                certificato ha confermato l'autenticita della carta.
            </p>
            <a href="{{ route('marketplace.index') }}" class="px-8 py-4 rounded-xl font-bold text-white inline-block"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Vai al Marketplace &#8594;
            </a>
        </div>
    </div>

@endsection

