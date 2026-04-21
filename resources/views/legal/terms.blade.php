@extends('layouts.app')

@section('title', 'Termini di Servizio — TCG SafeSwap')

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-16">
        <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-2">Legale</p>
        <h1 class="text-4xl font-black text-white mb-8">Termini di Servizio</h1>

        <div class="space-y-8 text-gray-400 leading-relaxed">

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">1. Accettazione dei termini</h2>
                <p>Utilizzando TCG SafeSwap accetti i presenti termini di servizio. Se non accetti questi termini non puoi
                    utilizzare la piattaforma.</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">2. Il servizio</h2>
                <p>TCG SafeSwap è un marketplace per la compravendita e lo scambio di carte da gioco collezionabili. Ogni
                    transazione è soggetta a validazione da parte di esperti certificati.</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">3. Sistema Escrow</h2>
                <p>I pagamenti vengono trattenuti dalla piattaforma in modalità escrow e rilasciati al venditore solo dopo
                    la validazione della carta. TCG SafeSwap non è responsabile per eventuali controversie tra utenti.</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">4. Commissioni</h2>
                <p>TCG SafeSwap applica una commissione dell'8% su ogni vendita completata. Le spese di spedizione (€5.90 per
                    ogni tratta) sono a carico dell'acquirente.</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">5. Carte e contenuti</h2>
                <p>Gli utenti sono responsabili delle carte che pubblicano. È vietato pubblicare carte contraffatte, rubate
                    o non di propria proprietà. TCG SafeSwap si riserva il diritto di rimuovere qualsiasi inserzione.</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">6. Limitazione di responsabilità</h2>
                <p>TCG SafeSwap non è responsabile per danni derivanti dall'uso della piattaforma, inclusi danni diretti,
                    indiretti, incidentali o consequenziali.</p>
            </div>

            <p class="text-gray-600 text-sm">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
        </div>
    </div>
@endsection

