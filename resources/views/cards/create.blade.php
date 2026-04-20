@extends('layouts.app')

@section('title', 'Vendi una carta — TCG Vault')

@section('content')
<div class="max-w-2xl mx-auto px-3 md:px-6">
    <h1 class="text-3xl font-bold text-yellow-400 mb-2">Vendi una carta</h1>
    <p class="text-gray-400 mb-8">Compila il form per pubblicare la tua carta nel marketplace.</p>

    @if($errors->any())
        <div class="bg-red-900 border border-red-700 text-red-300 px-4 py-3 text-sm mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-gray-400 text-sm mb-1">Nome carta *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Es. Charizard, Black Lotus..."
                   class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-400 text-sm mb-1">Set *</label>
                <input type="text" name="set_name" value="{{ old('set_name') }}" required
                       placeholder="Es. Base Set"
                       class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-1">Numero carta *</label>
                <input type="text" name="card_number" value="{{ old('card_number') }}" required
                       placeholder="Es. 025/165"
                       class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-400 text-sm mb-1">Categoria TCG *</label>
                <select name="tcg_category" required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
                    <option value="">Seleziona...</option>
                    <option value="pokemon"  {{ old('tcg_category') == 'pokemon'  ? 'selected' : '' }}>Pokémon</option>
                    <option value="mtg"      {{ old('tcg_category') == 'mtg'      ? 'selected' : '' }}>Magic: The Gathering</option>
                    <option value="yugioh"   {{ old('tcg_category') == 'yugioh'   ? 'selected' : '' }}>Yu-Gi-Oh!</option>
                    <option value="onepiece" {{ old('tcg_category') == 'onepiece' ? 'selected' : '' }}>One Piece</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-1">Rarità *</label>
                <input type="text" name="rarity" value="{{ old('rarity') }}" required
                       placeholder="Es. Rare, Mythic, Common..."
                       class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-400 text-sm mb-1">Condizione *</label>
                <select name="condition" required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
                    <option value="">Seleziona...</option>
                    <option value="NM" {{ old('condition') == 'NM' ? 'selected' : '' }}>NM - Near Mint</option>
                    <option value="LP" {{ old('condition') == 'LP' ? 'selected' : '' }}>LP - Lightly Played</option>
                    <option value="MP" {{ old('condition') == 'MP' ? 'selected' : '' }}>MP - Moderately Played</option>
                    <option value="HP" {{ old('condition') == 'HP' ? 'selected' : '' }}>HP - Heavily Played</option>
                    <option value="DMG"{{ old('condition') == 'DMG'? 'selected' : '' }}>DMG - Damaged</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-1">Prezzo (€)</label>
                <input type="number" name="price" value="{{ old('price') }}"
                       step="0.01" min="0.01" placeholder="Es. 29.99"
                       class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400">
                <p class="text-gray-500 text-xs mt-1">Lascia vuoto se solo permuta</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-4 bg-gray-900 border border-gray-700 rounded">
            <input type="checkbox" name="available_for_trade" id="trade" value="1"
                   {{ old('available_for_trade') ? 'checked' : '' }}
                   class="accent-yellow-400 w-4 h-4">
            <label for="trade" class="text-gray-300 text-sm cursor-pointer">
                Disponibile anche per permuta con altre carte
            </label>
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-1">Descrizione (opzionale)</label>
            <textarea name="description" rows="3"
                      placeholder="Aggiungi dettagli sulla carta..."
                      class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded focus:outline-none focus:border-yellow-400 resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
    
    <label class="block text-gray-400 text-sm mb-2">
        Foto della carta *
        <span class="text-gray-600">(min. 1, max. 5 — JPEG/PNG, max 3MB)</span>
    </label>

    {{-- Area drag & drop --}}
    <div id="drop-zone"
         class="border-2 border-dashed border-purple-800/50 rounded-xl p-8 text-center transition cursor-pointer hover:border-purple-500"
         style="background: rgba(45,17,84,0.2);"
         onclick="openFilePicker(event)"
         ondragover="handleDragOver(event)"
         ondragleave="handleDragLeave(event)"
         ondrop="handleDrop(event)">

        <div id="drop-placeholder">
            <svg class="w-12 h-12 text-purple-800 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <p class="text-gray-400 font-medium mb-1">Trascina le foto qui</p>
            <p class="text-gray-600 text-sm">oppure clicca per selezionare</p>
            <p class="text-gray-700 text-xs mt-2">JPEG, PNG · Max 3MB per foto · Max 5 foto</p>
        </div>

        {{-- Preview immagini --}}
        <div id="preview-grid" class="hidden grid grid-cols-3 gap-3 mt-2">
        </div>
    </div>

    {{-- Input nascosto --}}
    <input type="file"
           id="images"
           name="images[]"
           multiple
           accept=".jpg,.jpeg,.png"
           required
           class="hidden"
           onchange="handleFiles(this.files)">

    {{-- Contatore --}}
    <div class="flex justify-between mt-2">
        <span id="file-count" class="text-gray-600 text-xs">0 foto selezionate</span>
        <button type="button" id="clear-btn" onclick="clearFiles()"
                class="text-red-400 text-xs hover:text-red-300 hidden">
            ✕ Rimuovi tutte
        </button>
    </div>

    @error('images')
        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
    @error('images.*')
        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

        <div class="flex gap-4 pt-2">
            <button type="submit"
                    class="flex-1 bg-yellow-400 text-black font-bold py-3 rounded hover:bg-yellow-300 transition">
                Pubblica carta
            </button>
            <a href="{{ route('marketplace.index') }}"
               class="px-6 py-3 border border-gray-700 text-gray-400 hover:text-white rounded transition text-center">
                Annulla
            </a>
        </div>
    </form>
