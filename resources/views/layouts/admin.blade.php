<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — TCG SafeSwap</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background:#0d0d1a;min-height:100vh">
    <nav style="background:#0a0a15;border-bottom:1px solid rgba(124,58,237,0.3)"
        class="px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div
                style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:14px">
                🃏</div>
            <span class="text-white font-black text-sm tracking-widest">TCG SafeSwap</span>
            <span class="text-purple-400 text-xs px-2 py-0.5 rounded border border-purple-800/50"
                style="background:rgba(124,58,237,0.1)">ADMIN</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cards') }}" class="text-gray-400 text-xs hover:text-white transition">🃏 Gestione
                Carte</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-300 text-sm py-2 w-full text-left">&#128275;
                    Logout</button>
            </form>
            <span class="text-gray-500 text-xs">{{ auth()->user()->name }}</span>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-4 p-3 rounded-xl border border-green-800/50 text-green-400 text-sm"
                style="background:rgba(6,78,59,0.15)">
                ✅ {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>

</html>
