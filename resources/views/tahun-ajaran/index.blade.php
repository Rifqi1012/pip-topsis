<x-app-layout>
    <x-slot name="header">
        Periode Tahun Ajaran
    </x-slot>

    <x-page-guide
        id="tahun_ajaran_index"
        title="Mengatur Periode Tahun Ajaran"
        :steps="['Masukkan tahun mulai (tahun selesai akan terisi otomatis)', 'Klik Simpan Tahun Ajaran', 'Periode baru langsung tersedia saat input siswa dan seleksi']"
    >
        Gunakan halaman ini di awal tahun ajaran baru agar data penerima bantuan tercatat rapi per periode.
    </x-page-guide>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Tambah -->
        <div class="lg:col-span-4 book-card p-6 lg:sticky lg:top-24">
            <h2 class="font-editorial text-lg font-bold text-[#1C2420] pb-3 mb-4 border-b border-[#E4DDD0]">
                Tambah Tahun Ajaran
            </h2>

            <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="tahun_mulai" class="book-label">Tahun Mulai</label>
                    <input type="number" id="tahun_mulai" name="tahun_mulai" value="{{ old('tahun_mulai', date('Y')) }}" min="2000" max="2100" class="book-input" placeholder="2026" required oninput="document.getElementById('tahun_selesai').value = parseInt(this.value || 0) + 1;">
                    @error('tahun_mulai') <p class="text-[#991B1B] text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tahun_selesai" class="book-label">Tahun Selesai</label>
                    <input type="number" id="tahun_selesai" name="tahun_selesai" value="{{ old('tahun_selesai', date('Y') + 1) }}" min="2001" max="2101" class="book-input" placeholder="2027" required>
                    @error('tahun_selesai') <p class="text-[#991B1B] text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    <p class="text-xs text-[#4B5851] mt-1.5">Otomatis disimpan dengan format <strong>YYYY/YYYY</strong>.</p>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Simpan Tahun Ajaran
                </button>
            </form>
        </div>

        <!-- Tabel -->
        <div class="lg:col-span-8 book-card overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">Daftar Periode Tersimpan</h2>
                <p class="text-xs text-[#4B5851] mt-0.5">Total {{ $tahunAjarans->total() }} periode.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="book-table">
                    <thead>
                        <tr>
                            <th scope="col" class="w-16 text-center">No</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Tahun Mulai</th>
                            <th scope="col">Tahun Selesai</th>
                            <th scope="col" class="text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tahunAjarans as $index => $ta)
                            <tr>
                                <td class="text-center font-bold text-[#4B5851]">{{ $tahunAjarans->firstItem() + $index }}</td>
                                <td class="font-bold text-[#1B4D3E] text-base">{{ $ta->nama }}</td>
                                <td class="font-semibold">{{ $ta->tahun_mulai }}</td>
                                <td class="font-semibold">{{ $ta->tahun_selesai }}</td>
                                <td class="text-right">
                                    <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus periode tahun ajaran {{ $ta->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-soft">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-[#4B5851]">
                                    Belum ada periode tahun ajaran. Tambahkan periode baru melalui formulir di samping.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tahunAjarans->hasPages())
                <div class="p-4 border-t border-[#E4DDD0] bg-[#FAF7F2]">
                    {{ $tahunAjarans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
