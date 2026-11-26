<x-app-layout>
    <x-slot name="header">
        Proses Seleksi &amp; Perankingan
    </x-slot>

    <x-page-guide
        id="topsis_index"
        title="Cara Menjalankan Seleksi Penerima Bantuan"
        :steps="['Pilih Kelas dan Periode Tahun Ajaran yang ingin diseleksi', 'Klik tombol Hitung Seleksi untuk memproses ranking', 'Klik Export PDF atau Export Excel untuk mengunduh laporan resmi']"
    >
        Siswa peringkat <strong>1 sampai {{ $kuota }}</strong> otomatis ditetapkan sebagai <strong>Direkomendasikan</strong> sesuai kuota yang diatur di menu Pengaturan Kuota.
    </x-page-guide>

    <div class="space-y-6">
        <!-- Kuota Banner + Action Header -->
        <div class="book-card p-5 sm:p-6 bg-[#E8F1EE] border-[#B8D4CA] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="px-4 py-2.5 rounded-xl bg-[#1B4D3E] text-white text-center flex-shrink-0">
                    <span class="block text-[11px] font-bold uppercase text-[#FDE68A]">Kuota</span>
                    <span class="font-editorial text-3xl font-bold leading-none">{{ $kuota }}</span>
                </div>
                <div>
                    <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Ranking Calon Penerima Bantuan</h2>
                    <p class="text-sm text-[#2E574A] mt-0.5">
                        Menampilkan hasil untuk <strong>{{ $selectedKelas ? 'Kelas ' . $selectedKelas : 'Semua Kelas' }}</strong> &bull; Periode <strong>{{ $selectedPeriode ?: 'Semua Periode' }}</strong>.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('topsis.transparansi', ['kelas' => $selectedKelas, 'periode' => $selectedPeriode]) }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-[#1B4D3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Detail Hitungan
                </a>

                @if(Auth::user()->role === 'kepala_sekolah')
                    <form action="{{ route('topsis.calculate') }}" method="POST" class="inline" onsubmit="return confirm('Hitung ulang ranking untuk Kelas {{ $selectedKelas }} (Periode: {{ $selectedPeriode }})? Hasil lama pada kelas & periode ini akan diperbarui.');">
                        @csrf
                        <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                        <input type="hidden" name="periode" value="{{ $selectedPeriode }}">
                        <button type="submit" class="btn-accent" {{ (!$selectedKelas && !$selectedPeriode) ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Hitung Seleksi Sekarang
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Filter & Export + Table -->
        <div class="book-card overflow-hidden">
            <div class="p-5 sm:p-6 bg-[#FAF7F2] border-b border-[#E4DDD0] flex flex-col xl:flex-row justify-between items-stretch xl:items-end gap-4">
                <form action="{{ route('topsis.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                    <div>
                        <label for="topsis-kelas" class="book-label text-xs">Pilih Kelas</label>
                        <select id="topsis-kelas" name="kelas" onchange="this.form.submit()" class="book-input text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ $selectedKelas == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="topsis-periode" class="book-label text-xs">Periode Tahun Ajaran</label>
                        <select id="topsis-periode" name="periode" onchange="this.form.submit()" class="book-input text-sm">
                            <option value="">Semua Periode</option>
                            @foreach($periodeList as $p)
                                <option value="{{ $p }}" {{ $selectedPeriode == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="topsis-status" class="book-label text-xs">Status Rekomendasi</label>
                        <select id="topsis-status" name="status" onchange="this.form.submit()" class="book-input text-sm">
                            <option value="semua" {{ $selectedStatus == 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="direkomendasikan" {{ $selectedStatus == 'direkomendasikan' ? 'selected' : '' }}>Direkomendasikan</option>
                            <option value="cadangan" {{ $selectedStatus == 'cadangan' ? 'selected' : '' }}>Cadangan</option>
                        </select>
                    </div>
                </form>

                <form action="" method="GET" id="form-export" class="flex flex-wrap gap-2.5 flex-shrink-0">
                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                    <input type="hidden" name="periode" value="{{ $selectedPeriode }}">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">

                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.pdf') }}'" class="btn-secondary flex-1 sm:flex-none text-[#991B1B] border-[#FCA5A5] hover:bg-[#FEF2F2]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Unduh PDF
                    </button>
                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.excel') }}'" class="btn-secondary flex-1 sm:flex-none text-[#166534] border-[#86EFAC] hover:bg-[#DCFCE7]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Excel
                    </button>
                </form>
            </div>

            @if($hasil->isEmpty())
                <div class="p-12 text-center">
                    <p class="font-editorial text-xl font-bold text-[#1C2420]">Belum Ada Hasil Perankingan</p>
                    <p class="text-sm text-[#4B5851] mt-2 max-w-lg mx-auto leading-relaxed">
                        Pilih <strong>Kelas</strong> dan <strong>Periode Tahun Ajaran</strong> di atas, lalu tekan tombol <strong>Hitung Seleksi Sekarang</strong> untuk memproses siswa berstatus Siap Seleksi.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="book-table">
                        <thead>
                            <tr>
                                <th scope="col" class="w-24 text-center">Peringkat</th>
                                <th scope="col">Kode Siswa</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">Kelas</th>
                                <th scope="col" class="text-center">Status Rekomendasi</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasil as $h)
                                <tr class="{{ $h->status_rekomendasi === 'Direkomendasikan' ? 'bg-[#F4FBF6]' : '' }}">
                                    <td class="text-center">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl font-editorial font-bold text-base {{ $h->ranking <= 3 ? 'bg-[#1B4D3E] text-white' : 'bg-[#F3EFE6] text-[#1C2420] border border-[#D6CFC2]' }}">
                                            {{ $h->ranking }}
                                        </span>
                                    </td>
                                    <td class="font-bold text-[#1B4D3E] whitespace-nowrap">{{ $h->siswa->kode_siswa ?? '-' }}</td>
                                    <td class="font-bold text-[#1C2420]">{{ $h->siswa->nama_siswa ?? 'Siswa Dihapus' }}</td>
                                    <td class="whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-lg bg-[#F3EFE6] border border-[#D6CFC2] text-xs font-bold text-[#1C2420]">
                                            Kelas {{ $h->siswa->kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        @if($h->status_rekomendasi === 'Direkomendasikan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">
                                                Direkomendasikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-[#F3EFE6] text-[#4B5851] border border-[#D6CFC2]">
                                                Cadangan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        @if($h->siswa)
                                            <a href="{{ route('siswas.show', $h->siswa->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#1B4D3E] bg-[#E8F1EE] hover:bg-[#D5E7E1] border border-[#B8D4CA] min-h-[36px]">
                                                Lihat Detail
                                            </a>
                                        @else
                                            <span class="text-xs text-[#4B5851]">Dihapus</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
