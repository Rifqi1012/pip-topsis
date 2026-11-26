<x-app-layout>
    <x-slot name="header">
        Tambah Akun Baru
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('users.index') }}" class="btn-secondary text-xs">&larr; Kembali ke Kelola Akun</a>
        </div>

        <div class="book-card p-6 sm:p-8">
            <div class="pb-5 mb-6 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Formulir Akun Pengguna</h2>
                <p class="text-sm text-[#4B5851] mt-1">Buat akun login baru untuk Wali Kelas atau Kepala Sekolah.</p>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="book-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="book-input" placeholder="Nama lengkap guru" required autofocus>
                    @error('name') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="book-label">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="book-input" placeholder="nama@sekolah.com" required>
                    @error('email') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="book-label">Peran Pengguna</label>
                    <select id="role" name="role" class="book-input" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas (Input Siswa &amp; Penilaian)</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah (Kelola Master, Seleksi &amp; Akun)</option>
                    </select>
                    @error('role') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="book-label">Kata Sandi</label>
                        <input type="password" id="password" name="password" class="book-input" placeholder="Minimal 8 karakter" required>
                        @error('password') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="book-label">Ulangi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="book-input" placeholder="Ketik ulang kata sandi" required>
                    </div>
                </div>

                <div class="pt-5 border-t border-[#E4DDD0] flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Akun Baru</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
