<x-app-layout>
    <x-slot name="header">
        Tambah Data Siswa
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('siswas.index') }}" class="btn-secondary text-xs">
                &larr; Kembali ke Daftar Siswa
            </a>
        </div>

        <div class="book-card p-6 sm:p-8">
            <div class="pb-5 mb-6 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Formulir Identitas Siswa</h2>
                <p class="text-sm text-[#4B5851] mt-1">Isi data identitas siswa dengan benar. Penilaian kriteria dapat diisi setelah data siswa disimpan.</p>
            </div>

            <form action="{{ route('siswas.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nisn" class="book-label">NISN</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" class="book-input" placeholder="Nomor Induk Siswa Nasional" required>
                        @error('nisn') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama_siswa" class="book-label">Nama Lengkap Siswa</label>
                        <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa') }}" class="book-input" placeholder="Nama lengkap sesuai rapor" required>
                        @error('nama_siswa') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="kelas" class="book-label">Kelas</label>
                        <select id="kelas" name="kelas" class="book-input" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kls)
                                <option value="{{ $kls }}" {{ old('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                        @error('kelas') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="periode" class="book-label">Periode Tahun Ajaran</label>
                        <select id="periode" name="periode" class="book-input" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @php
                                $currentYear = date('Y');
                                $selectedPeriode = old('periode', $currentYear . '/' . ($currentYear + 1));
                                $options = isset($tahunAjarans) && $tahunAjarans->isNotEmpty() ? $tahunAjarans : ['2024/2025', '2025/2026', '2026/2027', '2027/2028'];
                            @endphp
                            @foreach($options as $academicYear)
                                <option value="{{ $academicYear }}" {{ $selectedPeriode == $academicYear ? 'selected' : '' }}>{{ $academicYear }}</option>
                            @endforeach
                        </select>
                        @error('periode') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="book-label">Alamat Lengkap (Opsional)</label>
                    <textarea id="alamat" name="alamat" rows="3" class="book-input" placeholder="Alamat rumah siswa">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status_data" class="book-label">Status Data</label>
                    <select id="status_data" name="status_data" class="book-input" required>
                        <option value="submitted" {{ old('status_data', 'submitted') == 'submitted' ? 'selected' : '' }}>Siap Seleksi (Submitted) - Ikut perhitungan penerima bantuan</option>
                        <option value="draft" {{ old('status_data') == 'draft' ? 'selected' : '' }}>Belum Selesai (Draft) - Simpan sementara</option>
                    </select>
                    @error('status_data') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-5 border-t border-[#E4DDD0] flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('siswas.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Data Siswa</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
