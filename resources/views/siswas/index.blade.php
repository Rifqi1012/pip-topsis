<x-app-layout>
    <x-slot name="header">
        Data Siswa
    </x-slot>

    <x-page-guide
        id="siswas_index"
        title="Cara Mengelola Data Siswa"
        :steps="Auth::user()->role === 'wali_kelas'
            ? ['Tambah siswa manual atau Import dari Excel', 'Pastikan kelas dan tahun ajaran sesuai', 'Lanjutkan ke menu Penilaian Siswa']
            : ['Gunakan filter untuk menyaring kelas atau periode', 'Klik Detail untuk memeriksa identitas dan nilai siswa']"
    >
        @if(Auth::user()->role === 'wali_kelas')
            Halaman ini berisi daftar siswa di kelas Anda. Pastikan status siswa adalah <strong>Siap Seleksi</strong> agar dapat diproses oleh Kepala Sekolah.
        @else
            Halaman ini menampilkan seluruh data siswa dari semua Wali Kelas sebagai bahan seleksi bantuan.
        @endif
    </x-page-guide>

    <div class="book-card overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-[#E4DDD0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1C2420]">Daftar Siswa Terdaftar</h2>
                <p class="text-xs font-medium text-[#4B5851] mt-0.5">Menampilkan {{ $siswas->total() }} data siswa.</p>
            </div>

            @if(Auth::user()->role === 'wali_kelas')
                <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
                    <a href="{{ route('siswa-import.index') }}" class="btn-secondary flex-1 sm:flex-none">
                        <svg class="w-4 h-4 text-[#1B4D3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Excel
                    </a>
                    <a href="{{ route('siswas.create') }}" class="btn-primary flex-1 sm:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Siswa
                    </a>
                </div>
            @endif
        </div>

        <!-- Filter & Search -->
        <div class="p-5 sm:p-6 bg-[#FAF7F2] border-b border-[#E4DDD0]">
            <form action="{{ route('siswas.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ Auth::user()->role === 'kepala_sekolah' ? '5' : '3' }} gap-3.5 items-end">
                <div>
                    <label for="filter-search" class="book-label text-xs">Cari Nama atau Kode</label>
                    <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="book-input text-sm" placeholder="Ketik nama atau SW-0001...">
                </div>

                @if(Auth::user()->role === 'kepala_sekolah')
                    <div>
                        <label for="filter-kelas" class="book-label text-xs">Filter Kelas</label>
                        <select id="filter-kelas" name="kelas" class="book-input text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kls)
                                <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="filter-periode" class="book-label text-xs">Tahun Ajaran</label>
                    <select id="filter-periode" name="periode" class="book-input text-sm">
                        <option value="">Semua Tahun Ajaran</option>
                        @php
                            $currentYear = date('Y');
                            $defaultPeriode = Auth::user()->role === 'wali_kelas' ? $currentYear . '/' . ($currentYear + 1) : null;
                            $selectedPeriode = request('periode', $defaultPeriode);
                        @endphp
                        @foreach($periodeList as $p)
                            <option value="{{ $p }}" {{ $selectedPeriode == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                @if(Auth::user()->role === 'kepala_sekolah')
                    <div>
                        <label for="filter-wali" class="book-label text-xs">Wali Kelas</label>
                        <select id="filter-wali" name="wali_kelas_id" class="book-input text-sm">
                            <option value="">Semua Wali Kelas</option>
                            @foreach($waliKelasList as $wk)
                                <option value="{{ $wk->id }}" {{ request('wali_kelas_id') == $wk->id ? 'selected' : '' }}>{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none">Terapkan</button>
                    <a href="{{ route('siswas.index') }}" class="btn-secondary flex-1 sm:flex-none">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="book-table">
                <thead>
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Siswa &amp; NISN</th>
                        <th scope="col">Kelas</th>
                        @if(Auth::user()->role === 'kepala_sekolah')
                            <th scope="col">Wali Kelas</th>
                        @endif
                        <th scope="col">Status Data</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $siswa)
                        <tr>
                            <td class="font-bold text-[#1B4D3E] whitespace-nowrap">{{ $siswa->kode_siswa }}</td>
                            <td>
                                <div class="font-bold text-[#1C2420]">{{ $siswa->nama_siswa }}</div>
                                <div class="text-xs text-[#4B5851] mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#F3EFE6] border border-[#D6CFC2] text-xs font-bold text-[#1C2420]">
                                    Kelas {{ $siswa->kelas }}
                                </span>
                            </td>
                            @if(Auth::user()->role === 'kepala_sekolah')
                                <td class="text-[#2E3A34] font-medium">{{ $siswa->user->name }}</td>
                            @endif
                            <td class="whitespace-nowrap">
                                @if($siswa->status_data === 'submitted')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">
                                        Siap Seleksi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FCD34D]">
                                        Belum Selesai (Draft)
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('siswas.show', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#1B4D3E] bg-[#E8F1EE] hover:bg-[#D5E7E1] border border-[#B8D4CA] min-h-[36px]">
                                        Detail
                                    </a>

                                    @if(Auth::user()->role === 'wali_kelas')
                                        <a href="{{ route('siswas.edit', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#92400E] bg-[#FEF3C7] hover:bg-[#FDE68A] border border-[#FCD34D] min-h-[36px]">
                                            Ubah
                                        </a>
                                        <form action="{{ route('siswas.destroy', $siswa->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data siswa {{ $siswa->nama_siswa }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-soft">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'kepala_sekolah' ? '6' : '5' }}" class="py-12 text-center">
                                <p class="font-bold text-[#1C2420] text-base">Belum ada data siswa yang cocok.</p>
                                <p class="text-sm text-[#4B5851] mt-1">
                                    @if(request()->hasAny(['search', 'kelas', 'periode', 'wali_kelas_id']))
                                        Coba ubah atau reset filter pencarian di atas.
                                    @elseif(Auth::user()->role === 'wali_kelas')
                                        Mulai dengan menambahkan siswa baru atau mengimpor dari Excel.
                                    @else
                                        Wali Kelas belum menginput data siswa.
                                    @endif
                                </p>
                                @if(Auth::user()->role === 'wali_kelas' && !request()->hasAny(['search', 'kelas', 'periode']))
                                    <div class="mt-4 flex justify-center gap-2.5">
                                        <a href="{{ route('siswas.create') }}" class="btn-primary text-xs">Tambah Siswa Pertama</a>
                                        <a href="{{ route('siswa-import.index') }}" class="btn-secondary text-xs">Import dari Excel</a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswas->hasPages())
            <div class="p-4 sm:px-6 border-t border-[#E4DDD0] bg-[#FAF7F2]">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
