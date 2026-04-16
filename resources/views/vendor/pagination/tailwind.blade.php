@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-8 gap-4">
        <p class="text-gray-500 text-sm">
            Mostrando {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} di {{ $paginator->total() }} risultati
        </p>
        <div class="flex items-center gap-2">
            {{-- Precedente --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl text-gray-600 border border-purple-900/30 cursor-not-allowed"
                      style="background: rgba(45,17,84,0.2);">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-4 py-2 rounded-xl text-white border border-purple-900/50 hover:border-purple-500 transition"
                   style="background: rgba(45,17,84,0.3);">
                    &laquo;
                </a>
            @endif

            {{-- Pagine --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 rounded-xl text-gray-600">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 rounded-xl text-white font-bold border border-purple-500"
                                  style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-4 py-2 rounded-xl text-gray-400 border border-purple-900/50 hover:border-purple-500 hover:text-white transition"
                               style="background: rgba(45,17,84,0.2);">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Successivo --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-4 py-2 rounded-xl text-white border border-purple-900/50 hover:border-purple-500 transition"
                   style="background: rgba(45,17,84,0.3);">
                    &raquo;
                </a>
            @else
                <span class="px-4 py-2 rounded-xl text-gray-600 border border-purple-900/30 cursor-not-allowed"
                      style="background: rgba(45,17,84,0.2);">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif