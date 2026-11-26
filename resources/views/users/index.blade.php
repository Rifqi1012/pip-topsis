<x-app-layout>
    <x-slot name="header">
        Kelola Akun Pengguna
    </x-slot>

    <x-page-guide
        id="users_index"
        title="Mengelola Akun Wali Kelas & Kepala Sekolah"
        :steps="['Klik Tambah Akun untuk membuat akun guru baru', 'Gunakan Nonaktifkan jika guru sudah tidak bertugas tanpa menghapus riwayat data']"
    >
        Anda dapat menambah akun baru, mengatur ulang kata sandi guru yang lupa, atau menonaktifkan akun kapan saja.
    </x-page-guide>

    <div class="book-card overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-[#E4DDD0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">Daftar Pengguna SIBATU</h2>
                <p class="text-xs text-[#4B5851] mt-0.5">Total {{ $users->total() }} akun terdaftar.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Akun Baru
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="p-5 sm:p-6 bg-[#FAF7F2] border-b border-[#E4DDD0]">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
                <div class="flex-1">
                    <label for="user-search" class="book-label text-xs">Cari Nama atau Email</label>
                    <input id="user-search" type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="book-input text-sm">
                </div>
                <div class="sm:w-56">
                    <label for="user-role" class="book-label text-xs">Filter Peran</label>
                    <select id="user-role" name="role" class="book-input text-sm" onchange="this.form.submit()">
                        <option value="">Semua Peran</option>
                        <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="wali_kelas" {{ request('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none">Cari</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary flex-1 sm:flex-none">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="book-table">
                <thead>
                    <tr>
                        <th scope="col">Nama Pengguna</th>
                        <th scope="col">Email</th>
                        <th scope="col">Peran</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="{{ !$user->is_active ? 'opacity-70 bg-[#FAF7F2]' : '' }}">
                            <td class="font-bold text-[#1C2420]">
                                {{ $user->name }}
                                @if(Auth::id() === $user->id)
                                    <span class="ml-2 px-2 py-0.5 rounded text-[11px] font-bold bg-[#E8F1EE] text-[#1B4D3E] border border-[#B8D4CA]">Anda</span>
                                @endif
                            </td>
                            <td class="text-[#4B5851] font-medium">{{ $user->email }}</td>
                            <td class="whitespace-nowrap">
                                @if($user->role === 'kepala_sekolah')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#E8F1EE] text-[#1B4D3E] border border-[#B8D4CA]">Kepala Sekolah</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FCD34D]">Wali Kelas</span>
                                @endif
                            </td>
                            <td class="text-center whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#92400E] bg-[#FEF3C7] hover:bg-[#FDE68A] border border-[#FCD34D] min-h-[36px]">
                                        Ubah
                                    </a>

                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->name }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $user->is_active ? 'btn-danger-soft' : 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#166534] bg-[#DCFCE7] hover:bg-[#BBF7D0] border border-[#86EFAC] min-h-[36px]' }}">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-[#4B5851]">
                                Tidak ada akun pengguna yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 sm:px-6 border-t border-[#E4DDD0] bg-[#FAF7F2]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
