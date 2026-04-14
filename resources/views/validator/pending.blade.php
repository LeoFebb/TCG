@extends('layouts.app')
@section('title', 'Candidatura in Revisione — TCG Vault')
@section('content')
<div class="max-w-xl mx-auto px-6 py-24 text-center animate-in">
    <div class="w-20 h-20 border border-vault-gold/50 flex items-center justify-center mx-auto mb-8">
        <svg class="w-9 h-9 text-vault-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <p class="font-mono text-vault-gold text-xs tracking-widest uppercase mb-3">Candidatura ricevuta</p>
    <h1 class="font-display text-4xl font-light text-vault-text mb-4">
        In attesa di <em class="text-vault-gold">approvazione</em>
    </h1>
    <p class="text-vault-muted text-base leading-relaxed mb-10">
        La tua candidatura come validatore TCG è stata ricevuta e verrà esaminata
        dal nostro team entro <strong class="text-vault-text">48–72 ore lavorative</strong>.
        Riceverai una notifica via email sull'esito.
    </p>

    <div class="gold-line mb-10"></div>

    <div class="text-left space-y-4">
        @foreach([
            ['num' => '01', 'title' => 'Candidatura inviata ✓', 'desc' => 'I tuoi dati e il documento d\'identità sono stati ricevuti.', 'done' => true],
            ['num' => '02', 'title' => 'Revisione dell\'admin', 'desc' => 'Il team TCG Vault verifica le tue credenziali e competenze.', 'done' => false],
            ['num' => '03', 'title' => 'Attivazione account', 'desc' => 'Una volta approvato, avrai accesso alla dashboard validatore.', 'done' => false],
        ] as $step)
            <div class="flex items-start gap-4 p-4 border {{ $step['done'] ? 'border-vault-gold/30 bg-yellow-950/10' : 'border-vault-border' }}">
                <span class="font-mono text-sm {{ $step['done'] ? 'text-vault-gold' : 'text-vault-muted' }} flex-shrink-0 w-6">
                    {{ $step['done'] ? '✓' : $step['num'] }}
                </span>
                <div>
                    <p class="font-body text-sm {{ $step['done'] ? 'text-vault-text' : 'text-vault-muted' }} font-medium">
                        {{ $step['title'] }}
                    </p>
                    <p class="font-body text-xs text-vault-muted mt-0.5">{{ $step['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('marketplace.index') }}" class="inline-block mt-10 text-vault-muted hover:text-vault-gold font-mono text-sm transition-colors">
        ← Torna al marketplace
    </a>
</div>
@endsection




