<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi — TCG Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center" 
      style="background: linear-gradient(180deg, #2d1154 0%, #1a0a2e 30%, #0d0d0d 60%, #000000 100%);">

<div class="w-full max-w-md px-6">
    
    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                 style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                <span class="text-white font-black text-2xl">T</span>
            </div>
            <div>
                <span class="text-white font-black text-2xl">TCG</span>
                <span class="text-purple-400 font-black text-2xl"> Vault</span>
            </div>
        </a>
        <p class="text-gray-500 text-sm mt-3">Marketplace TCG Certificato</p>
    </div>

    {{-- Card --}}
    <div class="rounded-2xl p-8 border border-purple-900/50"
         style="background: rgba(45, 17, 84, 0.4); backdrop-filter: blur(10px);">
        
        <h1 class="text-2xl font-black text-white mb-2">Bentornato!</h1>
        <p class="text-gray-500 text-sm mb-8">Accedi al tuo account TCG Vault</p>

        @if(session('status'))
            <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 text-sm rounded-lg mb-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="mario@esempio.it"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-gray-400 text-sm">Password</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-purple-400 text-xs hover:text-purple-300">
                            Password dimenticata?
                        </a>
                    @endif
                </div>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ricordami --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="remember" id="remember" class="accent-purple-500 w-4 h-4">
                <label for="remember" class="text-gray-400 text-sm cursor-pointer">Ricordami</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Accedi →
            </button>
        </form>
    </div>

    {{-- Link registrazione --}}
    <p class="text-center text-gray-600 text-sm mt-6">
        Non hai un account?
        <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
            Registrati gratis
        </a>
    </p>
    <p class="text-center mt-3">
        <a href="{{ route('validator.register.form') }}" class="text-gray-600 text-xs hover:text-purple-400 transition">
            🛡 Vuoi diventare un validatore?
        </a>
    </p>
</div>

</body>
</html>



