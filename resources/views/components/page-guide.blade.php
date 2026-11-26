@props([
    'id' => 'default',
    'title' => 'Panduan Singkat',
    'steps' => [],
])

<div
    x-data="{
        open: localStorage.getItem('sibatu_guide_{{ $id }}') === 'open',
        toggle() {
            this.open = !this.open;
            localStorage.setItem('sibatu_guide_{{ $id }}', this.open ? 'open' : 'closed');
        }
    }"
    class="mb-6"
>
    <div x-show="open" x-cloak x-transition class="rounded-2xl border-2 border-[#E5C98B] bg-[#FFFBEB] p-4 sm:p-5 text-[#1C2420]">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#FEF3C7] border border-[#F59E0B] text-[#92400E] flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#78350F]">{{ $title }}</h2>
                    @if($slot->isNotEmpty())
                        <div class="mt-1 text-sm text-[#4B3B1F] leading-relaxed">
                            {{ $slot }}
                        </div>
                    @endif
                    @if(count($steps) > 0)
                        <ol class="mt-2.5 flex flex-wrap gap-2">
                            @foreach($steps as $i => $step)
                                <li class="inline-flex items-center gap-2 bg-white border border-[#E5C98B] rounded-lg px-3 py-1.5 text-xs font-semibold text-[#1C2420]">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#1B4D3E] text-white text-[11px] font-bold">{{ $i + 1 }}</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
            <button
                type="button"
                @click="toggle()"
                class="text-xs font-semibold text-[#78350F] hover:text-[#1C2420] bg-white/80 hover:bg-white border border-[#E5C98B] rounded-lg px-3 py-1.5 flex-shrink-0 min-h-[36px]"
                aria-label="Sembunyikan panduan"
            >
                Tutup Tips
            </button>
        </div>
    </div>

    <div x-show="!open" class="flex justify-end">
        <button
            type="button"
            @click="toggle()"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#78350F] bg-[#FFFBEB] hover:bg-[#FEF3C7] border border-[#E5C98B] rounded-lg px-3 py-1.5 min-h-[36px]"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tampilkan Panduan Halaman
        </button>
    </div>
</div>
