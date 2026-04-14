<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimposta password — TCG Vault</title>
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

        <div class="text-center mb-6">
            <div class="text-5xl mb-4">🔒</div>
            <h1 class="text-2xl font-black text-white mb-2">Nuova password</h1>
            <p class="text-gray-500 text-sm">Scegli una nuova password sicura per il tuo account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nuova Password --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Nuova password</label>
                <input type="password" name="password" required
                       placeholder="Minimo 8 caratteri"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Conferma Password --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Conferma nuova password</label>
                <input type="password" name="password_confirmation" required
                       placeholder="Ripeti la password"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Reimposta password →
            </button>
        </form>
    </div>

    <p class="text-center text-gray-600 text-sm mt-6">
        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
            ← Torna al login
        </a>
    </p>
</div>

</body>
</html>



