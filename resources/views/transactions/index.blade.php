@extends('layouts.app')

@section('title', 'Le mie transazioni — TCG Vault')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-7xl mx-auto">
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Account</p>
            <h1 class="text-3xl font-black text-white">Le mie transazioni</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- VENDITE --}}
        <div class="rounded-2xl border border-purple-900/50 p-8 mb-8" style="background: rgba(45,17,84,0.2);">
            <h2 class="text-xl font-black text-white mb-6 flex items-center gap-2">
                &#128230; Carte che ho venduto
            </h2>

            @forelse($selling as $t)
                <div class="rounded-xl border border-purple-900/50 p-6 mb-4" style="background: rgba(45,17,84,0.3);">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div>
                            <p class="text-purple-400 text-xs font-mono uppercase mb-1">Transazione #{{ $t->id }}</p>
                            <h3 class="text-white font-bold text-lg">{{ $t->card->name }}</h3>
                            <p class="text-gray-500 text-sm">Acquirente: {{ $t->buyer->name }}</p>
                            <div class="space-y-1 text-sm mt-2">
                                <p class="text-gray-500">Prezzo carta: <span
                                        class="text-white">&#8364;{{ number_format($t->amount, 2) }}</span></p>
                                <p class="text-gray-500">Spedizioni (x2): <span class="text-white">&#8364;5.90</span></p>
                                <p class="text-gray-500">Totale pagato: <span
                                        class="text-purple-400 font-bold">&#8364;{{ number_format($t->amount + 11.8, 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 items-start md:items-end">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold
        @if ($t->status === 'completed') bg-green-900/50 text-green-400 border border-green-800
        @elseif($t->status === 'paid_escrow') bg-yellow-900/50 text-yellow-400 border border-yellow-800
        @elseif($t->status === 'in_validation') bg-purple-900/50 text-purple-400 border border-purple-800
        @elseif($t->status === 'shipping') bg-blue-900/50 text-blue-400 border border-blue-800
        @else bg-gray-900/50 text-gray-400 border border-gray-800 @endif">
                                @if ($t->status === 'paid_escrow')
                                    &#128176; Pagato
                                @elseif($t->status === 'in_validation')
                                    &#128300; In validazione
                                @elseif($t->status === 'validated')
                                    &#9989; Validata
                                @elseif($t->status === 'shipping')
                                    &#128230; In spedizione
                                @elseif($t->status === 'completed')
                                    &#127381; Completata
                                @else
                                    &#9203; In attesa
                                @endif
                            </span>
                        </div>
                    </div>

                    @if ($t->status === 'paid_escrow')
                        <div class="mt-4 rounded-xl p-4 border border-yellow-800/50"
                            style="background: rgba(234,179,8,0.05);">
                            <p class="text-yellow-400 font-bold text-sm mb-3">&#128230; Spedisci la carta al validatore</p>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('shipping.label', $t) }}" target="_blank"
                                    class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                    &#128424; Scarica etichetta PDF
                                </a>
                                <form method="POST" action="{{ route('transaction.mark-shipped', $t) }}">
                                    @csrf
                                    <input type="text" name="tracking_number" placeholder="Tracking (opzionale)"
                                        class="px-3 py-2 rounded-lg text-white text-sm border mr-2"
                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                        style="background: linear-gradient(135deg, #059669, #10b981);">
                                        &#9989; Ho spedito
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            @empty
                <div class="rounded-xl p-10 text-center border border-purple-900/50"
                    style="background: rgba(45,17,84,0.1);">
                    <p class="text-gray-500 text-sm">Non hai ancora venduto nessuna carta.</p>
                    <a href="{{ route('cards.create') }}"
                        class="text-purple-400 text-sm hover:text-purple-300 mt-2 inline-block">
                        + Pubblica una carta &#8594;
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ACQUISTI --}}
        <div class="rounded-2xl border border-purple-900/50 p-8" style="background: rgba(45,17,84,0.2);">
            <h2 class="text-xl font-black text-white mb-6 flex items-center gap-2">
                &#128722; Carte che ho acquistato
            </h2>

            @forelse($buying as $t)
                <div class="rounded-xl border border-purple-900/50 p-6 mb-4" style="background: rgba(45,17,84,0.3);">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div>
                            <p class="text-purple-400 text-xs font-mono uppercase mb-1">Transazione #{{ $t->id }}</p>
                            <h3 class="text-white font-bold text-lg">{{ $t->card->name }}</h3>
                            <p class="text-gray-500 text-sm">Venditore: {{ $t->seller->name }}</p>
                            <div class="space-y-1 text-sm mt-2">
                                <p class="text-gray-500">Prezzo carta: <span
                                        class="text-white">&#8364;{{ number_format($t->amount, 2) }}</span></p>
                                <p class="text-gray-500">Spedizioni (x2): <span class="text-white">&#8364;5.90</span></p>
                                <p class="text-gray-500">Totale pagato: <span
                                        class="text-purple-400 font-bold">&#8364;{{ number_format($t->amount + 11.8, 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 items-start md:items-end">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold
                            @if ($t->status === 'completed') bg-green-900/50 text-green-400 border border-green-800
                            @elseif($t->status === 'paid_escrow') bg-yellow-900/50 text-yellow-400 border border-yellow-800
                            @elseif($t->status === 'in_validation') bg-purple-900/50 text-purple-400 border border-purple-800
                            @elseif($t->status === 'shipping') bg-blue-900/50 text-blue-400 border border-blue-800
                            @else bg-gray-900/50 text-gray-400 border border-gray-800 @endif">
                                @if ($t->status === 'pending')
                                    &#9203; In attesa
                                @elseif($t->status === 'paid_escrow')
                                    &#128176; Pagato
                                @elseif($t->status === 'in_validation')
                                    &#128300; In validazione
                                @elseif($t->status === 'validated')
                                    &#9989; Validata
                                @elseif($t->status === 'shipping')
                                    &#128230; In spedizione
                                @elseif($t->status === 'completed')
                                    &#127381; Completata
                                @elseif($t->status === 'disputed')
                                    &#9888; Controversia
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Pulsante etichetta venditore --}}
                    @if ($t->status === 'paid_escrow' && $t->seller_id === Auth::id())
                        <div class="mt-4 rounded-xl p-4 border border-yellow-800/50"
                            style="background: rgba(234,179,8,0.05);">
                            <p class="text-yellow-400 font-bold text-sm mb-3">&#128230; Spedisci la carta al validatore</p>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('shipping.label', $t) }}" target="_blank"
                                    class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                    &#128424; Scarica etichetta PDF
                                </a>
                                <form method="POST" action="{{ route('transaction.mark-shipped', $t) }}">
                                    @csrf
                                    <input type="text" name="tracking_number" placeholder="Tracking (opzionale)"
                                        class="px-3 py-2 rounded-lg text-white text-sm border mr-2"
                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                        style="background: linear-gradient(135deg, #059669, #10b981);">
                                        &#9989; Ho spedito
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if ($t->status === 'shipping' && $t->buyer_id === Auth::id())
                        <div class="mt-4 rounded-xl p-4 border border-green-800/50" style="background: rgba(6,78,59,0.1);">
                            <p class="text-green-400 font-bold text-sm mb-3">&#128230; Carta in arrivo!</p>
                            @if ($t->return_tracking_number)
                                <div class="mb-3 p-3 rounded-lg border border-green-800/30"
                                    style="background: rgba(0,0,0,0.2);">
                                    <p class="text-gray-500 text-xs uppercase mb-1">Numero tracking</p>
                                    <p class="text-white font-mono font-bold">{{ $t->return_tracking_number }}</p>
                                </div>
                            @endif
                            <p class="text-gray-400 text-xs mb-3">Quando ricevi la carta clicca il pulsante per confermare.
                            </p>
                            <form method="POST" action="{{ route('transaction.mark-completed', $t) }}">
                                @csrf
                                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white text-sm"
                                    style="background: linear-gradient(135deg, #059669, #10b981);">
                                    &#9989; Ho ricevuto la carta
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Fondi in escrow --}}
                    @if ($t->status === 'in_validation')
                        <div class="mt-4 rounded-xl p-4 border border-yellow-800/50"
                            style="background: rgba(234,179,8,0.05);">
                            <p class="text-yellow-400 font-bold text-xs uppercase tracking-wide mb-2">&#128274; Il tuo
                                pagamento e al sicuro</p>
                            <p class="text-gray-500 text-xs">
                                I fondi sono bloccati in escrow e verranno rilasciati al venditore solo dopo la validazione
                                della carta.
                            </p>
                        </div>
                    @endif

                    {{-- Progress bar --}}
                    @php
                        $steps = ['pending', 'paid_escrow', 'in_validation', 'validated', 'shipping', 'completed'];
                        $currentStep = array_search($t->status, $steps);
                        $currentStep = $currentStep === false ? 0 : $currentStep;
                    @endphp
                    <div class="mt-4 flex items-center gap-1">
                        @foreach (['Pagato', 'Al validatore', 'In esame', 'Approvato', 'In spedizione', 'Completato'] as $i => $label)
                            <div class="flex-1 text-center">
                                <div
                                    class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center text-xs font-bold
                                {{ $i <= $currentStep ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-600' }}">
                                    {!! $i < $currentStep ? '&#10003;' : $i + 1 !!}
                                </div>
                                <p class="text-xs {{ $i <= $currentStep ? 'text-purple-400' : 'text-gray-600' }}">
                                    {{ $label }}
                                </p>
                            </div>
                            @if (!$loop->last)
                                <div class="h-px flex-1 {{ $i < $currentStep ? 'bg-purple-600' : 'bg-gray-800' }} mb-4">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Pulsante spedizione --}}
                    @if ($t->status === 'paid_escrow' && $t->seller_id === Auth::id())
                        <div class="mt-4 rounded-xl p-4 border border-yellow-800/50"
                            style="background: rgba(234,179,8,0.05);">
                            <p class="text-yellow-400 font-bold text-sm mb-3">&#128230; Spedisci la carta al validatore</p>
                            <div class="flex gap-3">
                                <a href="{{ route('shipping.label', $t) }}" target="_blank"
                                    class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                    &#128424; Scarica etichetta PDF
                                </a>
                                <form method="POST" action="{{ route('transaction.mark-shipped', $t) }}">
                                    @csrf
                                    <input type="text" name="tracking_number" placeholder="Tracking (opzionale)"
                                        class="px-3 py-2 rounded-lg text-white text-sm border mr-2"
                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white"
                                        style="background: linear-gradient(135deg, #059669, #10b981);">
                                        &#9989; Ho spedito
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-xl p-10 text-center border border-purple-900/50"
                    style="background: rgba(45,17,84,0.1);">
                    <p class="text-gray-500 text-sm">Non hai ancora acquistato nessuna carta.</p>
                    <a href="{{ route('marketplace.index') }}"
                        class="text-purple-400 text-sm hover:text-purple-300 mt-2 inline-block">
                        Vai al marketplace &#8594;
                    </a>
                </div>
            @endforelse
        </div>

    </div>

@endsection
