<x-app-layout>
    <x-slot name="header">
        Data Kelas
    </x-slot>

    <x-page-guide
        id="kelas_index"
        title="Tentang Data Master Kelas"
        :steps="['Tambahkan daftar kelas sekolah di kotak sebelah kiri', 'Daftar kelas otomatis muncul di dropdown Wali Kelas dan filter seleksi']"
    >
        Dengan data master ini, Wali Kelas cukup memilih kelas dari daftar sehingga tidak terjadi salah ketik nama kelas.
    </x-page-guide>

    <div
        x-data="{
            showEditModal: false,
            editAction: '',
            editNama: '',
            openEdit(item) {
                this.editAction = item.update_url;
                this.editNama = item.nama;
                this.showEditModal = true;
            }
        }"
        @keydown.escape.window="showEditModal = false"
        class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start"
    >
        <!-- Form Tambah Kelas -->
        <div class="lg:col-span-4 book-card p-6 lg:sticky lg:top-24">
            <h2 class="font-editorial text-lg font-bold text-[#1C2420] pb-3 mb-4 border-b border-[#E4DDD0]">
                Tambah Kelas Baru
            </h2>

            <form action="{{ route('kelas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="nama" class="book-label">Nama Kelas</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" maxlength="50" class="book-input uppercase" placeholder="Contoh: 1A, 2B, 6" required>
                    @error('nama')
                        <p class="text-[#991B1B] text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-[#4B5851] mt-1.5">Contoh format: <strong>1</strong>, <strong>2A</strong>, <strong>2B</strong>, <strong>6</strong>.</p>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Simpan Kelas
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kelas -->
        <div class="lg:col-span-8 book-card overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-[#E4DDD0]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">Daftar Kelas Aktif</h2>
                <p class="text-xs text-[#4B5851] mt-0.5">Total {{ $kelasList->total() }} kelas terdaftar.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="book-table">
                    <thead>
                        <tr>
                            <th scope="col" class="w-16 text-center">No</th>
                            <th scope="col">Nama Kelas</th>
                            <th scope="col" class="text-right w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelasList as $index => $kelas)
                            <tr>
                                <td class="text-center font-bold text-[#4B5851]">{{ $kelasList->firstItem() + $index }}</td>
                                <td class="font-bold text-[#1B4D3E] text-base">Kelas {{ $kelas->nama }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit({ nama: {{ json_encode($kelas->nama) }}, update_url: '{{ route('kelas.update', $kelas->id) }}' })"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#92400E] bg-[#FEF3C7] hover:bg-[#FDE68A] border border-[#FCD34D] min-h-[36px]"
                                        >
                                            Ubah
                                        </button>
                                        <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Kelas {{ $kelas->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-soft">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-10 text-center text-[#4B5851]">
                                    Belum ada data kelas. Tambahkan kelas pertama melalui formulir di samping.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kelasList->hasPages())
                <div class="p-4 border-t border-[#E4DDD0] bg-[#FAF7F2]">
                    {{ $kelasList->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Edit Kelas -->
        <div x-show="showEditModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-[#1C2420]/55 p-4" role="dialog" aria-modal="true" aria-labelledby="modal-edit-kelas-title">
            <div @click.away="showEditModal = false" class="book-card w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 bg-[#F3EFE6] border-b border-[#D6CFC2] flex justify-between items-center">
                    <h3 id="modal-edit-kelas-title" class="font-editorial text-lg font-bold text-[#1C2420]">Ubah Nama Kelas</h3>
                    <button type="button" @click="showEditModal = false" aria-label="Tutup dialog" class="w-9 h-9 rounded-lg hover:bg-[#E4DDD0] text-[#1C2420] font-bold text-lg">&times;</button>
                </div>

                <form :action="editAction" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit-nama-kelas" class="book-label">Nama Kelas</label>
                        <input id="edit-nama-kelas" type="text" name="nama" x-model="editNama" required maxlength="50" class="book-input uppercase">
                    </div>

                    <div class="pt-3 flex justify-end gap-2.5 border-t border-[#E4DDD0]">
                        <button type="button" @click="showEditModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
