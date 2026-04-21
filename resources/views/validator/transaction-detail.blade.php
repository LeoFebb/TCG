@extends('layouts.app')

@section('title', 'Dettaglio Transazione — TCG SafeSwap')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-7xl mx-auto">
            <a href="{{ route('validator.dashboard') }}"
                class="text-purple-400 text-sm hover:text-purple-300 mb-2 inline-block">
                ← Torna alla dashboard
            </a>
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Validazione</p>
            <h1 class="text-3xl font-black text-white">Transazione #{{ $transaction->id }}</h1>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Info carta --}}
            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">
                    @if ($transaction->card->tcg_category === 'pokemon')
                        <img src="https://images.pokemontcg.io/base1/4_hires.png"
                            class="w-4 h-4 object-contain inline mr-1">
                    @elseif($transaction->card->tcg_category === 'mtg')
                        &#129668;
                    @elseif($transaction->card->tcg_category === 'yugioh')
                        &#128065;
                    @elseif($transaction->card->tcg_category === 'onepiece')
                        &#9760;&#65039;
                    @elseif($transaction->card->tcg_category === 'dragon_ball_super')
                        &#129409;
                    @elseif($transaction->card->tcg_category === 'naruto')
                        &#127811;
                    @else
                        &#128293;
                    @endif
                    Carta da validare
                </p>
                @php
    $isAcquirenteValidator = $transaction->buyer_validator_id === Auth::user()->id;
    $offeredName = $transaction->offered_card_name ?? str_replace('Carta offerta: ', '', $transaction->validator_notes ?? '');
@endphp

@if($transaction->type === 'trade' && $isAcquirenteValidator)
    @if($transaction->offered_card_image)
        <img src="{{ Storage::url($transaction->offered_card_image) }}"
             alt="{{ $offeredName }}"
             class="w-full rounded-lg border border-purple-900/50 mb-4">
    @endif
    <h2 class="text-white font-black text-xl mb-2">{{ $offeredName }}</h2>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Tipo</span>
            <span class="text-white">Carta offerta in permuta</span>
        </div>
    </div>
