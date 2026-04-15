@extends('layouts.app')

@section('title', 'Le mie carte — TCG Vault')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Account</p>
                <h1 class="text-3xl font-black text-white">Le mie carte</h1>
            </div>
            <a href="{{ route('cards.create') }}" class="px-6 py-3 rounded-xl font-bold text-white text-sm"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                + Pubblica carta
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-3 md:px-6 py-4 md:py-8">

        @forelse($cards as $card)
            <div class="rounded-xl border border-purple-900/50 p-4 mb-4 flex items-center gap-3 md:gap-5"
                style="background: rgba(45,17,84,0.2);">

                {{-- Immagine --}}
                <div class="w-16 h-24 rounded-lg overflow-hidden flex-shrink-0 bg-black/40">
                    @if ($card->images && count($card->images) > 0)
                        <img src="{{ str_starts_with($card->images[0], 'http') ? $card->images[0] : Storage::url($card->images[0]) }}"
                            alt="{{ $card->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-2xl">🔥</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <p class="text-purple-400 text-xs font-mono uppercase mb-1">{{ $card->tcg_category }} ·
                        {{ $card->condition }}</p>
                    <h3 class="text-white font-bold text-lg">{{ $card->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $card->set_name }} · #{{ $card->card_number }}</p>
                    @if ($card->price)
                        <p class="text-purple-400 font-bold mt-1">€{{ number_format($card->price, 2) }}</p>
                    @endif
                </div>

                {{-- Stato --}}
                <div class="flex-shrink-0 text-center">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold
                    @if ($card->status === 'available') bg-green-900/50 text-green-400 border border-green-800
                    @elseif($card->status === 'in_negotiation') bg-yellow-900/50 text-yellow-400 border border-yellow-800
                    @else bg-gray-900/50 text-gray-400 border border-gray-800 @endif">
                        @if ($card->status === 'available')
                            ✅ Disponibile
                        @elseif($card->status === 'in_negotiation')
                            ⏳ In trattativa
                        @elseif($card->status === 'sold')
                            🎉 Venduta
                        @endif
                    </span>
                    <p class="text-gray-600 text-xs mt-1">
                        ⏳ Da validare
                    </p>
                </div>

                {{-- Azioni --}}
                <div class="flex-shrink-0 flex gap-2">
                    <a href="{{ route('marketplace.show', $card) }}"
                        class="px-4 py-2 rounded-lg text-sm border border-purple-800 text-purple-400 hover:bg-purple-900/30 transition">
                        👁 Vedi
                    </a>
                    @if ($card->status === 'available')
                        <button type="button" onclick="openDeleteModal({{ $card->id }}, '{{ $card->name }}')"
                            class="px-4 py-2 rounded-lg text-sm border border-red-800 text-red-400 hover:bg-red-900/30 transition">
                            🗑 Elimina
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl p-20 text-center border border-purple-900/50" style="background: rgba(45,17,84,0.2);">
                <div class="text-6xl mb-4">🔥</div>
                <h3 class="text-2xl font-bold text-white mb-2">Non hai ancora pubblicato carte</h3>
                <a href="{{ route('cards.create') }}" class="px-6 py-3 rounded-xl font-bold text-white inline-block mt-4"
                    style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    + Pubblica la tua prima carta
                </a>
            </div>
        @endforelse
    </div>

    {{-- Modal eliminazione --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.8); backdrop-filter: blur(5px);">
        <div class="rounded-2xl border border-red-800/50 p-8 w-full max-w-md" style="background: rgba(45,17,84,0.95);">
            <div class="text-center mb-6">
                <div class="text-5xl mb-4">🗑</div>
                <h3 class="text-2xl font-black text-white mb-2">Elimina carta</h3>
                <p class="text-gray-400 text-sm">
                    Sei sicuro di voler eliminare <strong class="text-white" id="cardName"></strong>?
                    <br>Questa azione non può essere annullata.
                </p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 py-3 rounded-xl font-bold text-gray-400 border border-gray-700 hover:text-white transition">
                        Annulla
                    </button>
                    <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-white transition"
                        style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                        🗑 Sì, elimina
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openDeleteModal(id, name) {
                document.getElementById('cardName').textContent = name;
                document.getElementById('deleteForm').action = '/cards/' + id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });
        </script>
    @endpush

@endsection
