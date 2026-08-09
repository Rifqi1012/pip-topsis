<x-app-layout>
    <x-slot name="header">
        Hasil Proses Seleksi
    </x-slot>

    <div class="mb-6 flex justify-between items-center bg-blue-50 p-4 rounded-lg border border-blue-100">
        <div>
            <span class="text-sm text-blue-800 font-medium block">Informasi Kuota:</span>
            <span class="text-2xl font-bold text-blue-900">{{ $kuota }} Siswa</span>
        </div>
        <div class="text-right text-sm text-blue-700">
            <p>Siswa ranking 1 sampai {{ $kuota }} akan ditetapkan sebagai <strong>Direkomendasikan</strong>.</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6 text-gray-900 border-b border-gray-100">
            <!-- Header & Main Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Ranking Calon Penerima PIP</h3>
                    <p class="text-sm text-gray-500 mt-1">Data di bawah adalah hasil dari proses seleksi terbaru.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('topsis.transparansi', ['kelas' => $selectedKelas, 'periode' => $selectedPeriode]) }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Transparansi
                    </a>
                    
                    @if(Auth::user()->role === 'kepala_sekolah')
                    <form action="{{ route('topsis.calculate') }}" method="POST" class="inline" onsubmit="return confirm('Proses ini akan menghapus hasil perhitungan lama dan menghitung ulang data siswa HANYA untuk Kelas {{ $selectedKelas }} (Periode: {{ $selectedPeriode }}). Lanjutkan?');">
                        @csrf
                        <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                        <input type="hidden" name="periode" value="{{ $selectedPeriode }}">
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors flex items-center shadow-sm" {{ (!$selectedKelas && !$selectedPeriode) ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Proses
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Filter & Export Bar -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Form Filter -->
                <form action="{{ route('topsis.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto" id="form-filter">
                    <div class="w-full sm:w-auto flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span class="text-sm font-semibold text-gray-600 hidden sm:block mr-2">Filter:</span>
                    </div>
                    <div class="w-full sm:w-auto">
                        <select name="kelas" onchange="this.form.submit()" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm hover:border-gray-400 transition-colors">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ $selectedKelas == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-full sm:w-auto">
                        <select name="periode" onchange="this.form.submit()" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm hover:border-gray-400 transition-colors">
                            <option value="">-- Semua Periode Tahun Ajaran --</option>
                            @foreach($periodeList as $p)
                                <option value="{{ $p }}" {{ $selectedPeriode == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-full sm:w-auto">
                        <select name="status" onchange="this.form.submit()" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm hover:border-gray-400 transition-colors">
                            <option value="semua" {{ $selectedStatus == 'semua' ? 'selected' : '' }}>-- Semua Status --</option>
                            <option value="direkomendasikan" {{ $selectedStatus == 'direkomendasikan' ? 'selected' : '' }}>Direkomendasikan</option>
                            <option value="cadangan" {{ $selectedStatus == 'cadangan' ? 'selected' : '' }}>Cadangan</option>
                        </select>
                    </div>
                </form>

                <!-- Form Export -->
                <form action="" method="GET" class="flex w-full sm:w-auto" id="form-export">
                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                    <input type="hidden" name="periode" value="{{ $selectedPeriode }}">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    
                    <div class="inline-flex rounded-lg shadow-sm w-full sm:w-auto" role="group">
                        <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.pdf') }}'" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-700 bg-white border border-gray-300 rounded-l-lg hover:bg-red-50 hover:text-red-800 focus:z-10 focus:ring-2 focus:ring-red-300 transition-colors">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export PDF
                        </button>
                        <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.excel') }}'" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-green-700 bg-white border border-gray-300 rounded-r-lg hover:bg-green-50 hover:text-green-800 focus:z-10 focus:ring-2 focus:ring-green-300 transition-colors -ml-px">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="p-6">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($hasil->isEmpty())
                <div class="text-center py-10">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Hasil</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(Auth::user()->role === 'kepala_sekolah')
                            Pilih kelas dan periode, lalu klik tombol "Proses" untuk melakukan kalkulasi data siswa berstatus submitted di Kelas {{ $selectedKelas }} (Periode: {{ $selectedPeriode }}).
                        @else
                            Kepala Sekolah belum melakukan proses seleksi untuk kelas ini.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center w-20">Rank</th>
                                <th scope="col" class="px-6 py-3">Kode Siswa</th>
                                <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasil as $h)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors {{ $h->status_rekomendasi === 'Direkomendasikan' ? 'bg-green-50/30' : '' }}">
                                    <td class="px-6 py-4 text-center">{{ $h->ranking }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $h->siswa->kode_siswa ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $h->siswa->nama_siswa ?? 'Siswa Dihapus' }}</td>
                                    <td class="px-6 py-4">{{ $h->siswa->kelas ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($h->status_rekomendasi === 'Direkomendasikan')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Direkomendasikan</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">Cadangan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($h->siswa)
                                        <a href="{{ route('siswas.show', $h->siswa->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                        @else
                                        <span class="text-gray-400">Dihapus</span>
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
