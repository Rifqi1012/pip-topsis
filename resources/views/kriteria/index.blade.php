<x-app-layout>
    <x-slot name="header">
        Master Kriteria
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Daftar Kriteria</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3">Kode</th>
                            <th scope="col" class="px-6 py-3">Nama Kriteria</th>
                            <th scope="col" class="px-6 py-3">Atribut</th>
                            <th scope="col" class="px-6 py-3">Bobot</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kriterias as $kriteria)
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $kriteria->kode }}</td>
                                <td class="px-6 py-4">{{ $kriteria->nama_kriteria }}</td>
                                <td class="px-6 py-4">
                                    @if($kriteria->atribut == 'Cost')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">Cost</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Benefit</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $kriteria->bobot }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('kriteria.show', $kriteria->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('kriteria.edit', $kriteria->id) }}" class="text-orange-500 hover:text-orange-700 font-medium">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
