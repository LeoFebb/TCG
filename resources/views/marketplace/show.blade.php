@extends('layouts.app')

@section('title', $card->name . ' — ' . $card->set_name . ' — TCG Vault')
@section('description', 'Acquista ' . $card->name . ' dal set ' . $card->set_name . '. Condizione: ' . $card->condition
    . '. Validazione certificata TCG Vault.')

@section('content')

    {{-- Breadcrumb --}}
    <div class="max-w-6xl mx-auto px-6 py-4">
        <nav class="text-xs text-gray-500 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-purple-400 transition">Home</a>
            <span>&#8250;</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-purple-400 transition">Marketplace</a>
            <span>&#8250;</span>
            <a href="{{ route('marketplace.category', $card->tcg_category) }}" class="hover:text-purple-400 transition">
                {{ strtoupper($card->tcg_category) }}
            </a>
            <span>&#8250;</span>
            <span class="text-gray-400">{{ $card->name }}</span>
        </nav>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            {{-- Immagine --}}
            <div>
                <div
                    class="aspect-[2/3] bg-black/40 rounded-xl overflow-hidden border border-purple-900/50 max-w-sm mx-auto lg:mx-0">
                    @if ($card->images && count($card->images) > 0)
                        <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0]) }}"
                            alt="{{ $card->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl">&#128293;</span>
                        </div>
                    @endif
                </div>

                @if ($card->images && count($card->images) > 1)
                    <div class="grid grid-cols-4 gap-2 mt-3 max-w-sm mx-auto lg:mx-0">
                        @foreach (array_slice($card->images, 1, 4) as $img)
                            <div class="aspect-[2/3] bg-black/40 rounded-lg overflow-hidden border border-purple-900/50">
                                <img src="{{ str_starts_with($img, 'http') ? $img : Storage::url($img) }}"
                                    alt="{{ $card->name }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Dettagli --}}
            <div>
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="text-purple-400 text-xs font-mono uppercase px-2 py-1 border border-purple-800/50 rounded">
                        {{ $card->tcg_category }}
                    </span>
                    <span class="text-gray-500 text-xs">{{ $card->set_name }} &middot; #{{ $card->card_number }}</span>
                </div>

                <h1 class="text-4xl font-black text-white mb-2">{{ $card->name }}</h1>

                <div class="flex items-center gap-6 mb-6">
                    <div>
                        <span class="text-gray-500 text-xs block">RARITA</span>
                        <span class="text-white text-sm">{{ $card->rarity }}</span>
                    </div>
                    <div class="w-px h-8 bg-purple-900"></div>
                    <div>
                        <span class="text-gray-500 text-xs block">CONDIZIONE</span>
                        <span class="text-white text-sm font-bold">{{ $card->condition }}</span>
                    </div>
                    <div class="w-px h-8 bg-purple-900"></div>
                    <div>
                        <span class="text-gray-500 text-xs block">VENDITORE</span>
                        <span class="text-white text-sm">{{ $card->owner->name }}</span>
                    </div>
                </div>

                {{-- Badge validazione --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-purple-700/50 mb-6"
                    style="background: rgba(124,58,237,0.1);">
                    &#128737; <span class="text-purple-400 text-xs font-mono uppercase tracking-widest">Validazione TCG
                        Vault inclusa</span>
                </div>

                @if ($card->description)
                    <div class="p-4 rounded-xl border border-purple-900/50 mb-6" style="background: rgba(0,0,0,0.2);">
                        <p class="text-gray-500 text-xs uppercase mb-1">Note del venditore</p>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $card->description }}</p>
                    </div>
                @endif

                <div class="border-t border-purple-900/50 pt-6 mb-6">

                    @if ($card->price)
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <span class="text-gray-500 text-xs block uppercase">Prezzo</span>
                                <span class="text-4xl font-black text-purple-400">
                                    &#8364;{{ number_format($card->price, 2) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-500 text-xs block">Commissione (8%)</span>
                                <span
                                    class="text-gray-400 text-sm">&#8364;{{ number_format($card->price * 0.08, 2) }}</span>
                                <span class="text-gray-500 text-xs block mt-1">Spedizione</span>
                                <span class="text-gray-400 text-sm">&#8364;5.90</span>
                            </div>
                        </div>

                        @auth
                            @if ($card->user_id !== auth()->id() && auth()->user()->role !== 'validator')
                                <a href="{{ route('checkout', $card) }}"
                                    class="block w-full py-4 rounded-xl font-bold text-black text-base text-center transition hover:opacity-90 mb-3"
                                    style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
                                    &#128274; Acquista in Escrow &mdash;
                                    &#8364;{{ number_format($card->price + 5.9 + $card->price * 0.08, 2) }}
                                </a>
                                <p class="text-center text-gray-600 text-xs">
                                    &#128737; Pagamento protetto &middot; Validazione certificata inclusa
                                </p>
                            @else
                                @if (auth()->user()->role !== 'validator')
                                    <div class="p-4 rounded-xl border border-purple-900/50 text-center">
                                        <p class="text-gray-500 text-sm">Questa e una tua carta.</p>
                                    </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full py-4 rounded-xl font-bold text-white text-base text-center transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                Accedi per acquistare
                            </a>
                        @endauth
                    @endif

                    @if ($card->available_for_trade)
                        <div class="border border-blue-800/40 rounded-xl p-5 mt-4"
                            style="background: rgba(59,130,246,0.05);">
                            <div class="flex items-center gap-2 mb-3">
                                &#128260; <span
                                    class="text-blue-400 text-xs font-mono uppercase tracking-widest">Disponibile per
                                    permuta</span>
                            </div>
                            @auth
                                @if ($card->user_id !== auth()->id() && isset($tradeOptions))
                                    <form method="POST" action="{{ route('trade.offer') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="card_id" value="{{ $card->id }}">

                                        <div class="mb-3">
                                            <label class="block text-gray-400 text-sm mb-2">Seleziona carta da offrire</label>
                                            <select id="trade-select-dropdown"
                                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border mb-2"
                                                style="background: rgba(45,17,84,0.95); border-color: rgba(124,58,237,0.5); color: #e8e6e0;"
                                                onchange="handleTradeSelect(this)">
                                                <option value="">Seleziona una carta dal catalogo...</option>
                                                @foreach ($catalogCards as $cc)
                                                    <option value="{{ $cc->name }} - {{ $cc->set_name }}">
                                                        {{ $cc->name }} — {{ $cc->set_name }}
                                                        {{ $cc->card_number ? '#' . $cc->card_number : '' }}
                                                    </option>
                                                @endforeach
                                                <option value="__manual__">✏️ Scrivi manualmente...</option>
                                            </select>

                                            <input type="text" name="offered_card_name" id="trade-manual-input"
                                                placeholder="Scrivi il nome della carta..."
                                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border mb-3 hidden"
                                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                        </div>
                                        {{-- Scelta validatore acquirente --}}
                                        <div class="mb-3">
                                            <label class="block text-gray-400 text-sm mb-2">&#128737; Scegli il tuo
                                                validatore</label>
                                            <select name="buyer_validator_id" required
                                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border mb-3"
                                                style="background: rgba(45,17,84,0.95); border-color: rgba(59,130,246,0.3);">
                                                <option value="">Seleziona un validatore...</option>
                                                @foreach (\App\Models\User::where('role', 'validator')->where('is_verified_validator', true)->whereJsonContains('tcg_categories', $card->tcg_category)->get() as $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }} —
                                                        {{ $v->city }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
    <label class="block text-gray-400 text-sm mb-2">&#128247; Foto della tua carta *</label>
    <label for="offered_card_image" class="block border-2 border-dashed border-purple-800/50 rounded-xl p-4 text-center cursor-pointer hover:border-purple-500 transition">
        <div id="offered-preview" class="hidden mb-3">
            <img id="offered-img" src="" class="max-h-40 mx-auto rounded-lg">
        </div>
        <p class="text-gray-400 text-sm" id="offered-text">Clicca per caricare la foto della tua carta</p>
        <p class="text-gray-600 text-xs mt-1">JPEG, PNG · Max 5MB</p>
    </label>
    <input type="file" id="offered_card_image" name="offered_card_image" accept="image/*" required
       style="display:none;" onchange="previewOfferedCard(this)">
</div>
                                        <button type="submit" <button type="submit"
                                            class="w-full py-3 rounded-xl font-bold text-white text-sm border border-blue-700 hover:bg-blue-900/30 transition">
                                            &#128260; Proponi permuta
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="block text-center py-3 rounded-xl text-sm border border-blue-700 text-blue-400 hover:bg-blue-900/30 transition">
                                    Accedi per proporre permuta
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
function previewOfferedCard(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const file = input.files[0];
        reader.onload = function(e) {
            document.getElementById('offered-img').src = e.target.result;
            document.getElementById('offered-preview').classList.remove('hidden');
            document.getElementById('offered-text').textContent = file.name;
        };
        reader.readAsDataURL(file);
    }
}
</script>
<script>
function previewOfferedCard(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const file = input.files[0];
        reader.onload = function(e) {
            document.getElementById('offered-img').src = e.target.result;
            document.getElementById('offered-preview').classList.remove('hidden');
            document.getElementById('offered-text').textContent = file.name;
        };
        reader.readAsDataURL(file);
    }
}

