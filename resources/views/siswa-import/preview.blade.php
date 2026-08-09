<x-app-layout>
    <x-slot name="header">
        Preview & Validasi Data Import
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Preview Data Siswa</h3>
            <a href="{{ route('siswa-import.index') }}" class="text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-lg text-sm px-4 py-2 transition-colors">
                Kembali & Upload Ulang
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                <div class="text-sm font-medium text-gray-500 mb-1">Total Baris Dibaca</div>
                <div class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</div>
            </div>
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="text-sm font-medium text-green-600 mb-1">Data Valid</div>
                <div class="text-2xl font-bold text-green-700">{{ $summary['valid'] }}</div>
            </div>
            <div class="p-4 rounded-lg {{ $summary['error'] > 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="text-sm font-medium {{ $summary['error'] > 0 ? 'text-red-600' : 'text-gray-500' }} mb-1">Data Error / Invalid</div>
                <div class="text-2xl font-bold {{ $summary['error'] > 0 ? 'text-red-700' : 'text-gray-800' }}">{{ $summary['error'] }}</div>
            </div>
            <div class="p-4 rounded-lg {{ $summary['duplicate'] > 0 ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="text-sm font-medium {{ $summary['duplicate'] > 0 ? 'text-yellow-700' : 'text-gray-500' }} mb-1">Duplikat NISN</div>
                <div class="text-2xl font-bold {{ $summary['duplicate'] > 0 ? 'text-yellow-800' : 'text-gray-800' }}">{{ $summary['duplicate'] }}</div>
            </div>
        </div>

        @if(!$summary['can_import'])
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Import Tidak Dapat Dilakukan!</p>
                <p>Terdapat {{ $summary['error'] }} baris data yang bermasalah. Anda harus memperbaiki file Excel/CSV Anda terlebih dahulu dan mengunggah ulang agar tidak terjadi inkonsistensi data TOPSIS.</p>
            </div>
        @else
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded flex justify-between items-center" role="alert">
                <div>
                    <p class="font-bold">Seluruh Data Valid!</p>
                    <p>Semua baris data telah memenuhi syarat dan pemetaan kriteria berhasil. Anda dapat melanjutkan proses import.</p>
                </div>
                <form action="{{ route('siswa-import.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-bold rounded-lg text-sm px-6 py-3 shadow-md transition-colors" onclick="return confirm('Apakah Anda yakin ingin mengimport {{ $summary['valid'] }} data ini?');">
                        Import {{ $summary['valid'] }} Data ke Database
                    </button>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">Baris</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Nama / NISN</th>
                        <th scope="col" class="px-4 py-3">Kelas</th>
                        <th scope="col" class="px-4 py-3">Tahun Ajaran</th>
                        <th scope="col" class="px-4 py-3">Pesan Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr class="border-b {{ !$row['is_valid'] ? 'bg-red-50' : 'bg-white hover:bg-gray-50' }}">
                            <td class="px-4 py-3 text-center font-bold {{ !$row['is_valid'] ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $row['row_number'] }}
                            </td>
                            <td class="px-4 py-3">
                                @if($row['is_valid'])
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded">Valid</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded">Invalid</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $row['nama_siswa'] ?: '(Kosong)' }}</div>
                                <div class="text-xs text-gray-500">{{ $row['nisn'] ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $row['kelas'] }}</td>
                            <td class="px-4 py-3">{{ $row['tahun_ajaran'] }}</td>
                            <td class="px-4 py-3">
                                @if(count($row['errors']) > 0)
                                    <ul class="list-disc list-inside text-xs text-red-600">
                                        @foreach($row['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
