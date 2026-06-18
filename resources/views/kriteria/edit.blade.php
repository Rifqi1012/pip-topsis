<x-app-layout>
    <x-slot name="header">
        Edit Kriteria
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 max-w-2xl mx-auto">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Edit {{ $kriteria->nama_kriteria }} ({{ $kriteria->kode }})</h3>
                <a href="{{ route('kriteria.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
            </div>

            <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nama Kriteria</label>
                    <input type="text" value="{{ $kriteria->nama_kriteria }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" disabled>
                    <p class="mt-1 text-xs text-gray-500">Nama kriteria tidak dapat diubah.</p>
                </div>

                <div class="mb-5">
                    <label for="atribut" class="block mb-2 text-sm font-medium text-gray-900">Atribut</label>
                    <select id="atribut" name="atribut" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="Cost" {{ (old('atribut', $kriteria->atribut) == 'Cost') ? 'selected' : '' }}>Cost</option>
                        <option value="Benefit" {{ (old('atribut', $kriteria->atribut) == 'Benefit') ? 'selected' : '' }}>Benefit</option>
                    </select>
                    @error('atribut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="bobot" class="block mb-2 text-sm font-medium text-gray-900">Bobot (1-5)</label>
                    <input type="number" id="bobot" name="bobot" value="{{ old('bobot', $kriteria->bobot) }}" min="1" max="5" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    @error('bobot')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</x-app-layout>
