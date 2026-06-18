<x-app-layout>
    <x-slot name="header">
        Detail Sub Kriteria
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 max-w-2xl mx-auto">
        <div class="p-6 text-gray-900">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Info Sub Kriteria</h3>
            <ul class="space-y-4">
                <li>
                    <span class="block text-sm text-gray-500">Kriteria Induk</span>
                    <span class="font-semibold">{{ $subKriteria->kriteria->nama_kriteria }} ({{ $subKriteria->kriteria->kode }})</span>
                </li>
                <li>
                    <span class="block text-sm text-gray-500">Nama Sub Kriteria</span>
                    <span class="font-semibold">{{ $subKriteria->nama_sub_kriteria }}</span>
                </li>
                <li>
                    <span class="block text-sm text-gray-500">Nilai</span>
                    <span class="font-semibold text-blue-600">{{ $subKriteria->nilai }}</span>
                </li>
            </ul>
            <div class="mt-6">
                <a href="{{ route('sub_kriteria.edit', $subKriteria->id) }}" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Edit Sub Kriteria</a>
                <a href="{{ route('sub_kriteria.index') }}" class="mt-2 block w-full text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
