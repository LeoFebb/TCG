<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 10px; }
        .label { border: 3px solid #000; padding: 12px; height: 100%; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .logo { font-size: 22px; font-weight: bold; letter-spacing: 2px; }
        .tipo-badge { display: inline-block; background: #7c3aed; color: white; padding: 4px 14px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-top: 6px; }
        .section { margin: 8px 0; }
        .section-title { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 3px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 2px; }
        .section-content { font-size: 12px; line-height: 1.6; }
        .nome { font-size: 14px; font-weight: bold; }
        .ruolo-badge { display: inline-block; background: #f3f4f6; border: 1px solid #ddd; padding: 2px 8px; border-radius: 3px; font-size: 10px; color: #555; margin-bottom: 3px; }
        .arrow { text-align: center; font-size: 28px; margin: 6px 0; color: #7c3aed; }
        .barcode-area { text-align: center; border: 2px dashed #7c3aed; padding: 8px; margin: 8px 0; background: #f9f7ff; border-radius: 4px; }
        .tracking { font-size: 18px; font-weight: bold; letter-spacing: 4px; font-family: monospace; color: #7c3aed; }
        .card-info { background: #f9f7ff; border: 1px solid #e9d5ff; padding: 8px; margin: 8px 0; border-radius: 4px; }
        .card-name { font-size: 14px; font-weight: bold; }
        .card-detail { font-size: 10px; color: #666; margin-top: 2px; }
        .warning { background: #fef3c7; border: 1px solid #f59e0b; padding: 6px 8px; margin: 8px 0; font-size: 10px; border-radius: 4px; }
        .certified { background: #d1fae5; border: 1px solid #10b981; padding: 6px 8px; margin: 8px 0; font-size: 10px; border-radius: 4px; color: #065f46; font-weight: bold; }
        .istruzioni { background: #f3f4f6; border: 1px solid #e5e7eb; padding: 6px 8px; margin: 6px 0; font-size: 10px; border-radius: 4px; color: #374151; }
        .footer { text-align: center; font-size: 9px; color: #888; border-top: 1px solid #ddd; padding-top: 6px; margin-top: 8px; }
        .divider { border-top: 1px dashed #ccc; margin: 8px 0; }
    </style>
</head>
<body>
<div class="label">

    {{-- Header --}}
    <div class="header">
        <div class="logo">🛡 TCG VAULT</div>
        <div style="font-size: 10px; color: #555; margin-top: 2px;">Marketplace TCG Certificato</div>
        <div class="tipo-badge">{{ $tipo }}</div>
    </div>

    {{-- Tracking --}}
    <div class="barcode-area">
        <div style="font-size: 9px; color: #888; margin-bottom: 4px;">CODICE SPEDIZIONE</div>
        <div class="tracking">{{ $tracking_code }}</div>
        <div style="font-size: 9px; color: #888; margin-top: 2px;">
            Transazione #{{ $transaction->id }} — Generata il {{ $generated_at }}
        </div>
    </div>

    {{-- Mittente --}}
    <div class="section">
        <div class="section-title">📤 Mittente</div>
        <div class="section-content">
            <div class="ruolo-badge">{{ $mittente['ruolo'] }}</div>
            <div class="nome">{{ $mittente['nome'] }}</div>
            @if(isset($mittente['address']))
                <div style="font-size: 11px; color: #555;">{{ $mittente['address'] }}</div>
            @endif
            @if(isset($mittente['phone']))
                <div style="font-size: 11px; color: #555;">Tel: {{ $mittente['phone'] }}</div>
            @endif
        </div>
    </div>

    <div class="arrow">⬇</div>

    {{-- Destinatario --}}
    <div class="section">
        <div class="section-title">📥 Destinatario</div>
        <div class="section-content">
            <div class="nome">{{ $destinatario['name'] }}</div>
            @if(isset($destinatario['address']))
                <div>{{ $destinatario['address'] }}</div>
            @endif
            @if(isset($destinatario['zip']) && isset($destinatario['city']))
                <div>{{ $destinatario['zip'] }} {{ $destinatario['city'] }}</div>
            @endif
            @if(isset($destinatario['country']))
                <div>{{ $destinatario['country'] }}</div>
            @endif
            @if(isset($destinatario['phone']) && $destinatario['phone'])
                <div>Tel: {{ $destinatario['phone'] }}</div>
            @endif
        </div>
    </div>

    <div class="divider"></div>

    {{-- Info carta --}}
    <div class="card-info">
        <div style="font-size: 9px; color: #888; margin-bottom: 4px;">🃏 CONTENUTO</div>
        <div class="card-name">{{ $transaction->card->name }}</div>
        <div class="card-detail">
            {{ $transaction->card->set_name }} · #{{ $transaction->card->card_number }}<br>
            Condizione: {{ $transaction->card->condition }} · {{ strtoupper($transaction->card->tcg_category) }}
        </div>
    </div>

    {{-- Badge certificazione se è rispedizione --}}
    @if(isset($is_return) && $is_return)
        <div class="certified">
            ✅ CARTA CERTIFICATA AUTENTICA — TCG Vault
        </div>
    @endif

    {{-- Warning --}}
    <div class="warning">
        ⚠️ <strong>FRAGILE</strong> — Inserire in toploader. Non piegare. Proteggere dall'umidità.
    </div>

    {{-- Istruzioni --}}
    <div class="istruzioni">
        📋 {{ $istruzioni }}
    </div>

    {{-- Footer --}}
    <div class="footer">
        TCG Vault © {{ date('Y') }} — support@tcgvault.it<br>
        Questa etichetta è valida solo per la transazione #{{ $transaction->id }}
    </div>
</div>
</body>
</html>



