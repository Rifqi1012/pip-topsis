<x-app-layout>
    <x-slot name="header">
        Isi Penilaian Siswa
    </x-slot>

    @php
        $initialFilled = [];
        foreach ($kriterias as $k) {
            $val = old("penilaian.{$k->id}", $existingPenilaian[$k->id] ?? null);
            if ($val) {
                $initialFilled[$k->id] = (string) $val;
            }
        }
    @endphp

    <div
        x-data="{
            total: {{ $kriterias->count() }},
            selected: {{ json_encode($initialFilled, JSON_FORCE_OBJECT) }},
            get filledCount() {
                return Object.keys(this.selected).length;
            },
            get percent() {
                return this.total > 0 ? Math.round((this.filledCount / this.total) * 100) : 0;
            },
            pick(kriteriaId, subId) {
                this.selected = { ...this.selected, [kriteriaId]: String(subId) };
            }
        }"
        class="max-w-4xl mx-auto space-y-6"
    >
        <!-- Top Info + Live Progress Bar -->
        <div class="book-card p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#1B4D3E]">{{ $siswa->kode_siswa }} &bull; Kelas {{ $siswa->kelas }}</p>
                <h2 class="font-editorial text-2xl font-bold text-[#1C2420] mt-0.5">{{ $siswa->nama_siswa }}</h2>
            </div>

            <div class="sm:w-64">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <span class="text-[#4B5851]">Progres Pengisian</span>
                    <span class="text-[#1B4D3E]" x-text="filledCount + ' dari ' + total + ' kriteria'"></span>
                </div>
                <div class="w-full h-3 rounded-full bg-[#EAE4D8] overflow-hidden border border-[#D6CFC2]">
                    <div class="h-full bg-[#1B4D3E] transition-all duration-200" :style="'width: ' + percent + '%'"></div>
                </div>
            </div>
        </div>

        @if($kriterias->isEmpty())
            <div class="book-card p-6 bg-[#FFFBEB] border-[#E5C98B] text-[#78350F] text-sm font-medium">
                Belum ada data kriteria yang tersedia. Silakan hubungi Kepala Sekolah untuk menambahkan kriteria penilaian.
            </div>
        @else
            <form action="{{ route('penilaian-siswa.update', $siswa->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @foreach($kriterias as $idx => $kriteria)
                    @php
                        $selectedSubId = old("penilaian.{$kriteria->id}", $existingPenilaian[$kriteria->id] ?? null);
                    @endphp
                    <fieldset class="book-card p-5 sm:p-6">
                        <legend class="flex flex-wrap items-center gap-2 text-base font-bold text-[#1C2420] mb-4">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#1B4D3E] text-white text-xs font-bold">{{ $idx + 1 }}</span>
                            <span>{{ $kriteria->nama_kriteria }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-[#F3EFE6] border border-[#D6CFC2] text-[#4B5851]">{{ $kriteria->kode }}</span>
                        </legend>

                        @if($kriteria->subKriterias->isEmpty())
                            <p class="text-sm text-[#92400E] bg-[#FEF3C7] border border-[#FCD34D] rounded-xl p-3.5 font-medium">
                                Pilihan untuk kriteria {{ $kriteria->nama_kriteria }} belum diisi oleh Kepala Sekolah.
                            </p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($kriteria->subKriterias as $opt)
                                    <label class="relative block">
                                        <input
                                            type="radio"
                                            name="penilaian[{{ $kriteria->id }}]"
                                            value="{{ $opt->id }}"
                                            class="peer sr-only"
                                            {{ $selectedSubId == $opt->id ? 'checked' : '' }}
                                            @change="pick({{ $kriteria->id }}, {{ $opt->id }})"
                                            required
                                        >
                                        <div class="min-h-[64px] rounded-xl border-2 border-[#D6CFC2] bg-[#FAF7F2] hover:border-[#1B4D3E] hover:bg-white peer-checked:border-[#1B4D3E] peer-checked:bg-[#E8F1EE] peer-focus-visible:ring-2 peer-focus-visible:ring-[#D97706] p-4 transition-colors flex items-center justify-between gap-3">
                                            <div>
                                                <span class="block text-sm font-bold text-[#1C2420]">{{ $opt->nama_sub_kriteria }}</span>
                                                <span class="block text-xs font-semibold text-[#4B5851] mt-0.5">Nilai {{ $opt->nilai }} ({{ $opt->label_nilai }})</span>
                                            </div>
                                            <span class="w-6 h-6 rounded-full border-2 border-[#C9C0B1] bg-white peer-checked:border-[#1B4D3E] flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-[#1B4D3E] hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error("penilaian.{$kriteria->id}")
                            <p class="mt-2.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p>
                        @enderror
                    </fieldset>
                @endforeach

                <div class="book-card p-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sticky bottom-3 z-20">
                    <a href="{{ route('penilaian-siswa.index') }}" class="btn-secondary">
                        &larr; Kembali
                    </a>
                    <button type="submit" class="btn-primary px-7">
                        Simpan Penilaian Siswa
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
