@extends('layouts.app')

@section('title', 'Acquista — ' . $card->name . ' — TCG Vault')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Checkout</p>
            <h1 class="text-3xl font-black text-white">Acquisto sicuro</h1>
        </div>
        <div class="rounded-xl px-5 py-3 border border-yellow-800/50 flex items-center gap-3"
             style="background: rgba(234,179,8,0.1);">
            <span class="text-yellow-400 text-lg">&#9203;</span>
            <div>
                <p class="text-yellow-400 text-xs font-mono uppercase tracking-widest">Tempo rimasto</p>
                <p class="text-white font-black text-xl" id="checkout-timer">05:00</p>
            </div>
        </div>
    </div>
</div>

    <div class="max-w-5xl mx-auto px-3 md:px-6 py-6 md:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- COLONNA SINISTRA: Riepilogo --}}
            <div>

                {{-- Info carta --}}
                <div class="rounded-xl border border-purple-900/50 p-6 mb-6" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">Stai acquistando</p>

                    @if ($card->images && count($card->images) > 0)
                        <div class="aspect-[2/3] max-w-[150px] mx-auto mb-4 rounded-lg overflow-hidden">
                            <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : (str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0])) }}"
                                alt="{{ $card->name }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <h2 class="text-white font-black text-xl mb-1">{{ $card->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $card->set_name }} · #{{ $card->card_number }}</p>
                    <p class="text-gray-500 text-sm">Condizione: <span class="text-white">{{ $card->condition }}</span></p>
                    <p class="text-gray-500 text-sm">Venditore: <span class="text-white">{{ $seller->name }}</span></p>
                </div>

                {{-- Riepilogo prezzi --}}
                <div class="rounded-xl border border-purple-900/50 p-6 mb-6" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">Riepilogo pagamento</p>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Prezzo carta</span>
                            <span class="text-white font-mono">&#8364;{{ number_format($card->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Commissione piattaforma (8%)</span>
                            <span class="text-white font-mono">&#8364;{{ number_format($card->price * 0.08, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">&#128666; Spedizione</span>
                            <span class="text-white font-mono">&#8364;5.90</span>
                        </div>
                        <div class="border-t border-purple-900/50 pt-3 flex justify-between">
                            <span class="text-white font-bold">Totale</span>
                            <span class="font-black text-xl" style="color: #a855f7;">
                                &#8364;{{ number_format($card->price + 5.9 + $card->price * 0.08, 2) }}
                            </span>
                        </div>
                        <div class="border-t border-purple-900/50 pt-3">
                            <p class="text-gray-500 text-xs">Il venditore riceverà: <span
                                    class="text-green-400 font-bold">&#8364;{{ number_format($card->price + 5.9, 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Banner flusso spedizione --}}
                <div class="rounded-xl p-5 border border-purple-700/50 mb-6" style="background: rgba(124,58,237,0.1);">
                    <p class="text-purple-400 font-bold text-sm mb-4">📦 Come funziona la spedizione</p>
                    <div class="space-y-3">
                        @foreach ([['icona' => '1️⃣', 'testo' => 'Paghi carta + spedizione in escrow — i soldi sono al sicuro'], ['icona' => '2️⃣', 'testo' => 'Il venditore riceve un\'etichetta PDF da stampare e spedisce la carta al nostro centro validazione'], ['icona' => '3️⃣', 'testo' => 'Il validatore esamina la carta e ne certifica autenticità e condizioni'], ['icona' => '4️⃣', 'testo' => 'La carta viene spedita a te e i fondi sbloccati al venditore']] as $step)
                            <div class="flex items-start gap-3">
                                <span class="text-lg">{{ $step['icona'] }}</span>
                                <span class="text-gray-400 text-sm">{{ $step['testo'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Flusso spedizione --}}
                <div class="rounded-xl p-5 border border-purple-700/50 mb-6" style="background: rgba(124,58,237,0.1);">
                    <p class="text-purple-400 font-bold text-sm mb-4">📦 Come funziona la spedizione</p>
                    <div class="space-y-3">
                        @foreach ([['icona' => '1️⃣', 'testo' => 'Paghi carta + spedizione — i fondi sono trattenuti in escrow al sicuro'], ['icona' => '2️⃣', 'testo' => 'Il venditore scarica l\'etichetta PDF, la stampa e spedisce la carta al nostro centro di validazione'], ['icona' => '3️⃣', 'testo' => 'Il validatore esamina la carta e certifica autenticità e condizioni dichiarate'], ['icona' => '4️⃣', 'testo' => 'La carta viene spedita a te e i fondi vengono sbloccati al venditore']] as $step)
                            <div class="flex items-start gap-3">
                                <span class="text-lg flex-shrink-0">{{ $step['icona'] }}</span>
                                <span class="text-gray-400 text-sm">{{ $step['testo'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Banner sicurezza --}}
                <div class="rounded-xl p-5 border border-green-800/50" style="background: rgba(6,78,59,0.2);">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🔒</span>
                        <div>
                            <p class="text-green-400 font-bold text-sm mb-1">I tuoi soldi sono al sicuro</p>
                            <p class="text-gray-500 text-xs leading-relaxed">
                                Il pagamento viene trattenuto dalla piattaforma in modalità <strong
                                    class="text-white">escrow</strong>
                                e rilasciato al venditore solo dopo che il validatore ha confermato l'autenticità della
                                carta.
                                In caso di problemi ricevi un rimborso completo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLONNA DESTRA: Form pagamento --}}
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
            <div>
                <div class="rounded-xl border border-purple-900/50 p-8" style="background: rgba(45,17,84,0.3);">

                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-2">Dati di spedizione</p>
                    <h3 class="text-white font-black text-xl mb-6">Dove spediamo la carta?</h3>

                    <form id="payment-form" method="POST" action="{{ route('checkout', $card) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="card_id" value="{{ $card->id }}">

                        {{-- Nome destinatario --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Nome e cognome *</label>
                            <input type="text" name="shipping_name" required placeholder="Mario Rossi"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>

                        {{-- Indirizzo --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Indirizzo *</label>
                            <input type="text" name="shipping_address" required placeholder="Via Roma 1"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>

                        {{-- Città e CAP --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Città *</label>
                                <input type="text" name="shipping_city" required placeholder="Milano"
                                    class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                    style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">CAP *</label>
                                <input type="text" name="shipping_zip" required placeholder="20100"
                                    class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                    style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            </div>
                        </div>

                        {{-- Telefono --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Telefono</label>
                            <input type="text" name="shipping_phone" placeholder="+39 333 1234567"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>

                        <div class="border-t border-purple-900/50 pt-4">
                            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">Pagamento</p>

                            {{-- Stripe Elements --}}
                           {{-- <div class="mb-4">
                                <label class="block text-gray-400 text-sm mb-2">Carta di credito / debito</label>
                                <div id="card-element" class="w-full px-4 py-3 rounded-xl border"
                                    style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                    @if(!$stripePublicKey || !$clientSecret)
    <div class="p-4 rounded-xl border border-red-800/50 text-red-400 text-sm mt-3"
         style="background: rgba(127,29,29,0.2);">
        &#9888; Pagamento non disponibile al momento. Riprova più tardi.
    </div>
@endif
                                </div>
                                <div id="card-errors" class="text-red-400 text-xs mt-1 hidden"></div>
                            </div>

                            {{-- Accettazione termini --}}
                            <div class="flex items-start gap-3 mb-5 p-4 rounded-xl border border-purple-900/30"
                                style="background: rgba(0,0,0,0.2);">
                                <input type="checkbox" name="accept_terms" id="accept-terms" required
                                    class="mt-0.5 accent-purple-500 w-4 h-4 flex-shrink-0">
                                <label for="accept-terms" class="text-xs text-gray-500 leading-relaxed cursor-pointer">
                                    Comprendo che il pagamento sarà trattenuto in escrow e rilasciato al venditore
                                    solo dopo la validazione fisica della carta. In caso di controversia coopererò
                                    con il processo di risoluzione TCG Vault.
                                </label>
                            </div>
                            {{-- Scelta validatore --}}
                            @if ($validators->isNotEmpty())
                                <div class="rounded-xl border border-purple-900/50 p-6 mb-6"
                                    style="background: rgba(45,17,84,0.3);">
                                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">&#128737;
                                        Scegli il validatore</p>
                                    <p class="text-gray-400 text-xs mb-4">Il validatore esaminerà la carta prima di
                                        spedirtela. Scegli quello che preferisci.</p>
                                    <div class="space-y-3" id="validator-list">
                                        @foreach ($validators as $v)
                                            <label
                                                class="flex items-center gap-4 p-4 rounded-xl border border-purple-900/50 cursor-pointer hover:border-purple-500 transition validator-option"
                                                style="background: rgba(0,0,0,0.2);">
                                                <input type="radio" name="validator_id" value="{{ $v->id }}"
                                                    class="text-purple-600" {{ '' }}>
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden border border-purple-700"
                                                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                                    @if ($v->profile_photo)
                                                        <img src="{{ Storage::url($v->profile_photo) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <span
                                                            class="text-white text-sm font-black">{{ strtoupper(substr($v->name, 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-white font-bold text-sm">{{ $v->name }}</p>
                                                    @if ($v->city)
                                                        <p class="text-gray-500 text-xs">&#128205; {{ $v->city }}</p>
                                                    @endif
                                                    @if ($v->tcg_categories)
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach ($v->tcg_categories as $cat)
                                                                <span
                                                                    class="text-xs px-2 py-0.5 rounded-full border border-purple-800/50 text-purple-300"
                                                                    style="background: rgba(124,58,237,0.2);">
                                                                    {{ strtoupper($cat) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                @php
                                                    $validated = \App\Models\Transaction::where('validator_id', $v->id)
                                                        ->whereIn('status', ['validated', 'shipping', 'completed'])
                                                        ->count();
                                                @endphp
                                                <div class="text-right flex-shrink-0">
                                                    <p class="text-purple-400 font-bold text-lg">{{ $validated }}</p>
                                                    <p class="text-gray-600 text-xs">validate</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Errore validatore --}}
                                <div id="validator-error"
                                    class="hidden mt-3 p-4 rounded-xl border border-red-800/50 text-red-400 text-sm"
                                    style="background: rgba(127,29,29,0.2);">
                                    &#9888; Devi selezionare un validatore per continuare.
                                </div>
                        </div>
                </div>
            @else
                <div class="rounded-xl border border-yellow-800/50 p-4 mb-6" style="background: rgba(234,179,8,0.05);">
                    <p class="text-yellow-400 text-sm">&#9888; Nessun validatore disponibile per questa
                        categoria. Verrà assegnato automaticamente.</p>
                </div>
                @endif

                <button type="submit" id="submit-btn"
                    class="w-full py-4 rounded-xl font-black text-black text-base transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
                    &#128274; Paga &#8364;{{ number_format($card->price + 5.9 + $card->price * 0.08, 2) }}
                    &mdash; Carta o PayPal
                </button>

                <p class="text-center text-gray-600 text-xs mt-3">
                    🔐 Connessione cifrata SSL · Processato da Stripe
                </p>
            </div>
            </form>
        </div>
    </div>
    </div>
   </div>
    </div>
    </div>
@endsection
@push('head')
<script>
// Timer countdown 5 minuti
document.addEventListener('DOMContentLoaded', function() {
    let timeLeft = 300;
    const timerEl = document.getElementById('checkout-timer');
    if (!timerEl) return;
    const interval = setInterval(function() {
        timeLeft--;
        const min = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const sec = (timeLeft % 60).toString().padStart(2, '0');
        timerEl.textContent = min + ':' + sec;
        if (timeLeft <= 60) {
            timerEl.style.color = '#f87171';
        }
        if (timeLeft <= 0) {
            clearInterval(interval);
            window.location.href = '{{ route('marketplace.index') }}';
        }
    }, 1000);
});
</script>
@endpush
@push('head')
<script src="https://js.stripe.com/v3/"></script>
@endpush
@push('scripts')
@if($stripePublicKey && $clientSecret)
<script>
const stripe = Stripe('{{ $stripePublicKey }}');
        const elements = stripe.elements({
            clientSecret: '{{ $clientSecret }}',
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary: '#a855f7',
                    colorBackground: '#0d0d0d',
                    colorText: '#e8e6e0',
                    colorDanger: '#f87171',
                    borderRadius: '12px',
                }
            }
        });

        const paymentElement = elements.create('payment', {
            layout: {
                type: 'tabs',
                defaultCollapsed: false,
            }
        });
        paymentElement.mount('#card-element');

        paymentElement.on('change', function(event) {
            const errorDiv = document.getElementById('card-errors');
            if (event.error) {
                errorDiv.textContent = event.error.message;
                errorDiv.classList.remove('hidden');
            } else {
                errorDiv.classList.add('hidden');
            }
        });

        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Elaborazione...</span>';

            // Prima salva i dati di spedizione
            const formData = new FormData(document.getElementById('payment-form'));
            await fetch('{{ route('checkout.update-shipping', $transaction) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            // Poi processa il pagamento
            // Salva il validatore scelto
            const validatorId = document.querySelector('input[name="validator_id"]:checked')?.value;
            if (!validatorId) {
                document.getElementById('validator-error').classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML =
                    '&#128274; Paga &#8364;{{ number_format($card->price + 5.9 + $card->price * 0.08, 2) }}';
                return;
            }

            await fetch('{{ route('transaction.choose-validator', $transaction) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    validator_id: validatorId
                })
            });
            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{ route('transactions.index') }}',
                },
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                document.getElementById('card-errors').classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '🔒 Paga €{{ number_format($card->price + 5.9, 2) }} in Escrow';
            }
        });
    </script>
@endif
@endpush
