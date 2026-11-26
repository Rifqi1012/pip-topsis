<x-app-layout>
    <x-slot name="header">
        Penilaian Siswa
    </x-slot>

    <x-page-guide
        id="penilaian_index"
        title="Cara Mengisi Penilaian Siswa"
        :steps="['Pilih siswa yang masih berstatus Belum Dinilai atau Sebagian', 'Klik tombol Isi Nilai di sebelah kanan', 'Pilih kondisi sesuai fakta keluarga siswa lalu klik Simpan']"
    >
        Setiap siswa perlu dinilai pada seluruh kriteria aktif (<strong>{{ $totalKriteria }} kriteria</strong>) agar dapat masuk ke proses seleksi bantuan.
    </x-page-guide>

    <div class="book-card overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-5 sm:p-6 bg-[#FAF7F2] border-b border-[#E4DDD0]">
            <form action="{{ route('penilaian-siswa.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ Auth::user()->role === 'kepala_sekolah' ? '5' : '3' }} gap-3.5 items-end">
                <div>
                    <label for="penilaian-search" class="book-label text-xs">Cari Nama, Kode, atau NISN</label>
                    <input id="penilaian-search" type="text" name="search" value="{{ request('search') }}" class="book-input text-sm" placeholder="Ketik pencarian...">
                </div>

                @if(Auth::user()->role === 'kepala_sekolah')
                    <div>
                        <label for="penilaian-kelas" class="book-label text-xs">Filter Kelas</label>
                        <select id="penilaian-kelas" name="kelas" class="book-input text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kls)
                                <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="penilaian-periode" class="book-label text-xs">Tahun Ajaran</label>
                    <select id="penilaian-periode" name="periode" class="book-input text-sm">
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
                        <label for="penilaian-wali" class="book-label text-xs">Wali Kelas</label>
                        <select id="penilaian-wali" name="wali_kelas_id" class="book-input text-sm">
                            <option value="">Semua Wali Kelas</option>
                            @foreach(\App\Models\User::where('role', 'wali_kelas')->get() as $wk)
                                <option value="{{ $wk->id }}" {{ request('wali_kelas_id') == $wk->id ? 'selected' : '' }}>{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none">Terapkan</button>
                    <a href="{{ route('penilaian-siswa.index') }}" class="btn-secondary flex-1 sm:flex-none">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="book-table">
                <thead>
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Siswa</th>
                        <th scope="col">Kelas</th>
                        @if(Auth::user()->role === 'kepala_sekolah')
                            <th scope="col">Wali Kelas</th>
                        @endif
                        <th scope="col">Kelengkapan Nilai</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $siswa)
                        @php
                            $terisi = $siswa->penilaians->whereIn('kriteria_id', $kriteriaIds)->count();
                            $isLengkap = $totalKriteria > 0 && $terisi >= $totalKriteria;
                        @endphp
                        <tr>
                            <td class="font-bold text-[#1B4D3E] whitespace-nowrap">{{ $siswa->kode_siswa }}</td>
                            <td>
                                <div class="font-bold text-[#1C2420]">{{ $siswa->nama_siswa }}</div>
                                <div class="text-xs text-[#4B5851]">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-[#F3EFE6] border border-[#D6CFC2] text-xs font-bold text-[#1C2420]">Kelas {{ $siswa->kelas }}</span>
                            </td>
                            @if(Auth::user()->role === 'kepala_sekolah')
                                <td class="font-medium">{{ $siswa->user->name }}</td>
                            @endif
                            <td class="whitespace-nowrap">
                                @if($isLengkap)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">
                                        Lengkap ({{ $terisi }}/{{ $totalKriteria }})
                                    </span>
                                @elseif($terisi > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FCD34D]">
                                        Sebagian ({{ $terisi }}/{{ $totalKriteria }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]">
                                        Belum Dinilai (0/{{ $totalKriteria }})
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                @can('update', $siswa)
                                    <a href="{{ route('penilaian-siswa.edit', $siswa->id) }}" class="{{ $isLengkap ? 'btn-secondary text-xs py-2 px-3.5' : 'btn-primary text-xs py-2 px-3.5' }}">
                                        {{ $isLengkap ? 'Ubah Nilai' : 'Isi Nilai' }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'kepala_sekolah' ? '6' : '5' }}" class="py-12 text-center">
                                <p class="font-bold text-[#1C2420]">Belum ada data siswa untuk dinilai.</p>
                                <p class="text-sm text-[#4B5851] mt-1">Tambahkan data siswa terlebih dahulu di menu Data Siswa.</p>
                                <div class="mt-4">
                                    <a href="{{ route('siswas.index') }}" class="btn-primary text-xs">Buka Data Siswa</a>
                                </div>
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
