<x-app-layout>
    <x-slot name="header">
        Data Siswa
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-gray-800">Daftar Data Siswa</h3>
                
                @if(Auth::user()->role === 'wali_kelas')
                <a href="{{ route('siswas.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data Siswa
                </a>
                @endif
            </div>

            @if(Auth::user()->role === 'tata_usaha')
            <!-- Filter & Search untuk TU -->
            <form action="{{ route('siswas.index') }}" method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari (Nama/Kode)</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik pencarian...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Filter Kelas</label>
                    <select name="kelas" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kls)
                            <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Filter Wali Kelas</label>
                    <select name="wali_kelas_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Wali Kelas</option>
                        @foreach($waliKelasList as $wk)
                            <option value="{{ $wk->id }}" {{ request('wali_kelas_id') == $wk->id ? 'selected' : '' }}>{{ $wk->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="w-full md:w-auto text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors">Terapkan</button>
                    <a href="{{ route('siswas.index') }}" class="w-full md:w-auto text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 transition-colors">Reset</a>
                </div>
            </form>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3">Kode Siswa</th>
                            <th scope="col" class="px-6 py-3">NISN</th>
                            <th scope="col" class="px-6 py-3">Nama Siswa</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            @if(Auth::user()->role === 'tata_usaha')
                            <th scope="col" class="px-6 py-3">Wali Kelas</th>
                            @endif
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $siswa->kode_siswa }}</td>
                                <td class="px-6 py-4">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $siswa->nama_siswa }}</td>
                                <td class="px-6 py-4">{{ $siswa->kelas }}</td>
                                @if(Auth::user()->role === 'tata_usaha')
                                <td class="px-6 py-4">{{ $siswa->user->name }}</td>
                                @endif
                                <td class="px-6 py-4">
                                    @if($siswa->status_data === 'submitted')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Submitted</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-3">
                                    <a href="{{ route('siswas.show', $siswa->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                    
                                    @can('update', $siswa)
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('siswas.edit', $siswa->id) }}" class="text-orange-500 hover:text-orange-700 font-medium">Edit</a>
                                    @endcan
                                    
                                    @can('delete', $siswa)
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('siswas.destroy', $siswa->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara soft delete?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium bg-transparent border-0 p-0 cursor-pointer">Hapus</button>
                                        </form>
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
