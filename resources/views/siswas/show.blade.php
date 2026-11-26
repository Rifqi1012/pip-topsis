<x-app-layout>
    <x-slot name="header">
        Detail Siswa
    </x-slot>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('siswas.index') }}" class="btn-secondary text-xs">
            &larr; Kembali
        </a>

        @can('update', $siswa)
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('siswas.edit', $siswa->id) }}" class="btn-secondary text-xs">Ubah Identitas</a>
                <a href="{{ route('penilaian-siswa.edit', $siswa->id) }}" class="btn-primary text-xs">Ubah Penilaian Kriteria</a>
            </div>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Data Identitas -->
        <div class="lg:col-span-5 book-card p-6">
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Identitas Siswa</h2>
                @if($siswa->status_data === 'submitted')
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">Siap Seleksi</span>
                @else
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FCD34D]">Draft</span>
                @endif
            </div>

            <dl class="space-y-4 text-sm">
                <div class="flex justify-between gap-4 border-b border-[#F3EFE6] pb-3">
                    <dt class="text-[#4B5851] font-medium">Kode Siswa</dt>
                    <dd class="font-bold text-[#1B4D3E]">{{ $siswa->kode_siswa }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-[#F3EFE6] pb-3">
                    <dt class="text-[#4B5851] font-medium">NISN</dt>
                    <dd class="font-bold text-[#1C2420]">{{ $siswa->nisn ?? '-' }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-[#F3EFE6] pb-3">
                    <dt class="text-[#4B5851] font-medium">Nama Lengkap</dt>
                    <dd class="font-bold text-[#1C2420] text-right">{{ $siswa->nama_siswa }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-[#F3EFE6] pb-3">
                    <dt class="text-[#4B5851] font-medium">Kelas / Periode</dt>
                    <dd class="font-bold text-[#1C2420]">Kelas {{ $siswa->kelas }} ({{ $siswa->periode ?? '-' }})</dd>
                </div>
                <div class="border-b border-[#F3EFE6] pb-3">
                    <dt class="text-[#4B5851] font-medium mb-1">Alamat Lengkap</dt>
                    <dd class="font-semibold text-[#1C2420]">{{ $siswa->alamat ?? '-' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-[#4B5851] font-medium">Wali Kelas</dt>
                    <dd class="font-bold text-[#1C2420]">{{ $siswa->user->name }}</dd>
                </div>
            </dl>
        </div>

        <!-- Data Penilaian -->
        <div class="lg:col-span-7 book-card p-6">
            <div class="pb-4 mb-5 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Hasil Penilaian Kriteria</h2>
                <p class="text-xs text-[#4B5851] mt-0.5">Nilai ini digunakan langsung dalam perhitungan seleksi TOPSIS.</p>
            </div>

            <ul class="space-y-3">
                @forelse($kriterias as $kriteria)
                    @php
                        $penilaian = $penilaians->get($kriteria->id);
                    @endphp
                    <li class="book-panel p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <span class="inline-block text-xs font-bold text-[#1B4D3E] bg-[#E8F1EE] border border-[#B8D4CA] px-2 py-0.5 rounded mr-1.5">{{ $kriteria->kode }}</span>
                            <span class="text-sm font-bold text-[#1C2420]">{{ $kriteria->nama_kriteria }}</span>
                            <div class="mt-1 text-sm text-[#4B5851]">
                                @if($penilaian && $penilaian->subKriteria)
                                    {{ $penilaian->subKriteria->nama_sub_kriteria }}
                                @else
                                    <span class="italic text-[#92400E]">Belum dinilai</span>
                                @endif
                            </div>
                        </div>

                        @if($penilaian && $penilaian->subKriteria)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-[#D6CFC2] text-xs font-bold text-[#1B4D3E] self-start sm:self-center whitespace-nowrap">
                                Nilai {{ $penilaian->subKriteria->nilai }} ({{ $penilaian->subKriteria->label_nilai }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#FEF3C7] border border-[#FCD34D] text-xs font-bold text-[#92400E] self-start sm:self-center">
                                Kosong
                            </span>
                        @endif
                    </li>
                @empty
                    <li class="text-sm text-[#4B5851]">Belum ada kriteria penilaian.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