function handleTradeSelect(select) {
    const manualInput = document.getElementById('trade-manual-input');
    if (select.value === '__manual__') {
        manualInput.classList.remove('hidden');
        manualInput.required = true;
        select.name = '';
        manualInput.name = 'offered_card_name';
        manualInput.focus();
    } else {
        manualInput.classList.add('hidden');
        manualInput.required = false;
        select.name = 'offered_card_name';
        manualInput.name = '';
    }
}
</script>
    @push('scripts')
<script defer>
window.addEventListener('load', function() {
    const offeredInput = document.getElementById('offered_card_image');
    if (offeredInput) {
        offeredInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const file = this.files[0];
                reader.onload = function(e) {
                    document.getElementById('offered-img').src = e.target.result;
                    document.getElementById('offered-preview').classList.remove('hidden');
                    document.getElementById('offered-text').textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function handleTradeSelect(select) {
    const manualInput = document.getElementById('trade-manual-input');
    if (select.value === '__manual__') {
        manualInput.classList.remove('hidden');
        manualInput.required = true;
        select.name = '';
        manualInput.name = 'offered_card_name';
        manualInput.focus();
    } else {
        manualInput.classList.add('hidden');
        manualInput.required = false;
        select.name = 'offered_card_name';
        manualInput.name = '';
    }
}

document.getElementById('trade-manual-input')?.addEventListener('blur', function() {
    const cardName = this.value.trim();
    if (cardName.length > 2) {
        fetch('{{ route("catalog.add-card") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                card_name: cardName,
                category: '{{ $card->tcg_category }}'
            })
        });
    }
});
</script>
@endpush
@endsection
