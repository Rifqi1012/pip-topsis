<x-app-layout>
    <x-slot name="header">
        Ubah Akun Pengguna
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('users.index') }}" class="btn-secondary text-xs">&larr; Kembali ke Kelola Akun</a>
        </div>

        <div class="book-card p-6 sm:p-8">
            <div class="pb-5 mb-6 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Edit Data: {{ $user->name }}</h2>
                <p class="text-sm text-[#4B5851] mt-1">Perbarui nama, email, peran, atau atur ulang kata sandi akun ini.</p>
            </div>

            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="book-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="book-input" required>
                    @error('name') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="book-label">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="book-input" required>
                    @error('email') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="book-label">Peran Pengguna</label>
                    <select id="role" name="role" class="book-input" required>
                        <option value="wali_kelas" {{ old('role', $user->role) == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                        <option value="kepala_sekolah" {{ old('role', $user->role) == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    </select>
                    @error('role') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="book-panel p-4 text-sm text-[#1C2420]">
                    <strong>Reset Kata Sandi (Opsional):</strong> Biarkan kedua kolom di bawah kosong jika tidak ingin mengganti kata sandi.
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="book-label">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="book-input" placeholder="Opsional (min. 8 karakter)">
                        @error('password') <p class="mt-1.5 text-xs text-[#991B1B] font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="book-label">Ulangi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="book-input" placeholder="Ulangi kata sandi baru">
                    </div>
                </div>

                <div class="pt-5 border-t border-[#E4DDD0] flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
