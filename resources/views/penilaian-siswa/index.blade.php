<x-app-layout>
    <x-slot name="header">
        Data Penilaian Siswa
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-gray-800">Daftar Data Penilaian Kriteria Siswa</h3>
            </div>

            <!-- Filter & Search -->
            <form action="{{ route('penilaian-siswa.index') }}" method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-{{ Auth::user()->role === 'tata_usaha' ? '5' : '3' }} gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari (Nama/Kode)</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik pencarian...">
                </div>
                @if(Auth::user()->role === 'tata_usaha')
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Filter Kelas</label>
                    <select name="kelas" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kls)
                            <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Periode Tahun Ajaran</label>
                    <select name="periode" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Periode Tahun Ajaran</option>
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
                @if(Auth::user()->role === 'tata_usaha')
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Filter Wali Kelas</label>
                    <select name="wali_kelas_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Wali Kelas</option>
                        @foreach(\App\Models\User::where('role', 'wali_kelas')->get() as $wk)
                            <option value="{{ $wk->id }}" {{ request('wali_kelas_id') == $wk->id ? 'selected' : '' }}>{{ $wk->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="flex items-end space-x-2">
                    <button type="submit" class="w-full md:w-auto text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors">Terapkan</button>
                    <a href="{{ route('penilaian-siswa.index') }}" class="w-full md:w-auto text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 transition-colors">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3">Kode Siswa</th>
                            <th scope="col" class="px-6 py-3">Nama Siswa</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            @if(Auth::user()->role === 'tata_usaha')
                            <th scope="col" class="px-6 py-3">Wali Kelas</th>
                            @endif
                            <th scope="col" class="px-6 py-3">Status Penilaian</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            @php
                                $isLengkap = !empty($siswa->c1_id) && !empty($siswa->c2_id) && !empty($siswa->c3_id) && !empty($siswa->c4_id) && !empty($siswa->c5_id) && !empty($siswa->c6_id);
                            @endphp
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $siswa->kode_siswa }}</td>
                                <td class="px-6 py-4">{{ $siswa->nama_siswa }}</td>
                                <td class="px-6 py-4">{{ $siswa->kelas }}</td>
                                @if(Auth::user()->role === 'tata_usaha')
                                <td class="px-6 py-4">{{ $siswa->user->name }}</td>
                                @endif
                                <td class="px-6 py-4">
                                    @if($isLengkap)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Lengkap</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @can('update', $siswa)
                                        <a href="{{ route('penilaian-siswa.edit', $siswa->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center transition-colors">
                                            {{ $isLengkap ? 'Edit Nilai' : 'Input Nilai' }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'tata_usaha' ? '6' : '5' }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data siswa ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $siswas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
