<x-app-layout>
    <x-slot name="header">
        Sub Kriteria
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-gray-800">Daftar Sub Kriteria</h3>
                
                <form action="{{ route('sub_kriteria.index') }}" method="GET" class="w-full md:w-1/3 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="search" name="search" value="{{ $search }}" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari sub kriteria atau kriteria...">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3">Kriteria (Kode)</th>
                            <th scope="col" class="px-6 py-3">Nama Sub Kriteria</th>
                            <th scope="col" class="px-6 py-3">Nilai</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subKriterias as $sub)
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $sub->kriteria->nama_kriteria }}</span>
                                    <span class="text-xs text-gray-500 block">({{ $sub->kriteria->kode }})</span>
                                </td>
                                <td class="px-6 py-4">{{ $sub->nama_sub_kriteria }}</td>
                                <td class="px-6 py-4 font-bold text-blue-600">{{ $sub->nilai }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('sub_kriteria.show', $sub->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('sub_kriteria.edit', $sub->id) }}" class="text-orange-500 hover:text-orange-700 font-medium">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $subKriterias->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
