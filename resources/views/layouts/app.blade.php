<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TCG Vault - Marketplace TCG Certificato')</title>
    <meta name="description" content="@yield('description', 'Compra, vendi e scambia carte da gioco rare con la protezione del sistema escrow TCG Vault.')">
    <meta name="keywords" content="@yield('keywords', 'carte pokemon, magic the gathering, yugioh, marketplace tcg, carte rare')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'TCG Vault')">
    <meta property="og:description" content="@yield('description', 'Marketplace TCG Certificato')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:site_name" content="TCG Vault">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'TCG Vault')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    @stack('schema')
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
    <style>
        nav::-webkit-scrollbar {
            display: none;
        }

        select option {
            background: rgba(45, 17, 84, 0.95);
            color: #e8e6e0;
        }
    </style>
</head>

<body class="text-gray-100 min-h-screen"
    style="background: linear-gradient(180deg, #2d1154 0%, #1a0a2e 30%, #0d0d0d 60%, #000000 100%); background-attachment: fixed;">

    <header class="border-b border-purple-900/50 sticky top-0 z-50"
        style="background: rgba(45, 17, 84, 0.95); backdrop-filter: blur(10px);">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                        style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        <span class="text-white font-black text-lg">T</span>
                    </div>
                    <div>
                        <span class="text-white font-black text-xl tracking-wide">TCG</span>
                        <span class="text-purple-400 font-black text-xl tracking-wide"> Vault</span>
                    </div>
                </a>

                <form method="GET" action="{{ route('marketplace.search') }}"
                    class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cerca carte, set, categorie..."
                            class="w-full bg-black/40 border border-purple-800/50 text-gray-300 px-4 py-2 rounded-lg text-xs focus:outline-none focus:border-purple-500 placeholder-gray-600">
                        <button type="submit">
                            <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
                {{-- Hamburger mobile --}}
                <button id="mobile-menu-btn" class="md:hidden text-gray-400 hover:text-white p-2">
                    <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}"
                            class="text-gray-400 hover:text-white text-xs transition-colors">Accedi</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-white"
                            style="background: linear-gradient(135deg, #7c3aed, #a855f7);">Registrati</a>
                    @else
                        @if (auth()->user()->role !== 'validator')
                            <a href="{{ route('cards.create') }}"
                                class="px-4 py-2 rounded-lg text-xs font-semibold text-white hidden md:block"
                                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">+ Vendi carta</a>
                            <a href="{{ route('cards.my') }}"
                                class="px-4 py-2 rounded-lg text-xs text-gray-400 border border-purple-800/50 hover:text-white transition hidden md:block">
                                Le mie carte
                            </a>
                            @php
                                $activeTransactions = auth()->check()
                                    ? \App\Models\Transaction::where(function ($q) {
                                        $q->where('buyer_id', auth()->id())->orWhere('seller_id', auth()->id());
                                    })
                                        ->whereNotIn('status', ['completed', 'disputed', 'rejected'])
                                        ->count()
                                    : 0;
                            @endphp
                            <a href="{{ route('transactions.index') }}"
                                class="relative px-4 py-2 rounded-lg text-xs text-gray-400 border border-purple-800/50 hover:text-white transition hidden md:block">
                                &#128230; Le mie transazioni
                                @if ($activeTransactions > 0)
                                    <span
                                        class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-white flex items-center justify-center font-bold"
                                        style="background: #a855f7; font-size: 9px;">
                                        {{ $activeTransactions }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('user.dashboard') }}"
                                class="px-4 py-2 rounded-lg text-xs text-gray-400 border border-purple-800/50 hover:text-white transition hidden md:block">
                                Dashboard
                            </a>
                        @endif
                        <a href="{{ auth()->user()->role === 'validator' ? route('validator.profile.edit') : route('profile.show') }}"
                            class="flex items-center gap-2 border border-purple-800/50 rounded-lg px-3 py-2 hover:border-purple-500 transition">
                            <div
                                class="w-6 h-6 rounded-full bg-purple-700 flex items-center justify-center overflow-hidden">
                                @if (auth()->user()->profile_photo)
                                    <img src="{{ Storage::url(auth()->user()->profile_photo) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-gray-300 text-xs hidden md:block">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-gray-500 hover:text-red-400 text-xs transition-colors">Esci</button>
                        </form>
                    @endguest
                </div>
            </div>

            <nav class="flex items-center justify-center gap-1 pb-2 overflow-x-auto md:flex hidden"
                style="-ms-overflow-style: none; scrollbar-width: none;">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#127968; Home
                </a>
                <a href="{{ route('marketplace.category', 'pokemon') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    <img src="https://images.pokemontcg.io/base1/4_hires.png" class="w-4 h-4 object-contain rounded">
                    Pokemon
                </a>
                <a href="{{ route('marketplace.category', 'mtg') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#129668; Magic TG
                </a>
                <a href="{{ route('marketplace.category', 'yugioh') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#128065; Yu-Gi-Oh!
                </a>
                <a href="{{ route('marketplace.category', 'onepiece') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#9760;&#65039; One Piece
                </a>
                <a href="{{ route('marketplace.category', 'dragon_ball_super') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#129409; Dragon Ball
                </a>
                <a href="{{ route('marketplace.category', 'naruto') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                    &#127811; Naruto
                </a>
            </nav>
            @if (auth()->check() && auth()->user()->role === 'validator')
                <nav class="flex items-center justify-center gap-1 pb-2 overflow-x-auto md:flex hidden"
                    style="-ms-overflow-style: none; scrollbar-width: none;">
                    @php
                        $pendingValidations = \App\Models\Transaction::where(function ($q) {
                            $q->where('validator_id', auth()->id())->orWhere('buyer_validator_id', auth()->id());
                        })
                            ->whereIn('status', ['in_validation', 'accepted'])
                            ->count();
                    @endphp
                    <a href="{{ route('validator.dashboard') }}"
                        class="relative flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#9889; Dashboard Validatore
                        @if ($pendingValidations > 0)
                            <span class="w-4 h-4 rounded-full text-white flex items-center justify-center font-bold"
                                style="background: #a855f7; font-size: 9px;">
                                {{ $pendingValidations }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('validator.profile.edit') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#128100; Il mio profilo
                        @if(auth()->user()->role === 'validator')
<a href="{{ route('chat.index') }}"
    class="relative flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
    &#128172; Chat
    @php $unreadCount = auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewChatMessage')->count(); @endphp
    @if($unreadCount > 0)
        <span class="w-4 h-4 rounded-full text-white flex items-center justify-center font-bold"
              style="background: #ef4444; font-size: 9px;">
            {{ $unreadCount }}
        </span>
    @endif
</a>
@endif
                    </a>
                </nav>
            @else
                <nav class="flex items-center justify-center gap-1 pb-2 overflow-x-auto md:flex hidden"
                    style="-ms-overflow-style: none; scrollbar-width: none;">
                    <a href="{{ route('marketplace.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#128722; Marketplace
                    </a>
                    <a href="{{ route('marketplace.trades') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#128260; Permute
                    </a>
                    <a href="{{ route('validators.list') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#128104;&#8205;&#128188; I nostri validatori
                    </a>
                    <a href="{{ route('validator.register.form') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-purple-900/50 transition whitespace-nowrap">
                        &#128737; Diventa Validatore
                    </a>
                </nav>
            @endif
        </div>
        {{-- Menu mobile --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-purple-900/50 px-6 py-4">
            {{-- Ricerca mobile --}}
            <form method="GET" action="{{ route('marketplace.search') }}" class="mb-4">
                <div class="relative w-full">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cerca carte..."
                        class="w-full bg-black/40 border border-purple-800/50 text-gray-300 px-4 py-2 rounded-lg text-sm focus:outline-none">
                </div>
            </form>
            {{-- Links --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('marketplace.index') }}"
                    class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#127183;
                    Marketplace</a>
                <a href="{{ route('validators.list') }}"
                    class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#128737;
                    Validatori</a>
                @auth
                    @if (auth()->user()->role !== 'validator')
                        <a href="{{ route('validator.register') }}"
                            class="text-purple-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#128737;
                            Diventa Validatore</a>
                        <a href="{{ route('cards.create') }}"
                            class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">+
                            Pubblica carta</a>
                        <a href="{{ route('transactions.index') }}"
                            class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#128230;
                            Transazioni</a>
                        <a href="{{ route('profile.show') }}"
                            class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#128100;
                            Profilo</a>
                    @else
                        <a href="{{ route('validator.dashboard') }}"
                            class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#9889;
                            Dashboard</a>
                        <a href="{{ route('validator.profile.edit') }}"
                            class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">&#128100;
                            Profilo</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-red-400 hover:text-red-300 text-sm py-2 w-full text-left">&#128275;
                            Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-400 hover:text-white text-sm py-2 border-b border-purple-900/30">Accedi</a>
                    <a href="{{ route('register') }}" class="text-white text-sm py-2">Registrati</a>
                @endauth
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 text-xs rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 text-xs rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 py-8">
      @if(auth()->check() && auth()->user()->has_shipping_debt && !session('debt_modal_dismissed'))
<div style="min-height:100vh;background:rgba(0,0,0,0.85);display:flex;align-items:center;justify-content:center;padding:20px;position:absolute;top:0;left:0;right:0;bottom:0;z-index:9999">
    <div class="rounded-2xl border border-red-800/50 p-10 text-center max-w-lg w-full" style="background:#0d0d1a">
        <div style="font-size:48px;margin-bottom:16px">⚠️</div>
        <h2 class="text-2xl font-black text-white mb-3">Account sospeso</h2>
        <p class="text-gray-400 mb-2">
            Una tua transazione è stata rifiutata dal validatore.<br>
            Hai un debito di spedizione di
        </p>
        <p class="text-red-400 font-black text-4xl mb-4">€{{ number_format(auth()->user()->shipping_debt_amount, 2) }}</p>
        <p class="text-gray-500 text-sm mb-8">
            Non puoi pubblicare carte o effettuare acquisti finché non saldi il debito.
        </p>
        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('dismiss.debt.modal') }}">
    @csrf
    <input type="hidden" name="redirect" value="{{ route('shipping.debt.pay') }}">
    <button type="submit"
        class="block w-full py-4 rounded-xl font-black text-black text-base"
        style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
        Paga €{{ number_format(auth()->user()->shipping_debt_amount, 2) }} ora
    </button>
</form>
            <a href="{{ route('dismiss.debt.modal') }}"
   class="block w-full py-3 rounded-xl font-bold text-gray-400 text-sm border border-purple-900/50">
    Continua a sfogliare il marketplace
</a>
        </div>
    </div>
</div>
@endif
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-purple-900/30">
        <div class="max-w-7xl mx-auto px-6 py-8 text-center">
            <p class="text-gray-600 text-xs">TCG Vault - Marketplace TCG Certificato</p>
            <div class="flex justify-center gap-6 mt-3">
                <a href="{{ route('privacy') }}" class="text-gray-600 text-xs hover:text-purple-400 transition">
                    Privacy Policy
                </a>
                <a href="{{ route('terms') }}" class="text-gray-600 text-xs hover:text-purple-400 transition">
                    Termini di servizio
                </a>
            </div>
        </div>
    </footer>
    {{-- Banner Cookie --}}
    @if (!request()->cookie('cookie_consent'))
        <div id="cookie-banner" class="fixed bottom-0 left-0 right-0 z-50 p-4"
            style="background: rgba(13,0,26,0.97); border-top: 1px solid rgba(124,58,237,0.5); backdrop-filter: blur(10px);">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="text-2xl flex-shrink-0">&#127850;</span>
                    <div>
                        <p class="text-white text-sm font-bold mb-1">Utilizziamo i cookie</p>
                        <p class="text-gray-400 text-xs leading-relaxed">
                            Utilizziamo cookie tecnici necessari al funzionamento della piattaforma.
                            Non utilizziamo cookie di profilazione o tracciamento.
                            <a href="{{ route('privacy') }}" class="text-purple-400 hover:text-purple-300 underline">
                                Leggi la Privacy Policy
                            </a>
                        </p>
                    </div>
                </div>
                <div class="flex gap-3 flex-shrink-0">
                    <a href="{{ route('privacy') }}"
                        class="px-4 py-2 rounded-lg text-xs text-gray-400 border border-gray-700 hover:text-white transition">
                        Maggiori info
                    </a>
                    <button onclick="acceptCookies()"
                        class="px-6 py-2 rounded-lg text-xs font-bold text-white transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        Accetta
                    </button>
                </div>
            </div>
        </div>
    @endif
    @push('scripts')
        <script>
            function acceptCookies() {
                document.cookie = "cookie_consent=true; max-age=" + (60 * 60 * 24 * 365) + "; path=/";
                document.getElementById('cookie-banner').style.display = 'none';
            }
        </script>
        @stack('scripts')
        <script>
            document.getElementById('mobile-menu-btn').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                const hamburger = document.getElementById('hamburger-icon');
                const close = document.getElementById('close-icon');
                menu.classList.toggle('hidden');
                hamburger.classList.toggle('hidden');
                close.classList.toggle('hidden');
            });
        </script>
    @endpush
</body>

</html>
