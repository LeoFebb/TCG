<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 5mm;
        }

        html,
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            background: white;
        }

        .label {
            border: 2px solid #000;
            padding: 5px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        .logo {
            font-size: 12px;
            font-weight: bold;
        }

        .tipo-badge {
            background: #7c3aed;
            color: white;
            padding: 1px 5px;
            font-size: 7px;
            font-weight: bold;
        }

        .tracking-code {
            font-family: monospace;
            font-size: 9px;
            font-weight: bold;
            color: #7c3aed;
        }

        .addresses {
            display: flex;
            gap: 4px;
        }

        .address-box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 3px;
            min-width: 0;
        }

        .address-box.highlight {
            border: 2px solid #000;
            background: #f9f7ff;
        }

        .address-label {
            font-size: 7px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .address-name {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 1px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .address-role {
            display: inline-block;
            background: #f3f4f6;
            border: 1px solid #ddd;
            padding: 1px 3px;
            font-size: 7px;
            color: #555;
            margin-bottom: 2px;
        }

        .address-detail {
            font-size: 7px;
            line-height: 1.3;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #7c3aed;
            padding: 0 2px;
            flex-shrink: 0;
        }

        .card-info {
            background: #f9f7ff;
            border: 1px solid #e9d5ff;
            padding: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-name {
            font-size: 9px;
            font-weight: bold;
        }

        .card-detail {
            font-size: 7px;
            color: #666;
        }

        .badge {
            background: #d1fae5;
            border: 1px solid #10b981;
            padding: 1px 4px;
            font-size: 7px;
            color: #065f46;
            font-weight: bold;
        }

        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 2px 4px;
            font-size: 7px;
        }

        .instructions {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 2px 4px;
            font-size: 7px;
            color: #374151;
        }

        .footer {
            text-align: center;
            font-size: 6px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 2px;
        }
    </style>
</head>

<body>
    <div class="label">

        {{-- Header --}}
        <div class="header">
            <div>
                <div class="logo">&#127183; TCG VAULT</div>
                <div style="font-size: 8px; color: #555;">Marketplace TCG Certificato</div>
            </div>
            <div style="text-align: center;">
                <div class="tipo-badge">{{ $tipo }}</div>
                <div class="tracking-code" style="margin-top: 3px;">{{ $tracking_code }}</div>
            </div>
            <div style="text-align: center; font-size: 8px; color: #888;">
                Transazione #{{ $transaction->id }}<br>
                {{ $generated_at }}
            </div>
        </div>

        {{-- Indirizzi --}}
        <div class="addresses">
            <div class="address-box">
                <div class="address-label">&#128228; Mittente</div>
                <div class="address-role">{{ $mittente['ruolo'] }}</div>
                <div class="address-name">{{ $mittente['nome'] }}</div>
                @if (isset($mittente['address']) && $mittente['address'])
                    <div class="address-detail">{{ $mittente['address'] }}</div>
                @endif
                @if (isset($mittente['phone']) && $mittente['phone'])
                    <div class="address-detail">Tel: {{ $mittente['phone'] }}</div>
                @endif
            </div>
            <div class="arrow">&#10132;</div>
            <div class="address-box highlight">
                <div class="address-label">&#128229; Destinatario</div>
                <div class="address-name">{{ $destinatario['name'] }}</div>
                @if (isset($destinatario['address']) && $destinatario['address'])
                    <div class="address-detail">{{ $destinatario['address'] }}</div>
                @endif
                @if (isset($destinatario['zip']) && isset($destinatario['city']) && $destinatario['city'])
                    <div class="address-detail">{{ $destinatario['zip'] }} {{ $destinatario['city'] }}</div>
                @endif
                @if (isset($destinatario['country']) && $destinatario['country'])
                    <div class="address-detail">{{ $destinatario['country'] }}</div>
                @endif
                @if (isset($destinatario['phone']) && $destinatario['phone'])
                    <div class="address-detail">Tel: {{ $destinatario['phone'] }}</div>
                @endif
            </div>
        </div>

        {{-- Info carta --}}
        <div class="card-info">
            <div>
                <div style="font-size: 7px; color: #888; margin-bottom: 2px;">&#127183; CONTENUTO</div>
                <div class="card-name">{{ $offered_card_name ?? $transaction->card->name }}</div>
                <div class="card-detail">
                    @if (isset($offered_card_name))
                        Carta offerta in permuta
                    @else
                        {{ $transaction->card->set_name }} · #{{ $transaction->card->card_number }} ·
                        {{ $transaction->card->condition }} · {{ strtoupper($transaction->card->tcg_category) }}
                    @endif
                </div>
                <div style="text-align: center;">
                    <div class="badge"> CERTIFICATA TCG VAULT</div>
                    <div style="font-size: 7px; color: #888; margin-top: 2px;">Rarita: {{ $transaction->card->rarity }}
                    </div>
                </div>
            </div>

            {{-- Warning e istruzioni --}}
            <div class="warning">&#9888; <strong>FRAGILE</strong> — Inserire in toploader. Non piegare.</div>
            <div class="instructions">{{ $istruzioni }}</div>

            {{-- Footer --}}
            <div class="footer">TCG Vault &copy; {{ date('Y') }} — support@tcgvault.it — Transazione
                #{{ $transaction->id }}</div>

        </div>
</body>

</html>
