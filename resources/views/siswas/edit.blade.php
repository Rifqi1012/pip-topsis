<x-app-layout>
    <x-slot name="header">
        Ubah Data Siswa
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('siswas.index') }}" class="btn-secondary text-xs">
                &larr; Kembali ke Daftar Siswa
            </a>
        </div>

        <div class="book-card p-6 sm:p-8">
            <div class="pb-5 mb-6 border-b border-[#E4DDD0] flex items-center justify-between gap-3">
                <div>
                    <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Edit Identitas Siswa</h2>
                    <p class="text-sm text-[#4B5851] mt-1">Kode Siswa: <strong class="text-[#1B4D3E]">{{ $siswa->kode_siswa }}</strong></p>
                </div>
            </div>

            <form action="{{ route('siswas.update', $siswa->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nisn" class="book-label">NISN</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="book-input" required>
                        @error('nisn') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama_siswa" class="book-label">Nama Lengkap Siswa</label>
                        <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" class="book-input" required>
                        @error('nama_siswa') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="kelas" class="book-label">Kelas</label>
                        @php
                            $selectedKelas = old('kelas', $siswa->kelas);
                            $kelasOptions = $kelasList->toArray();
                            if ($selectedKelas && !in_array($selectedKelas, $kelasOptions)) {
                                array_unshift($kelasOptions, $selectedKelas);
                            }
                        @endphp
                        <select id="kelas" name="kelas" class="book-input" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasOptions as $kls)
                                <option value="{{ $kls }}" {{ $selectedKelas == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                        @error('kelas') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="periode" class="book-label">Periode Tahun Ajaran</label>
                        <select id="periode" name="periode" class="book-input" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @php
                                $selectedPeriode = old('periode', $siswa->periode);
                                $options = isset($tahunAjarans) && $tahunAjarans->isNotEmpty() ? $tahunAjarans : collect(['2024/2025', '2025/2026', '2026/2027', '2027/2028']);
                                if ($selectedPeriode && !$options->contains($selectedPeriode)) {
                                    $options = $options->prepend($selectedPeriode);
                                }
                            @endphp
                            @foreach($options as $academicYear)
                                <option value="{{ $academicYear }}" {{ $selectedPeriode == $academicYear ? 'selected' : '' }}>{{ $academicYear }}</option>
                            @endforeach
                        </select>
                        @error('periode') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="book-label">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" class="book-input">{{ old('alamat', $siswa->alamat) }}</textarea>
                    @error('alamat') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status_data" class="book-label">Status Data</label>
                    <select id="status_data" name="status_data" class="book-input" required>
                        <option value="submitted" {{ old('status_data', $siswa->status_data) == 'submitted' ? 'selected' : '' }}>Siap Seleksi (Submitted)</option>
                        <option value="draft" {{ old('status_data', $siswa->status_data) == 'draft' ? 'selected' : '' }}>Belum Selesai (Draft)</option>
                    </select>
                    @error('status_data') <p class="mt-1.5 text-xs text-[#991B1B] font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-5 border-t border-[#E4DDD0] flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('siswas.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
