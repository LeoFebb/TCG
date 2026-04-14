@extends('layouts.app')

@section('title', 'Dashboard Validatore — TCG Vault')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- Header dashboard --}}
        <div class="mb-10 animate-in">
            <p class="font-mono text-vault-gold text-xs tracking-widest uppercase mb-2">Dashboard</p>
            <h1 class="font-display text-4xl font-light text-vault-text">
                Centro di <em class="text-vault-gold">Validazione</em>
            </h1>
            <p class="text-vault-muted text-sm mt-2">
                Benvenuto, {{ auth()->user()->name }} · Validatore TCG Certificato
            </p>
        </div>

        <div class="gold-line mb-10"></div>

        {{-- Statistiche --}}
        <div class="grid grid-cols-3 gap-4 mb-12">
            @foreach ([['label' => 'In attesa', 'value' => $stats['pending'], 'color' => 'text-amber-400', 'bg' => 'bg-amber-950/30', 'border' => 'border-amber-800/40'], ['label' => 'Validate', 'value' => $stats['validated'], 'color' => 'text-sky-400', 'bg' => 'bg-sky-950/30', 'border' => 'border-sky-800/40'], ['label' => 'Completate', 'value' => $stats['completed'], 'color' => 'text-vault-gold', 'bg' => 'bg-yellow-950/20', 'border' => 'border-vault-border']] as $stat)
                <div class="border {{ $stat['border'] }} {{ $stat['bg'] }} p-6 animate-in">
                    <p class="font-mono text-xs text-vault-muted uppercase tracking-widest mb-2">{{ $stat['label'] }}</p>
                    <p class="font-display text-5xl font-light {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Task pendenti --}}
        <div class="mb-12">
            <h2 class="font-display text-2xl font-light mb-6 flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Task in Attesa
            </h2>

            @forelse($pendingTasks as $transaction)
                <div class="border border-vault-border bg-vault-card mb-4 card-hover animate-in"
                    style="animation-delay: {{ $loop->index * 0.05 }}s">
                    <div class="p-6">
                        <div class="flex items-start justify-between flex-wrap gap-4">

                            {{-- Info transazione --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    {{-- Tipo transazione --}}
                                    <span
                                        class="font-mono text-xs px-2 py-0.5 border border-vault-border text-vault-muted uppercase">
                                        {{ $transaction->type === 'sale' ? '💳 Vendita' : '🔄 Permuta' }}
                                    </span>

                                    {{-- Stato --}}
                                    <span class="status-badge status-{{ $transaction->status }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @php
                                            $statusLabels = [
                                                'pending' => '⏳ In attesa',
                                                'paid_escrow' => '💰 Pagato',
                                                'in_validation' => '🔬 In validazione',
                                                'validated' => '✅ Validata',
                                                'shipping' => '📦 In spedizione',
                                                'completed' => '🎉 Completata',
                                                'disputed' => '⚠️ Controversia',
                                            ];
                                        @endphp
                                        {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                                    </span>

                                    {{-- Categoria TCG --}}
                                    <span class="font-mono text-xs text-vault-muted uppercase">
                                        {{ strtoupper($transaction->card->tcg_category) }}
                                    </span>
                                </div>

                                {{-- Nome carta --}}
                                @if ($transaction->card->images && count($transaction->card->images) > 0)
                                    <img src="{{ Storage::url($transaction->card->images[0]) }}"
                                        alt="{{ $transaction->card->name }}"
                                        style="width: 80px; height: 112px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; border: 1px solid rgba(124,58,237,0.3);">
                                @endif

                                <h3 class="font-display text-xl text-vault-text mb-1">
                                    {{ $transaction->card->name }}
                                    <h3 class="font-display text-xl text-vault-text mb-1">
                                        {{ $transaction->card->name }}
                                        <span class="text-vault-muted font-light text-base">
                                            — {{ $transaction->card->set_name }} #{{ $transaction->card->card_number }}
                                        </span>
                                    </h3>

                                    {{-- Dettagli permuta --}}
                                    @if ($transaction->type === 'trade' && $transaction->offeredCard)
                                        <p class="text-vault-muted text-sm mt-1">
                                            ↔ In scambio con:
                                            <span class="text-vault-text">{{ $transaction->offeredCard->name }}</span>
                                            ({{ $transaction->offeredCard->set_name }})
                                        </p>
                                    @endif

                                    {{-- Parti coinvolte --}}
                                    <div class="flex items-center gap-6 mt-3 text-sm">
                                        <div>
                                            <span class="text-vault-muted font-mono text-xs">VENDITORE </span>
                                            <span class="text-vault-text">{{ $transaction->seller->name }}</span>
                                        </div>
                                        <span class="text-vault-border">→</span>
                                        <div>
                                            <span class="text-vault-muted font-mono text-xs">ACQUIRENTE </span>
                                            <span class="text-vault-text">{{ $transaction->buyer->name }}</span>
                                        </div>
                                    </div>

                                    {{-- Stato ricezione (solo permute) --}}
                                    @if ($transaction->type === 'trade')
                                        <div class="flex items-center gap-4 mt-3">
                                            <div class="flex items-center gap-1.5">
                                                <span
                                                    class="w-2 h-2 rounded-full {{ $transaction->seller_shipped ? 'bg-emerald-400' : 'bg-vault-border' }}"></span>
                                                <span class="text-xs font-mono text-vault-muted">Carta venditore
                                                    ricevuta</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span
                                                    class="w-2 h-2 rounded-full {{ $transaction->buyer_shipped ? 'bg-emerald-400' : 'bg-vault-border' }}"></span>
                                                <span class="text-xs font-mono text-vault-muted">Carta acquirente
                                                    ricevuta</span>
                                            </div>
                                        </div>
                                    @endif
                            </div>

                            {{-- Importo (solo vendite) --}}
                            @if ($transaction->type === 'sale')
                                <div class="text-right">
                                    <p class="font-mono text-xs text-vault-muted uppercase mb-1">Importo in escrow</p>
                                    <p class="font-display text-3xl text-vault-gold">
                                        €{{ number_format($transaction->amount, 2, ',', '.') }}
                                    </p>
                                    <p class="font-mono text-xs text-vault-muted mt-1">
                                        Comm. piattaforma: €{{ number_format($transaction->platform_fee, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Azioni --}}
                        <div class="flex items-center gap-3 mt-6 pt-5 border-t border-vault-border">

                            {{-- Dettagli --}}
                            <a href="{{ route('validator.transaction.show', $transaction) }}"
                                class="px-4 py-2 border border-vault-border text-vault-muted hover:text-vault-text hover:border-vault-gold text-sm transition-all">
                                Esamina dettagli →
                            </a>

                            @if ($transaction->canBeValidated())
                                {{-- APPROVA --}}
                                <form method="POST" action="{{ route('validator.transaction.approve', $transaction) }}"
                                    onsubmit="return openApproveModal(event, this)">
                                    @csrf
                                    <input type="hidden" name="validator_notes" value="">
                                    <button type="submit"
                                        class="btn-gold px-6 py-2 text-sm rounded-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approva
                                    </button>
                                </form>

                                {{-- RIFIUTA --}}
                                <button onclick="openRejectModal({{ $transaction->id }})"
                                    class="px-6 py-2 bg-red-950/50 border border-red-800/50 text-red-400 hover:bg-red-900/50 text-sm transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Rifiuta
                                </button>
                            @endif

                            {{-- Pulsante etichetta rispedizione (solo dopo validazione) --}}
                            @if (in_array($transaction->status, ['validated', 'shipping']))
                                <a href="{{ route('shipping.return-label', $transaction) }}" target="_blank"
                                    class="px-4 py-2 rounded-lg border border-purple-700 text-purple-400 hover:bg-purple-900/30 text-sm transition flex items-center gap-2">
                                    &#128424; Stampa etichetta rispedizione
                                </a>
                                @if ($transaction->status === 'validated')
                                    <form method="POST" action="{{ route('validator.mark-shipped', $transaction) }}"
                                        class="flex items-center gap-2 mt-2">
                                        @csrf
                                        <input type="text" name="return_tracking_number"
                                            placeholder="Tracking (opzionale)"
                                            class="px-3 py-2 rounded-lg text-white text-xs border flex-1"
                                            style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg text-xs font-bold text-white whitespace-nowrap"
                                            style="background: linear-gradient(135deg, #059669, #10b981);">
                                            &#9989; Ho spedito
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Conferma ricezione carta (permute) --}}
                            @if ($transaction->type === 'trade' && $transaction->status === 'in_validation')
                                @if (!$transaction->seller_shipped)
                                    <form method="POST"
                                        action="{{ route('validator.transaction.confirm-received', $transaction) }}">
                                        @csrf
                                        <input type="hidden" name="party" value="seller">
                                        <button
                                            class="px-4 py-2 border border-sky-800/50 text-sky-400 hover:border-sky-600 text-sm transition-all">
                                            ✓ Ricevuta carta venditore
                                        </button>
                                    </form>
                                @endif
                                @if (!$transaction->buyer_shipped)
                                    <form method="POST"
                                        action="{{ route('validator.transaction.confirm-received', $transaction) }}">
                                        @csrf
                                        <input type="hidden" name="party" value="buyer">
                                        <button
                                            class="px-4 py-2 border border-sky-800/50 text-sky-400 hover:border-sky-600 text-sm transition-all">
                                            ✓ Ricevuta carta acquirente
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Timestamp --}}
                            <span class="ml-auto font-mono text-xs text-vault-muted">
                                {{ $transaction->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="border border-vault-border bg-vault-card p-12 text-center">
                    <p class="font-display text-2xl text-vault-muted font-light">Nessun task in attesa</p>
                    <p class="text-vault-muted text-sm mt-2">Torna più tardi per nuove transazioni da validare.</p>
                </div>
            @endforelse

            {{-- Paginazione --}}
            <div class="mt-6">
                {{ $pendingTasks->links() }}
            </div>
        </div>

        {{-- Transazioni recenti completate --}}
        @if ($completedTasks->isNotEmpty())
            <div>
                <h2 class="font-display text-2xl font-light mb-6 text-vault-muted">Validate di recente</h2>
                <div class="border border-vault-border overflow-hidden">
                    <table class="w-full">
                        <thead class="border-b border-vault-border">
                            <tr class="text-left">
                                <th class="font-mono text-xs text-vault-muted uppercase tracking-widest px-6 py-3">Carta
                                </th>
                                <th class="font-mono text-xs text-vault-muted uppercase tracking-widest px-6 py-3">Tipo
                                </th>
                                <th class="font-mono text-xs text-vault-muted uppercase tracking-widest px-6 py-3">Stato
                                </th>
                                <th class="font-mono text-xs text-vault-muted uppercase tracking-widest px-6 py-3">Data
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($completedTasks as $t)
                                <tr class="border-b border-vault-border/50 hover:bg-vault-surface/50 transition-colors">
                                    <td class="px-6 py-3 font-body text-sm text-vault-text">{{ $t->card->name }}</td>
                                    <td class="px-6 py-3">
                                        <span class="font-mono text-xs text-vault-muted uppercase">
                                            {{ $t->type === 'sale' ? 'Vendita' : 'Permuta' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="status-badge status-{{ $t->status }}">{{ $t->status }}</span>
                                    </td>
                                    <td class="px-6 py-3 font-mono text-xs text-vault-muted">
                                        {{ $t->validated_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        @if (in_array($t->status, ['validated', 'shipping']))
                                            <a href="{{ route('shipping.return-label', $t) }}" target="_blank"
                                                class="px-3 py-1 rounded-lg border border-purple-700 text-purple-400 hover:bg-purple-900/30 text-xs transition block mb-2">
                                                &#128424; Etichetta rispedizione
                                            </a>
                                            @if ($t->status === 'validated')
                                                <form method="POST" action="{{ route('validator.mark-shipped', $t) }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="text" name="return_tracking_number"
                                                        placeholder="Tracking (opzionale)"
                                                        class="px-2 py-1 rounded-lg text-white text-xs border"
                                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3); width: 130px;">
                                                    <button type="submit"
                                                        class="px-3 py-1 rounded-lg text-xs font-bold text-white whitespace-nowrap"
                                                        style="background: linear-gradient(135deg, #059669, #10b981);">
                                                        &#9989; Ho spedito
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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

    {{-- Modal Rifiuto --}}
    <div id="rejectModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-vault-card border border-red-800/50 w-full max-w-md p-8">
            <h3 class="font-display text-2xl font-light text-red-400 mb-2">Rifiuta Transazione</h3>
            <p class="text-vault-muted text-sm mb-6">
                Descrivi dettagliatamente il motivo del rifiuto. Questa nota sarà visibile all'admin e alle parti coinvolte.
            </p>
            <form id="rejectForm" method="POST">
                @csrf
                {{-- CSRF: protegge l'endpoint da attacchi cross-site request forgery --}}
                <textarea name="rejection_reason" required minlength="20" maxlength="2000" rows="5"
                    placeholder="Es: La carta presenta segni di usura non dichiarati nel listing, condizione LP vs NM dichiarato. Bordi del fronte segnati con..."
                    class="w-full bg-vault-surface border border-vault-border text-vault-text p-4 text-sm resize-none focus:outline-none focus:border-red-600 font-body"></textarea>
                <p class="font-mono text-xs text-vault-muted mt-1 mb-6">Minimo 20 caratteri</p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 py-3 border border-vault-border text-vault-muted hover:text-vault-text text-sm transition-colors">
                        Annulla
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-red-900/80 border border-red-700 text-red-300 hover:bg-red-800 text-sm transition-colors">
                        Conferma Rifiuto
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openRejectModal(transactionId) {
            const form = document.getElementById('rejectForm');
            // Imposta l'action del form con l'ID della transazione corretta
            form.action = `/validator/transaction/${transactionId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Chiudi il modal cliccando fuori dal contenuto
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
@endpush
