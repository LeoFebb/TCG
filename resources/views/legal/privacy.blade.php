@extends('layouts.app')

@section('title', 'Privacy Policy — TCG Vault')

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-16">
        <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-2">Legale</p>
        <h1 class="text-4xl font-black text-white mb-8">Privacy Policy</h1>

        <div class="space-y-8 text-gray-400 leading-relaxed">

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">1. Titolare del trattamento</h2>
                <p>TCG Vault è il titolare del trattamento dei dati personali raccolti attraverso questa piattaforma. Per
                    qualsiasi informazione: <span class="text-purple-400">privacy@tcgvault.it</span></p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">2. Dati raccolti</h2>
                <p>Raccogliamo i seguenti dati:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Nome e cognome</li>
                    <li>Indirizzo email</li>
                    <li>Indirizzo di spedizione</li>
                    <li>Dati di pagamento (gestiti da Stripe)</li>
                    <li>Documento d'identità (solo per i validatori)</li>
                </ul>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">3. Finalità del trattamento</h2>
                <p>I dati vengono utilizzati per:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Gestire le transazioni nel marketplace</li>
                    <li>Verificare l'identità dei validatori</li>
                    <li>Processare i pagamenti tramite Stripe</li>
                    <li>Gestire le spedizioni</li>
                    <li>Inviare comunicazioni relative alle transazioni</li>
                </ul>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">4. Conservazione dei dati</h2>
                <p>I dati vengono conservati per il tempo strettamente necessario alle finalità per cui sono stati raccolti,
                    nel rispetto del GDPR (Regolamento UE 2016/679).</p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">5. Diritti dell'utente</h2>
                <p>Hai il diritto di accedere, rettificare, cancellare i tuoi dati personali. Per esercitare questi diritti
                    contattaci a <span class="text-purple-400">privacy@tcgvault.it</span></p>
            </div>

            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.2);">
                <h2 class="text-xl font-black text-white mb-3">6. Cookie</h2>
                <p>Utilizziamo cookie tecnici necessari al funzionamento della piattaforma. Non utilizziamo cookie di
                    profilazione o tracciamento.</p>
            </div>

            <p class="text-gray-600 text-sm">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
        </div>
    </div>
@endsection

