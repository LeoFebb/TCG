@extends('layouts.app')

@section('title', 'Il mio profilo — TCG Vault')

@section('content')

    <div class="py-10 px-6 border-b border-purple-900/30" style="background: rgba(45, 17, 84, 0.2);">
        <div class="max-w-4xl mx-auto">
            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-1">Account</p>
            <h1 class="text-3xl font-black text-white">Il mio profilo</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-3 md:px-6 py-4 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Colonna sinistra: Avatar e statistiche --}}
            <div class="lg:col-span-1">

                {{-- Avatar --}}
                <div class="rounded-xl border border-purple-900/50 p-6 text-center mb-6"
                    style="background: rgba(45,17,84,0.3);">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-2 border-purple-700 flex items-center justify-center"
                        style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        @if ($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <span class="text-white text-3xl font-black">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-white font-black text-xl">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    @if ($user->city)
                        <p class="text-gray-600 text-xs mt-1">&#128205; {{ $user->city }}</p>
                    @endif
                    <p class="text-gray-600 text-xs mt-2">
                        Membro dal {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>

                {{-- Statistiche --}}
                <div class="rounded-xl border border-purple-900/50 p-6" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">Statistiche</p>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">&#128293; Carte pubblicate</span>
                            <span class="text-white font-bold">{{ $stats['carte'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">&#128230; Vendite completate</span>
                            <span class="text-white font-bold">{{ $stats['vendite'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">&#128722; Acquisti completati</span>
                            <span class="text-white font-bold">{{ $stats['acquisti'] }}</span>
                        </div>
                        <div class="border-t border-purple-900/50 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">&#128176; Guadagni totali</span>
                                <span
                                    class="text-green-400 font-black">&#8364;{{ number_format($stats['guadagni'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Debito spedizione --}}
@if(auth()->user()->has_shipping_debt)
    <div class="rounded-xl border border-red-800/50 p-5 mt-6" style="background: rgba(127,29,29,0.15);">
        <p class="text-red-400 font-bold text-sm mb-2">⚠️ Account sospeso</p>
        <p class="text-gray-400 text-xs mb-3">
            Hai un debito di spedizione di 
            <span class="text-red-400 font-bold">€{{ number_format(auth()->user()->shipping_debt_amount, 2) }}</span>
            da saldare per operare sul sito.
        </p>
        <a href="{{ route('shipping.debt.pay') }}" 
           class="block w-full py-2 rounded-xl font-bold text-white text-sm text-center"
           style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            Paga ora
        </a>
    </div>
@endif
                {{-- Link rapidi --}}
                <div class="mt-6 space-y-2">
                    <a href="{{ route('cards.my') }}"
                        class="block w-full py-3 px-4 rounded-xl text-sm text-gray-400 border border-purple-900/50 hover:text-white hover:border-purple-500 transition text-center">
                        &#128293; Le mie carte
                    </a>
                    <a href="{{ route('transactions.index') }}"
                        class="block w-full py-3 px-4 rounded-xl text-sm text-gray-400 border border-purple-900/50 hover:text-white hover:border-purple-500 transition text-center">
                        &#128230; Le mie transazioni
                    </a>
                    @if ($user->role !== 'validator')
                        <a href="{{ route('user.dashboard') }}"
                            class="block w-full py-3 px-4 rounded-xl text-sm text-gray-400 border border-purple-900/50 hover:text-white hover:border-purple-500 transition text-center">
                            &#128202; Dashboard
                        </a>
                    @endif
                </div>
            </div>

            {{-- Colonna destra: Form modifica --}}
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-purple-900/50 p-8" style="background: rgba(45,17,84,0.3);">
                    <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-6">Modifica profilo</p>

                    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf
                        

                        {{-- Foto profilo --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Foto profilo</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-purple-700 flex-shrink-0 flex items-center justify-center"
                                    id="photo-preview" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                    @if ($user->profile_photo)
                                        <img src="{{ Storage::url($user->profile_photo) }}" id="preview-img"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white font-black text-xl" id="preview-letter">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="border-2 border-dashed border-purple-800/50 rounded-xl p-3 text-center cursor-pointer hover:border-purple-500 transition"
                                        onclick="document.getElementById('profile_photo').click()">
                                        <p class="text-gray-400 text-xs">Clicca per caricare</p>
                                        <p class="text-gray-600 text-xs">JPEG, PNG - Max 2MB</p>
                                    </div>
                                    <input type="file" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png"
                                        class="hidden" onchange="previewPhoto(this)">
                                </div>
                            </div>
                        </div>

                        {{-- Nome --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Nome completo *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            @error('name')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            @error('email')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telefono --}}
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Telefono</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="+39 333 1234567"
                                class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                        </div>

                        {{-- Indirizzo --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Citta</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                    placeholder="Milano"
                                    class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                    style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">CAP</label>
                                <input type="text" name="zip" value="{{ old('zip', $user->zip) }}"
                                    placeholder="20100"
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

                        {{-- Cambio password --}}
                        <div class="border-t border-purple-900/50 pt-5">
                            <p class="text-purple-400 text-xs font-mono uppercase tracking-widest mb-4">Cambia password</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-400 text-sm mb-2">Nuova password</label>
                                    <input type="password" name="password" placeholder="Lascia vuoto per non cambiare"
                                        class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                    @error('password')
                                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-sm mb-2">Conferma nuova password</label>
                                    <input type="password" name="password_confirmation"
                                        placeholder="Ripeti la nuova password"
                                        class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none border transition"
                                        style="background: rgba(0,0,0,0.4); border-color: rgba(124,58,237,0.3);">
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-xl font-bold text-white transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                            Salva modifiche
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('photo-preview');
                    preview.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush

