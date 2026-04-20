@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="rounded-2xl border border-red-800/50 p-10" style="background: rgba(127,29,29,0.15);">
        <div class="text-6xl mb-4">⚠️</div>
        <h1 class="text-2xl font-black text-white mb-4">Account sospeso</h1>
        <p class="text-gray-400 mb-6">
            Hai un debito di spedizione di 
            <span class="text-red-400 font-bold text-xl">€{{ number_format(auth()->user()->shipping_debt_amount, 2) }}</span>
            a causa di una transazione rifiutata dal validatore.
        </p>
        <p class="text-gray-500 text-sm mb-8">
            Per riprendere a operare sul sito devi saldare il debito. 
            Contatta il supporto a <a href="mailto:support@tcgvault.it" class="text-purple-400">support@tcgvault.it</a>
        </p>
        <a href="mailto:support@tcgvault.it" 
           class="px-8 py-3 rounded-xl font-bold text-white inline-block"
           style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
            Contatta il supporto
        </a>
    </div>
</div>
@endsection