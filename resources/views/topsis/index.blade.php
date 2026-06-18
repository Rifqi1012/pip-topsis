<x-app-layout>
    <x-slot name="header">
        Hasil Perhitungan TOPSIS
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
        <div class="p-6 text-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Ranking Calon Penerima PIP</h3>
                <p class="text-sm text-gray-500 mt-1">Data di bawah adalah hasil dari perhitungan TOPSIS terbaru.</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <!-- Form Filter Export -->
                <form action="" method="GET" class="inline-flex items-center" id="form-export">
                    <select name="filter" id="filter" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none">
                        <option value="semua">Semua Data</option>
                        <option value="direkomendasikan">Hanya Direkomendasikan</option>
                        <option value="cadangan">Hanya Cadangan</option>
                    </select>
                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.pdf') }}'" class="text-red-700 bg-red-100 hover:bg-red-200 focus:ring-4 focus:ring-red-300 font-medium text-sm px-4 py-2.5 transition-colors border-y border-r border-red-200">
                        PDF
                    </button>
                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('laporan.excel') }}'" class="text-green-700 bg-green-100 hover:bg-green-200 focus:ring-4 focus:ring-green-300 font-medium rounded-r-lg text-sm px-4 py-2.5 transition-colors border-y border-r border-green-200 mr-2">
                        Excel
                    </button>
                </form>
                
                <a href="{{ route('topsis.transparansi') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                    Transparansi
                </a>
                
                @if(Auth::user()->role === 'tata_usaha')
                <form action="{{ route('topsis.calculate') }}" method="POST" class="inline" onsubmit="return confirm('Proses ini akan menghapus hasil perhitungan lama dan menghitung ulang seluruh data siswa yang berstatus submitted. Lanjutkan?');">
                    @csrf
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Hitung Ulang
                    </button>
                </form>
                @endif
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
                        @if(Auth::user()->role === 'tata_usaha')
                            Klik tombol "Hitung Ulang" untuk melakukan kalkulasi data siswa berstatus submitted.
                        @else
                            Tata Usaha belum melakukan perhitungan TOPSIS.
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
                                <th scope="col" class="px-6 py-3 text-right">Nilai Preferensi</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasil as $h)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors {{ $h->status_rekomendasi === 'Direkomendasikan' ? 'bg-green-50/30' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        @if($h->ranking == 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 font-bold border border-yellow-300">1</span>
                                        @elseif($h->ranking == 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-800 font-bold border border-gray-300">2</span>
                                        @elseif($h->ranking == 3)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-bold border border-orange-300">3</span>
                                        @else
                                            <span class="font-medium text-gray-600">{{ $h->ranking }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $h->siswa->kode_siswa }}</td>
                                    <td class="px-6 py-4">{{ $h->siswa->nama_siswa }}</td>
                                    <td class="px-6 py-4">{{ $h->siswa->kelas }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-blue-600">{{ number_format($h->nilai_preferensi, 4) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($h->status_rekomendasi === 'Direkomendasikan')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Direkomendasikan</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">Cadangan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('siswas.show', $h->siswa->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
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
