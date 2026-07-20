@props(['title' => '', 'description' => '', 'image' => '', 'price' => '', 'status' => '', 'link' => '#'])

<a href="{{ $link }}"
    class="group relative flex flex-col h-full bg-[#eff0f3] rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 overflow-hidden transform hover:-translate-y-1">

    @if ($image)
        <!-- Image Section -->
        <div class="relative w-full aspect-[4/3] overflow-hidden bg-slate-100">
            <img src="{{ $image }}" alt="{{ $title }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

            @if ($status)
                <div class="absolute top-4 left-4">
                    <x-badge :type="$status === 'Tersedia' ? 'success' : 'warning'">
                        {{ $status }}
                    </x-badge>
                </div>
            @endif

            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
        </div>
    @endif

    <!-- Content Section -->
    <div class="flex flex-col flex-grow p-5">
        <h3 class="text-lg font-bold text-slate-900 group-hover: transition-colors mb-2 line-clamp-1">
            {{ $title }}
        </h3>

        @if ($description)
            <p class="text-sm text-slate-500 mb-4 line-clamp-2">
                {{ $description }}
            </p>
        @endif

        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
            @if ($price)
                <div>
                    <span class="text-xs text-slate-400 font-medium">Mulai dari</span>
                    <div class="text-[#0d0d0d] font-bold">
                        {{ $price }}<span class="text-xs text-slate-400 font-normal">/hari</span>
                    </div>
                </div>
            @endif

            <div
                class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-[#ff8e3c] group-hover:bg-[#ff8e3c] group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </div>
    </div>
</a>
