<x-app-layout>
    <x-slot name="header">
        Kelola Periode Tahun Ajaran
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6 text-gray-900 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Periode Tahun Ajaran</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola data periode tahun ajaran yang akan muncul di dropdown pilihan dan filter pada sistem.</p>
        </div>

        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <!-- Form Tambah Tahun Ajaran -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm md:col-span-1 sticky top-6">
                    <h4 class="font-bold text-gray-800 text-lg mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Tahun Ajaran
                    </h4>

                    <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="tahun_mulai" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Awal Mulai</label>
                                <input type="number" id="tahun_mulai" name="tahun_mulai" value="{{ old('tahun_mulai', date('Y')) }}" min="2000" max="2100" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2.5 bg-white" placeholder="Contoh: 2026" required oninput="document.getElementById('tahun_selesai').value = parseInt(this.value || 0) + 1;">
                                @error('tahun_mulai')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_selesai" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Selesai</label>
                                <input type="number" id="tahun_selesai" name="tahun_selesai" value="{{ old('tahun_selesai', date('Y') + 1) }}" min="2001" max="2101" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2.5 bg-white" placeholder="Contoh: 2027" required>
                                @error('tahun_selesai')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1.5">Akan digabung menjadi format: <strong>YYYY/YYYY</strong></p>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center transition-all shadow-sm">
                                    Simpan Tahun Ajaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel Daftar Tahun Ajaran -->
                <div class="md:col-span-2">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 w-16 text-center">No</th>
                                    <th scope="col" class="px-6 py-3.5">Periode Tahun Ajaran</th>
                                    <th scope="col" class="px-6 py-3.5">Tahun Mulai</th>
                                    <th scope="col" class="px-6 py-3.5">Tahun Selesai</th>
                                    <th scope="col" class="px-6 py-3.5 text-right w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tahunAjarans as $index => $ta)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $tahunAjarans->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 font-bold text-blue-600 text-base">{{ $ta->nama }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $ta->tahun_mulai }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $ta->tahun_selesai }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode {{ $ta->nama }} ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs py-1 px-2 rounded border border-red-200 hover:bg-red-50 transition-colors">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Belum ada data periode tahun ajaran yang disimpakan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tahunAjarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
