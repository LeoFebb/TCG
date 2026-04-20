@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-black mb-4" style="background: linear-gradient(135deg, #7c3aed, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">500</div>
        <h1 class="text-2xl font-black text-white mb-4">Errore del server</h1>
        <p class="text-gray-500 mb-8">Qualcosa è andato storto. Il nostro team è già al lavoro per risolvere il problema.</p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('home') }}"
               class="px-6 py-3 rounded-xl font-bold text-black"
               style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
                🏠 Torna alla home
            </a>
            <a href="mailto:support@tcgvault.it"
               class="px-6 py-3 rounded-xl font-bold text-gray-400 border border-purple-900/50 hover:text-white transition">
                ✉️ Contatta supporto
            </a>
        </div>
    </div>
</div>
@endsection