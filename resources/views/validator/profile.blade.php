@extends('layouts.app')

@section('title', 'Il mio profilo — TCG Vault')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-3xl mx-auto">
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Validatore</p>
            <h1 class="text-3xl font-black text-white">Il mio profilo</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-3 md:px-6 py-6 md:py-10">

        <form method="POST" action="{{ route('validator.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Foto profilo --}}
            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">📸 Foto profilo</p>

                <div class="flex flex-col sm:flex-row items-center gap-4 md:gap-6">
                    {{-- Avatar attuale --}}
                    <div id="avatar-preview"
                        class="w-24 h-24 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden border-2 border-purple-700"
                        style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        @if ($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}"
                                class="w-full h-full object-cover" id="avatar-img">
                        @else
                            <span class="text-white text-3xl font-black" id="avatar-letter">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-1">
                        <div class="border-2 border-dashed border-purple-800/50 rounded-xl p-4 text-center cursor-pointer hover:border-purple-500 transition"
                            onclick="document.getElementById('profile_photo').click()" ondragover="event.preventDefault()"
                            ondrop="handlePhotoDrop(event)">
                            <p class="text-gray-400 text-sm">Trascina o clicca per caricare</p>
                            <p class="text-gray-600 text-xs mt-1">JPEG, PNG · Max 2MB</p>
                        </div>
                        <input type="file" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png"
                            class="hidden" onchange="previewPhoto(this)">
                        @error('profile_photo')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Info personali --}}
            <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">👤 Informazioni</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Nome</label>
                        <input type="text" value="{{ $user->name }}" disabled
                            class="w-full px-4 py-3 rounded-xl text-gray-500 text-sm border"
                            style="background: rgba(0,0,0,0.2); border-color: rgba(124,58,237,0.2);">
                        <p class="text-gray-600 text-xs mt-1">Il nome non può essere modificato</p>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Telefono</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="+39 333 1234567"
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                            style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Città</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Milano"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">CAP</label>
                            <input type="text" name="zip" value="{{ old('zip', $user->zip) }}" placeholder="20100"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Indirizzo</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                            placeholder="Via Roma 1"
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                            style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Partita IVA</label>
                        @if ($user->vat_number)
                            <p class="w-full px-4 py-3 rounded-xl text-white text-sm border"
                                style="background: rgba(0,0,0,0.2); border-color: rgba(124,58,237,0.2);">
                                {{ $user->vat_number }}
                            </p>
                            <p class="text-gray-600 text-xs mt-1">&#128274; Per modificare la P.IVA contatta il supporto a <a href="mailto:support@tcgvault.it" class="text-purple-400 hover:underline">support@tcgvault.it</a></p>
                            </p>
                        @else
                            <input type="text" name="vat_number" value="{{ old('vat_number') }}"
                                placeholder="IT12345678901"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            <p class="text-gray-600 text-xs mt-1">Inserisci la tua Partita IVA per completare il profilo.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Note competenze</label>
                        <textarea name="validation_notes" rows="3" placeholder="Descrivi la tua esperienza TCG..."
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition resize-none"
                            style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">{{ old('validation_notes', $user->validation_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl font-bold text-white"
                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                💾 Salva modifiche
            </button>
        </form>
    </div>
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('avatar-preview');
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handlePhotoDrop(e) {
            e.preventDefault();
            const input = document.getElementById('profile_photo');
            input.files = e.dataTransfer.files;
            previewPhoto(input);
        }
    </script>
@endsection


