@extends('layouts.app')

@section('title', 'Registrazione Validatore — TCG Vault')

@section('content')
    <div class="max-w-2xl mx-auto px-3 md:px-6 py-8 md:py-14">

        {{-- Header --}}
        <div class="text-center mb-12 animate-in">
            <div class="w-16 h-16 border border-vault-gold flex items-center justify-center mx-auto mb-6">
                <svg class="w-7 h-7 text-vault-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <p class="font-mono text-vault-gold text-xs tracking-widest uppercase mb-3">Candidatura</p>
            <h1 class="font-display text-4xl font-light text-vault-text">
                Diventa <em class="text-vault-gold">Validatore</em> TCG
            </h1>
            <p class="text-vault-muted text-sm mt-3 max-w-md mx-auto leading-relaxed">
                I validatori TCG Vault sono esperti certificati che garantiscono
                l'autenticità delle carte nel marketplace. Ogni candidatura viene
                esaminata manualmente dal nostro team.
            </p>
        </div>

        <div class="gold-line mb-10"></div>

        {{-- Form --}}
        <form method="POST" action="{{ route('validator.register') }}" enctype="multipart/form-data" class="animate-in"
            style="animation-delay: 0.1s">

            @csrf
            {{-- CSRF: obbligatorio. Il form è su endpoint rate-limited (5 req/15min per IP) --}}
            @auth
                <div class="border border-green-800/50 p-5 mb-8 rounded-xl" style="background: rgba(6,78,59,0.15);">
                    <p class="text-green-400 font-bold text-sm mb-1">✅ Sei già registrato come {{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs">Completa solo le informazioni aggiuntive per diventare validatore. I tuoi
                        dati di accesso rimangono invariati.</p>
                </div>
            @endauth
            @if ($errors->any())
                <div class="border border-red-800/50 bg-red-950/30 p-5 mb-8">
                    <p class="font-mono text-xs text-red-400 uppercase tracking-widest mb-3">Correggi gli errori</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-300 flex items-center gap-2">
                                <span class="text-red-500">›</span> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── SEZIONE 1: Dati personali ── --}}
            @guest
                <div class="border border-vault-border bg-vault-card p-8 mb-6">
                    <p class="font-mono text-xs text-vault-gold uppercase tracking-widest mb-6">01 · Dati personali</p>

                    <div class="grid grid-cols-1 gap-5">

                        {{-- Nome --}}

                        <div>
                            <label for="name"
                                class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">
                                Nome completo *
                            </label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                placeholder="Mario Rossi"
                                class="w-full bg-vault-surface border @error('name') border-red-600 @else border-vault-border @enderror text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                            @error('name')
                                <p class="font-mono text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}

                        <div>
                            <label for="email"
                                class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">
                                Indirizzo email *
                            </label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                placeholder="mario@esempio.it"
                                class="w-full bg-vault-surface border @error('email') border-red-600 @else border-vault-border @enderror text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                            @error('email')
                                <p class="font-mono text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}

                        <div>
                            <label for="password"
                                class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">
                                Password * <span class="normal-case text-vault-muted/60">(min. 12 caratteri, maiusc., numeri,
                                    simboli)</span>
                            </label>
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="w-full bg-vault-surface border @error('password') border-red-600 @else border-vault-border @enderror text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                            @error('password')
                                <p class="font-mono text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Conferma Password --}}

                        <div>
                            <label for="password_confirmation"
                                class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">
                                Conferma password *
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                        </div>
                    </div>
                </div>
            @endguest
            {{-- ── SEZIONE 2: Documento d'identità ── --}}
            <div class="border border-vault-border bg-vault-card p-8 mb-6">
                <p class="font-mono text-xs text-vault-gold uppercase tracking-widest mb-2">02 · Documento d'identità</p>
                <p class="text-vault-muted text-sm mb-6 leading-relaxed">
                    Obbligatorio per la verifica dell'identità. Il documento sarà trattato
                    in conformità con il GDPR e non sarà mai condiviso con terzi.
                    Formati accettati: <strong class="text-vault-text">JPEG, PDF</strong> · Max <strong
                        class="text-vault-text">5MB</strong>
                </p>

                {{-- Upload area --}}
                <div id="upload-area"
                    class="border-2 border-dashed @error('identity_document') border-red-600 @else border-vault-border @enderror p-10 text-center cursor-pointer hover:border-vault-gold transition-colors"
                    onclick="document.getElementById('identity_document').click()">

                    <div id="upload-placeholder">
                        <svg class="w-10 h-10 text-vault-muted mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="font-body text-vault-muted text-sm">Clicca o trascina il documento qui</p>
                        <p class="font-mono text-xs text-vault-muted/60 mt-1">JPEG · PDF · Max 5MB</p>
                    </div>

                    <div id="upload-preview" class="hidden">
                        <svg class="w-10 h-10 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p id="file-name" class="font-mono text-sm text-emerald-400"></p>
                        <p class="font-mono text-xs text-vault-muted mt-1">Clicca per cambiare</p>
                    </div>
                </div>

                {{-- Input file nascosto --}}
                <input type="file" id="identity_document" name="identity_document" required accept=".jpg,.jpeg,.pdf"
                    class="hidden" onchange="handleFileSelect(this)">

                @error('identity_document')
                    <p class="font-mono text-xs text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── SEZIONE 3: Competenze TCG ── --}}
            <div class="border border-vault-border bg-vault-card p-8 mb-6">
                <p class="font-mono text-xs text-vault-gold uppercase tracking-widest mb-2">03 · Categorie TCG</p>
                <p class="text-vault-muted text-sm mb-6">
                    Seleziona le categorie in cui sei esperto. Riceverai solo transazioni relative alle categorie
                    selezionate.
                </p>

                @error('tcg_categories')
                    <p class="font-mono text-xs text-red-400 mb-3">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-2 gap-3">
                    @foreach ([
            'mtg' => 'Magic: The Gathering',
            'pokemon' => 'Pokémon TCG',
            'yugioh' => 'Yu-Gi-Oh!',
            'onepiece' => 'One Piece Card Game',
            'dragon_ball_super' => 'Dragon Ball Super CG',
            'naruto' => 'Naruto Card Game',
        ] as $value => $label)
                        <label
                            class="flex items-center gap-3 border border-vault-border p-3 cursor-pointer hover:border-vault-gold/50 transition-colors group has-[:checked]:border-vault-gold has-[:checked]:bg-yellow-950/20">
                            <input type="checkbox" name="tcg_categories[]" value="{{ $value }}"
                                {{ in_array($value, old('tcg_categories', [])) ? 'checked' : '' }}
                                class="accent-vault-gold">
                            <span class="font-body text-sm text-vault-text group-hover:text-vault-gold transition-colors">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>

                {{-- Note expertise --}}
                <div class="mt-5">
                    <label for="expertise_notes"
                        class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">
                        Note sulle tue competenze (opzionale)
                    </label>
                    <textarea id="expertise_notes" name="expertise_notes" rows="3" maxlength="2000"
                        placeholder="Es: Ho 10 anni di esperienza con MTG, specializzato in carte Alpha/Beta. Arbitro certificato Level 2..."
                        class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body resize-none">{{ old('expertise_notes') }}</textarea>
                </div>
            </div>

            {{-- Indirizzo del validatore --}}
            <div class="border border-vault-border bg-vault-card p-8 mb-6">
                <p class="font-mono text-xs text-vault-gold uppercase tracking-widest mb-2">04 · Il tuo indirizzo</p>
                <p class="text-vault-muted text-sm mb-6 leading-relaxed">
                    Le carte da validare verranno spedite al tuo indirizzo. Assicurati che sia corretto.
                </p>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">Indirizzo
                            *</label>
                        <input type="text" name="address" required value="{{ old('address') }}"
                            placeholder="Via Roma 1"
                            class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">Città
                                *</label>
                            <input type="text" name="city" required value="{{ old('city') }}"
                                placeholder="Milano"
                                class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                        </div>
                        <div>
                            <label class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">CAP
                                *</label>
                            <input type="text" name="zip" required value="{{ old('zip') }}"
                                placeholder="20100"
                                class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                        </div>
                    </div>
                    <div>
                        <label class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">Telefono
                            *</label>
                        <input type="text" name="phone" required value="{{ old('phone') }}"
                            placeholder="+39 333 1234567"
                            class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                    </div>
                </div>
                <div>
                    <label class="font-mono text-xs text-vault-muted uppercase tracking-widest block mb-2">Partita IVA
                        *</label>
                    <input type="text" name="vat_number" required value="{{ old('vat_number') }}"
                        placeholder="IT12345678901"
                        class="w-full bg-vault-surface border border-vault-border text-black px-4 py-3 text-sm focus:outline-none focus:border-vault-gold transition-colors font-body">
                    @error('vat_number')
                        <p class="font-mono text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── SEZIONE 4: Termini e condizioni ── --}}
            <div class="border border-vault-border bg-vault-card p-6 mb-8">
                <label class="flex items-start gap-4 cursor-pointer">
                    <input type="checkbox" name="accept_validator_terms" id="validator-terms" required
                        class="mt-1 accent-vault-gold flex-shrink-0">
                    <span class="text-sm text-vault-muted leading-relaxed">
                        Accetto i
                        <a href="#" class="text-vault-gold hover:underline">Termini e Condizioni per i
                            Validatori</a>
                        di TCG Vault, inclusi gli obblighi di riservatezza, i tempi massimi di validazione
                        e le conseguenze per validazioni errate o fraudolente.
                        Confermo che le informazioni fornite sono veritiere e complete.
                    </span>
                </label>
                @error('accept_validator_terms')
                    <p class="font-mono text-xs text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-4 text-base tracking-wide flex items-center justify-center gap-3 font-bold text-white rounded-lg"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Invia candidatura
            </button>

            <p class="text-center font-mono text-xs text-vault-muted mt-4 leading-relaxed">
                La candidatura sarà esaminata entro 48–72 ore lavorative.<br>
                Riceverai una notifica via email sull'esito.
            </p>
        </form>
    </div>
       <script>
        /**
         * Gestisce la selezione del file per il documento d'identità.
         * Mostra un'anteprima con il nome del file invece dell'input nascosto.
         */
        function handleFileSelect(input) {
            const file = input.files[0];
            if (!file) return;

            // Validazione lato client (la validazione reale è server-side)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('Il file supera il limite di 5MB. Riduci le dimensioni e riprova.');
                input.value = '';
                return;
            }

            const allowed = ['image/jpeg', 'application/pdf'];
            if (!allowed.includes(file.type)) {
                alert('Formato non supportato. Carica un file JPEG o PDF.');
                input.value = '';
                return;
            }

            // Aggiorna l'UI per mostrare il file selezionato
            document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('upload-preview').classList.remove('hidden');
            document.getElementById('file-name').textContent = file.name;
        }

        // Drag & drop support
        const uploadArea = document.getElementById('upload-area');
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-vault-gold');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-vault-gold');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-vault-gold');
            const input = document.getElementById('identity_document');
            input.files = e.dataTransfer.files;
            handleFileSelect(input);
        });

        function openFilePicker(e) {
    // Non aprire il file picker se si clicca sul pulsante di rimozione
    if (e.target.closest('button')) return;
    document.getElementById('images').click();
    }
    </script>

@endsection


 
