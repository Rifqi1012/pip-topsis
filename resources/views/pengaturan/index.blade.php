<x-app-layout>
    <x-slot name="header">
        Pengaturan Sistem
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 max-w-4xl mx-auto">
        <div class="p-6 text-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Konfigurasi Kuota Penerima PIP</h3>
                <p class="text-sm text-gray-500 mt-1">Atur jumlah siswa yang akan direkomendasikan untuk menerima bantuan PIP.</p>
            </div>
        </div>

        <div class="p-6 pt-0">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('pengaturan.update') }}" method="POST" class="max-w-md">
                @csrf
                
                <div class="mb-6">
                    <label for="kuota_penerima" class="block mb-2 text-sm font-medium text-gray-900">Kuota Penerima (Siswa)</label>
                    <input type="number" id="kuota_penerima" name="kuota_penerima" value="{{ old('kuota_penerima', $kuota) }}" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <p class="mt-2 text-xs text-gray-500">Contoh: 20. Jika diatur 20, maka siswa ranking 1-20 akan mendapat status "Direkomendasikan", sisanya "Cadangan".</p>
                    @error('kuota_penerima')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</x-app-layout>
