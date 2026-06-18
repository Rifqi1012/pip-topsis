<x-app-layout>
    <x-slot name="header">
        Edit Sub Kriteria
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 max-w-2xl mx-auto">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Edit Sub Kriteria</h3>
                <a href="{{ route('sub_kriteria.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
            </div>

            <form action="{{ route('sub_kriteria.update', $subKriteria->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Kriteria</label>
                    <input type="text" value="{{ $subKriteria->kriteria->nama_kriteria }} ({{ $subKriteria->kriteria->kode }})" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" disabled>
                </div>

                <div class="mb-5">
                    <label for="nama_sub_kriteria" class="block mb-2 text-sm font-medium text-gray-900">Nama Sub Kriteria</label>
                    <input type="text" id="nama_sub_kriteria" name="nama_sub_kriteria" value="{{ old('nama_sub_kriteria', $subKriteria->nama_sub_kriteria) }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    @error('nama_sub_kriteria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="nilai" class="block mb-2 text-sm font-medium text-gray-900">Nilai (1-5)</label>
                    <input type="number" id="nilai" name="nilai" value="{{ old('nilai', $subKriteria->nilai) }}" min="1" max="5" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    @error('nilai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</x-app-layout>