@else
    @if ($transaction->card->images && count($transaction->card->images) > 0)
        <div class="grid grid-cols-2 gap-2 mb-4">
            @foreach ($transaction->card->images as $img)
                <img src="{{ str_starts_with($img, 'http') ? $img : Storage::url($img) }}"
                    alt="{{ $transaction->card->name }}" class="w-full rounded-lg border border-purple-900/50">
            @endforeach
        </div>
    @endif
    <h2 class="text-white font-black text-xl mb-2">{{ $transaction->card->name }}</h2>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Set</span>
            <span class="text-white">{{ $transaction->card->set_name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Numero</span>
            <span class="text-white">#{{ $transaction->card->card_number }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Categoria</span>
            <span class="text-white uppercase">{{ $transaction->card->tcg_category }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Rarità</span>
            <span class="text-white">{{ $transaction->card->rarity }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Condizione dichiarata</span>
            <span class="text-white font-bold">{{ $transaction->card->condition }}</span>
        </div>
    </div>
    @if ($transaction->card->description)
        <div class="mt-4 p-3 rounded-lg border border-purple-900/30" style="background: rgba(0,0,0,0.2);">
            <p class="text-gray-500 text-xs uppercase mb-1">Note venditore</p>
            <p class="text-gray-300 text-sm">{{ $transaction->card->description }}</p>
        </div>
    @endif
@endif
            </div>

            {{-- Info transazione --}}
            <div class="space-y-4">

                {{-- Parti coinvolte --}}
                <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">👥 Parti coinvolte</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-purple-700 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($transaction->seller->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-white text-sm font-bold">{{ $transaction->seller->name }}</p>
                                <p class="text-gray-500 text-xs">Venditore</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($transaction->buyer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-white text-sm font-bold">{{ $transaction->buyer->name }}</p>
                                <p class="text-gray-500 text-xs">Acquirente</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Importo --}}
                <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">💰 Importo</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Prezzo carta</span>
                            <span class="text-white">€{{ number_format($transaction->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Commissione</span>
                            <span class="text-white">€{{ number_format($transaction->platform_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold pt-2 border-t border-purple-900/50">
                            <span class="text-white">Venditore riceve</span>
                            <span class="text-purple-400">€{{ number_format($transaction->amount + 5.9, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Azioni validatore --}}
                @if ($transaction->status === 'in_validation')
                    <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                        <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">⚡ Azioni</p>

                        <form method="POST" action="{{ route('validator.transaction.approve', $transaction) }}"
                            class="mb-3" onsubmit="return openApproveModal(event, this)" @csrf <div class="mb-3">
                            <label class="block text-gray-400 text-sm mb-2">Note (opzionale)</label>
                            <textarea name="validator_notes" rows="2" placeholder="Carta autentica, condizioni conformi..."
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl font-bold text-white text-sm"
                        style="background: linear-gradient(135deg, #059669, #10b981);">
                        ✅ Approva — Carta autentica
                    </button>
                    </form>

                    <button onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                        class="w-full py-3 rounded-xl font-bold text-red-400 text-sm border border-red-800 hover:bg-red-900/30 transition">
                        ❌ Rifiuta — Carta non conforme
                    </button>

                    <form id="reject-form" method="POST"
                        action="{{ route('validator.transaction.reject', $transaction) }}" class="hidden mt-3">
                        @csrf
                        <textarea name="rejection_reason" required minlength="20" rows="3" placeholder="Descrivi il motivo del rifiuto..."
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border mb-2"
                            style="background: rgba(0,0,0,0.4); border-color: rgba(220,38,38,0.5);"></textarea>
                        <button type="submit"
                            class="w-full py-3 rounded-xl font-bold text-white text-sm bg-red-800 hover:bg-red-700 transition">
                            Conferma rifiuto
                        </button>
                    </form>
            </div>
            @endif

            {{-- Pulsante etichetta rispedizione --}}
            @if ($transaction->status === 'validated')
                <a href="{{ route('shipping.return-label', $transaction) }}" target="_blank"
                    class="block w-full py-3 rounded-xl font-bold text-white text-sm text-center"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    🖨 Stampa etichetta rispedizione
                </a>
            @endif
        </div>
    </div>
    </div>
    {{-- Modal Approvazione --}}
    <div id="approveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.8); backdrop-filter: blur(5px);">
        <div class="rounded-2xl border border-green-800/50 p-8 w-full max-w-md" style="background: rgba(13,0,26,0.97);">
            <div class="text-center mb-6">
                <div class="text-5xl mb-4">&#9989;</div>
                <h3 class="text-2xl font-black text-white mb-2">Conferma approvazione</h3>
                <p class="text-gray-400 text-sm">
                    Stai per approvare questa transazione.<br>
                    I fondi verranno rilasciati al venditore.
                </p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()"
                    class="flex-1 py-3 rounded-xl font-bold text-gray-400 border border-gray-700 hover:text-white transition">
                    Annulla
                </button>
                <button type="button" onclick="submitApproveForm()"
                    class="flex-1 py-3 rounded-xl font-bold text-white transition"
                    style="background: linear-gradient(135deg, #059669, #10b981);">
                    &#9989; Approva
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let approveForm = null;

            function openApproveModal(e, form) {
                e.preventDefault();
                approveForm = form;
                document.getElementById('approveModal').classList.remove('hidden');
                return false;
            }

            function closeApproveModal() {
                document.getElementById('approveModal').classList.add('hidden');
                approveForm = null;
            }

            function submitApproveForm() {
                if (approveForm) approveForm.submit();
            }

            document.getElementById('approveModal').addEventListener('click', function(e) {
                if (e.target === this) closeApproveModal();
            });
        </script>
    @endpush

@endsection
