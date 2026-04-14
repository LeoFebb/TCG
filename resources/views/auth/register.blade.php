<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati — TCG Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center py-10"
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

        <h1 class="text-2xl font-black text-white mb-2">Crea un account</h1>
        <p class="text-gray-500 text-sm mb-8">Unisciti alla community TCG Vault</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Nome --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Nome completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Mario Rossi"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="mario@esempio.it"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Password</label>
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
                <label class="block text-gray-400 text-sm mb-2">Conferma password</label>
                <input type="password" name="password_confirmation" required
                       placeholder="Ripeti la password"
                       class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500 border transition"
                       style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
            </div>

            {{-- Vantaggi --}}
            <div class="rounded-xl p-4 border border-purple-900/30"
                 style="background: rgba(124,58,237,0.1);">
                <p class="text-purple-400 text-xs font-bold mb-2">✨ Con TCG Vault puoi:</p>
                <ul class="text-gray-500 text-xs space-y-1">
                    <li>🃏 Vendere le tue carte in modo sicuro</li>
                    <li>🔄 Scambiare carte con altri collezionisti</li>
                    <li>🛡 Acquistare con protezione escrow</li>
                    <li>🔬 Ricevere carte verificate da esperti</li>
                </ul>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                Crea account →
            </button>
        </form>
    </div>

    {{-- Link login --}}
    <p class="text-center text-gray-600 text-sm mt-6">
        Hai già un account?
        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
            Accedi
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



