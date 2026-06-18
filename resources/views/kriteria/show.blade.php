<x-app-layout>
    <x-slot name="header">
        Detail Kriteria
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 col-span-1">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Info Kriteria</h3>
                <ul class="space-y-4">
                    <li>
                        <span class="block text-sm text-gray-500">Kode</span>
                        <span class="font-semibold">{{ $kriteria->kode }}</span>
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Nama</span>
                        <span class="font-semibold">{{ $kriteria->nama_kriteria }}</span>
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Atribut</span>
                        @if($kriteria->atribut == 'Cost')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">Cost</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Benefit</span>
                        @endif
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Bobot</span>
                        <span class="font-semibold">{{ $kriteria->bobot }}</span>
                    </li>
                </ul>
                <div class="mt-6">
                    <a href="{{ route('kriteria.edit', $kriteria->id) }}" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Edit Kriteria</a>
                    <a href="{{ route('kriteria.index') }}" class="mt-2 block w-full text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Kembali</a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 col-span-1 md:col-span-2">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Sub Kriteria</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Sub Kriteria</th>
                                <th scope="col" class="px-6 py-3">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria->subKriterias as $sub)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $sub->nama_sub_kriteria }}</td>
                                    <td class="px-6 py-4 font-bold text-blue-600">{{ $sub->nilai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
