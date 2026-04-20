@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-black mb-4" style="background: linear-gradient(135deg, #ef4444, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">403</div>
        <h1 class="text-2xl font-black text-white mb-4">Accesso negato</h1>
        <p class="text-gray-500 mb-8">Non hai i permessi per accedere a questa pagina.</p>
        <a href="{{ route('marketplace.index') }}"
           class="px-6 py-3 rounded-xl font-bold text-black"
           style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
            🃏 Vai al marketplace
        </a>
    </div>
</div>
@endsection