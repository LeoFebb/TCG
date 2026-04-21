@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-black text-white mb-8">🛠 Pannello Admin</h1>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
        @foreach([['Carte',  $stats['cards'], 'purple'], ['Utenti', $stats['users'], 'blue'], ['Validatori', $stats['validators'], 'green'], ['Transazioni', $stats['transactions'], 'yellow'], ['Controversie', $stats['disputed'], 'red']] as [$lbl,$val,$col])
        <div class="rounded-xl p-5 text-center border border-{{ $col }}-800/50" style="background: rgba(45,17,84,0.2);">
            <p class="text-2xl font-black text-{{ $col }}-400">{{ $val }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $lbl }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.cards') }}" class="rounded-xl p-6 border border-purple-900/50 hover:border-purple-500 transition" style="background: rgba(45,17,84,0.2);">
            <p class="text-2xl mb-2">🃏</p>
            <p class="text-white font-bold">Gestione Carte</p>
            <p class="text-gray-500 text-sm">Rimuovi o ripristina carte</p>
        </a>
        <a href="{{ route('admin.users') }}" class="rounded-xl p-6 border border-purple-900/50 hover:border-purple-500 transition" style="background: rgba(45,17,84,0.2);">
            <p class="text-2xl mb-2">👥</p>
            <p class="text-white font-bold">Gestione Utenti</p>
            <p class="text-gray-500 text-sm">Visualizza tutti gli utenti</p>
        </a>
        <a href="{{ route('admin.disputes') }}" class="rounded-xl p-6 border border-purple-900/50 hover:border-purple-500 transition" style="background: rgba(45,17,84,0.2);">
            <p class="text-2xl mb-2">⚠️</p>
            <p class="text-white font-bold">Controversie</p>
            <p class="text-gray-500 text-sm">{{ $stats['disputed'] }} transazioni in disputa</p>
        </a>
    </div>
</div>
@endsection