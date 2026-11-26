<x-app-layout>
    <x-slot name="header">
        Pengaturan Kuota
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-5">
        <x-page-guide
            id="pengaturan_index"
            title="Cara Kerja Kuota Penerima"
        >
            Angka kuota menentukan berapa banyak siswa peringkat teratas per kelas yang langsung diberi status <strong>Direkomendasikan</strong>. Siswa di luar kuota otomatis masuk daftar <strong>Cadangan</strong>.
        </x-page-guide>

        <div class="book-card p-6 sm:p-8">
            <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Jumlah Kuota Penerima Bantuan</h2>
            <p class="text-sm text-[#4B5851] mt-1">Atur batas jumlah penerima yang direkomendasikan pada hasil seleksi.</p>

            <form action="{{ route('pengaturan.update') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="kuota_penerima" class="book-label">Kuota Penerima (Jumlah Siswa)</label>
                    <input type="number" id="kuota_penerima" name="kuota_penerima" value="{{ old('kuota_penerima', $kuota) }}" min="1" class="book-input max-w-xs font-bold text-lg" required>
                    <p class="mt-2 text-xs text-[#4B5851]">Contoh: jika diisi <strong>20</strong>, maka peringkat 1 sampai 20 berstatus <strong>Direkomendasikan</strong>, dan peringkat 21 ke atas berstatus <strong>Cadangan</strong>.</p>
                    @error('kuota_penerima')
                        <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-[#E4DDD0]">
                    <button type="submit" class="btn-primary">Simpan Pengaturan Kuota</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
