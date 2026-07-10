@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Info --}}
            <div>
                <p class="text-sm text-gray-600">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    of
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            {{-- Pagination --}}
            <div class="overflow-x-auto">
                <div class="inline-flex min-w-max rounded-lg shadow-sm">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="px-3 py-2 border rounded-l-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="px-3 py-2 border rounded-l-lg bg-white hover:bg-gray-100">
                            &laquo;
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        {{-- Dots --}}
                        @if (is_string($element))
                            <span class="px-4 py-2 border bg-white text-gray-500">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="px-4 py-2 border bg-indigo-600 text-white font-semibold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 border bg-white hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="px-3 py-2 border rounded-r-lg bg-white hover:bg-gray-100">
                            &raquo;
                        </a>
                    @else
                        <span class="px-3 py-2 border rounded-r-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            &raquo;
                        </span>
                    @endif

                </div>
            </div>

        </div>

    </nav>
@endif