</div>

<script>
    let selectedFiles = [];

    // Gestisce il dragover
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('drop-zone').style.borderColor = '#a855f7';
        document.getElementById('drop-zone').style.background = 'rgba(124,58,237,0.15)';
    }

    // Gestisce il dragleave
    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('drop-zone').style.borderColor = '';
        document.getElementById('drop-zone').style.background = 'rgba(45,17,84,0.2)';
    }

    // Gestisce il drop
    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        handleDragLeave(e);
        const files = e.dataTransfer.files;
        handleFiles(files);
    }

    // Processa i file selezionati
    function handleFiles(files) {
        const maxFiles = 5;
        const maxSize  = 3 * 1024 * 1024; // 3MB
        const allowed  = ['image/jpeg', 'image/png'];

        for (let file of files) {
            if (selectedFiles.length >= maxFiles) {
                alert('Puoi caricare massimo 5 foto.');
                break;
            }
            if (!allowed.includes(file.type)) {
                alert(`${file.name}: formato non supportato. Usa JPEG o PNG.`);
                continue;
            }
            if (file.size > maxSize) {
                alert(`${file.name}: il file supera i 3MB.`);
                continue;
            }
            // Evita duplicati
            if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        }

        updateInput();
        updatePreview();
        updateCounter();
    }

    // Aggiorna l'input file con i file selezionati
    function updateInput() {
        const dt    = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        document.getElementById('images').files = dt.files;
    }

    // Aggiorna la preview
    function updatePreview() {
        const grid        = document.getElementById('preview-grid');
        const placeholder = document.getElementById('drop-placeholder');

        grid.innerHTML = '';

        if (selectedFiles.length === 0) {
            grid.classList.add('hidden');
            placeholder.classList.remove('hidden');
            return;
        }

        placeholder.classList.add('hidden');
        grid.classList.remove('hidden');

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}"
                         class="w-full aspect-[2/3] object-cover rounded-lg border border-purple-900/50">
                    <button type="button"
                            onclick="removeFile(${index})"
                            class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full text-xs
                                   opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        ✕
                    </button>
                    <p class="text-gray-600 text-xs mt-1 truncate">${file.name}</p>
                `;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    // Rimuove un singolo file
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateInput();
        updatePreview();
        updateCounter();
    }

    // Rimuove tutti i file
    function clearFiles() {
        selectedFiles = [];
        updateInput();
        updatePreview();
        updateCounter();
    }

    // Aggiorna il contatore
    function updateCounter() {
        const count   = selectedFiles.length;
        const counter = document.getElementById('file-count');
        const clearBtn = document.getElementById('clear-btn');

        counter.textContent = count === 0
            ? '0 foto selezionate'
            : `${count} foto selezionat${count === 1 ? 'a' : 'e'}`;

        clearBtn.classList.toggle('hidden', count === 0);
    }

    function openFilePicker(e) {
    // Non aprire il file picker se si clicca sul pulsante di rimozione
    if (e.target.closest('button')) return;
    document.getElementById('images').click();
    }
</script>

@endsection



