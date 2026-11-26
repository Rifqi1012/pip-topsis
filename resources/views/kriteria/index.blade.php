<x-app-layout>
    <x-slot name="header">
        Data Kriteria &amp; Sub-Kriteria
    </x-slot>

    <x-page-guide
        id="kriteria_index"
        title="Cara Mengatur Kriteria & Pilihan Nilai"
        :steps="['Klik baris kriteria untuk membuka daftar pilihan (sub-kriteria)', 'Gunakan form di bagian bawah baris untuk menambah pilihan baru', 'Nilai 5 berarti paling layak menerima bantuan, nilai 1 paling tidak layak']"
    >
        Seluruh kriteria dan pilihan di sini otomatis tampil di formulir <strong>Penilaian Siswa</strong> milik Wali Kelas dan langsung dipakai dalam perhitungan seleksi.
    </x-page-guide>

    <div
        x-data="{
            openId: {{ json_encode(session('open_kriteria', $kriterias->first()?->id)) }},
            showKriteriaModal: false,
            kriteriaMode: 'create',
            kriteriaAction: '{{ route('kriteria.store') }}',
            kriteriaForm: {
                kode: '{{ $nextKode }}',
                nama_kriteria: '',
                atribut: 'Benefit',
                bobot: 5,
                urutan: {{ $nextUrutan }},
            },
            showSubModal: false,
            subAction: '',
            subForm: {
                nama_sub_kriteria: '',
                nilai: 5,
            },
            openCreateKriteria() {
                this.kriteriaMode = 'create';
                this.kriteriaAction = '{{ route('kriteria.store') }}';
                this.kriteriaForm = {
                    kode: '{{ $nextKode }}',
                    nama_kriteria: '',
                    atribut: 'Benefit',
                    bobot: 5,
                    urutan: {{ $nextUrutan }},
                };
                this.showKriteriaModal = true;
            },
            openEditKriteria(item) {
                this.kriteriaMode = 'edit';
                this.kriteriaAction = item.update_url;
                this.kriteriaForm = {
                    kode: item.kode,
                    nama_kriteria: item.nama_kriteria,
                    atribut: item.atribut,
                    bobot: item.bobot,
                    urutan: item.urutan,
                };
                this.showKriteriaModal = true;
            },
            openEditSub(item) {
                this.subAction = item.update_url;
                this.subForm = {
                    nama_sub_kriteria: item.nama_sub_kriteria,
                    nilai: item.nilai,
                };
                this.showSubModal = true;
            }
        }"
        @keydown.escape.window="showKriteriaModal = false; showSubModal = false"
        class="space-y-5"
    >
        <!-- Toolbar -->
        <div class="book-card p-5 sm:p-6">
            <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                <div>
                    <h2 class="font-editorial text-lg font-bold text-[#1C2420]">Daftar Kriteria Penilaian ({{ $kriterias->count() }})</h2>
                    <p class="text-xs text-[#4B5851] mt-0.5">Klik salah satu kriteria untuk melihat dan mengubah daftar sub-kriterianya.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <form action="{{ route('kriteria.index') }}" method="GET" class="flex-1 sm:w-64">
                        <label for="search-kriteria" class="sr-only">Cari kriteria atau sub-kriteria</label>
                        <input id="search-kriteria" type="search" name="search" value="{{ $search }}" class="book-input text-sm" placeholder="Cari kriteria / pilihan...">
                    </form>

                    <button type="button" @click="openCreateKriteria()" class="btn-primary whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Kriteria
                    </button>
                </div>
            </div>

            @if($errors->any())
                <div class="mt-4 p-4 rounded-xl bg-[#FEE2E2] border border-[#FCA5A5] text-sm text-[#991B1B] font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Accordion Cards -->
        <div class="space-y-3.5">
            @forelse ($kriterias as $kriteria)
                <div class="book-card overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 hover:bg-[#FAF7F2] transition-colors">
                        <button
                            type="button"
                            @click="openId = (openId === {{ $kriteria->id }} ? null : {{ $kriteria->id }})"
                            :aria-expanded="(openId === {{ $kriteria->id }}).toString()"
                            class="flex items-center gap-3.5 text-left flex-1 min-w-0 min-h-[44px]"
                        >
                            <svg
                                class="w-5 h-5 text-[#4B5851] transition-transform duration-200 flex-shrink-0"
                                :class="openId === {{ $kriteria->id }} ? 'rotate-90 text-[#1B4D3E]' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>

                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-[#1B4D3E] text-white flex-shrink-0">
                                {{ $kriteria->kode }}
                            </span>

                            <div class="min-w-0">
                                <div class="font-bold text-[#1C2420] text-base truncate">{{ $kriteria->nama_kriteria }}</div>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs">
                                    <span class="px-2 py-0.5 rounded font-bold {{ $kriteria->atribut === 'Cost' ? 'bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]' : 'bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]' }}">
                                        {{ $kriteria->atribut }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded font-bold bg-[#F3EFE6] text-[#1C2420] border border-[#D6CFC2]">
                                        Bobot {{ $kriteria->bobot }} ({{ $kriteria->label_bobot }})
                                    </span>

                                    @if($kriteria->subKriterias->isEmpty())
                                        <span class="px-2 py-0.5 rounded font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FCD34D]">
                                            Belum ada pilihan nilai
                                        </span>
                                    @else
                                        <span class="text-[#4B5851] font-semibold">
                                            {{ $kriteria->subKriterias->count() }} Pilihan Nilai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>

                        <div class="flex items-center gap-2 pl-8 sm:pl-0 flex-shrink-0">
                            <button
                                type="button"
                                @click="openEditKriteria({
                                    kode: {{ json_encode($kriteria->kode) }},
                                    nama_kriteria: {{ json_encode($kriteria->nama_kriteria) }},
                                    atribut: {{ json_encode($kriteria->atribut) }},
                                    bobot: {{ $kriteria->bobot }},
                                    urutan: {{ $kriteria->urutan ?? 1 }},
                                    update_url: '{{ route('kriteria.update', $kriteria->id) }}'
                                })"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-[#92400E] bg-[#FEF3C7] hover:bg-[#FDE68A] border border-[#FCD34D] min-h-[36px]"
                            >
                                Ubah Kriteria
                            </button>

                            <form
                                action="{{ route('kriteria.destroy', $kriteria->id) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus kriteria {{ $kriteria->nama_kriteria }} ({{ $kriteria->kode }}) beserta semua pilihannya?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-soft">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <!-- Accordion Body -->
                    <div
                        x-show="openId === {{ $kriteria->id }}"
                        x-cloak
                        x-transition
                        class="border-t-[1.5px] border-[#E4DDD0] bg-[#FAF7F2] px-5 py-5"
                    >
                        <div class="overflow-x-auto bg-white rounded-xl border border-[#D6CFC2]">
                            <table class="book-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Pilihan Sub-Kriteria</th>
                                        <th scope="col" class="w-52">Skor Kelayakan (1-5)</th>
                                        <th scope="col" class="w-40 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kriteria->subKriterias as $sub)
                                        <tr>
                                            <td class="font-bold text-[#1C2420]">{{ $sub->nama_sub_kriteria }}</td>
                                            <td>
                                                <span class="inline-flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#E8F1EE] text-[#1B4D3E] font-extrabold text-xs border border-[#B8D4CA]">
                                                        {{ $sub->nilai }}
                                                    </span>
                                                    <span class="text-xs font-bold text-[#4B5851]">{{ $sub->label_nilai }}</span>
                                                </span>
                                            </td>
                                            <td class="text-right whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <button
                                                        type="button"
                                                        @click="openEditSub({
                                                            nama_sub_kriteria: {{ json_encode($sub->nama_sub_kriteria) }},
                                                            nilai: {{ $sub->nilai }},
                                                            update_url: '{{ route('sub_kriteria.update', $sub->id) }}'
                                                        })"
                                                        class="text-xs font-bold text-[#92400E] hover:underline px-2 py-1"
                                                    >
                                                        Ubah
                                                    </button>
                                                    <form
                                                        action="{{ route('sub_kriteria.destroy', $sub->id) }}"
                                                        method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('Hapus pilihan {{ $sub->nama_sub_kriteria }}?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs font-bold text-[#991B1B] hover:underline px-2 py-1 bg-transparent border-0">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-5 text-center text-[#4B5851] text-sm">
                                                Belum ada pilihan nilai untuk kriteria ini. Tambahkan melalui formulir di bawah.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Inline Form Tambah Sub-Kriteria -->
                        <form action="{{ route('sub_kriteria.store', $kriteria->id) }}" method="POST" class="mt-4 flex flex-col sm:flex-row items-stretch sm:items-end gap-3 bg-white p-4 rounded-xl border-2 border-dashed border-[#C9C0B1]">
                            @csrf
                            <div class="flex-1">
                                <label for="sub-nama-{{ $kriteria->id }}" class="book-label text-xs">Tambah Pilihan Baru untuk {{ $kriteria->kode }}</label>
                                <input id="sub-nama-{{ $kriteria->id }}" type="text" name="nama_sub_kriteria" required placeholder="Contoh: Tidak Bekerja / Jalan Kaki" class="book-input text-sm">
                            </div>
                            <div class="sm:w-52">
                                <label for="sub-nilai-{{ $kriteria->id }}" class="book-label text-xs">Nilai Kelayakan (1-5)</label>
                                <select id="sub-nilai-{{ $kriteria->id }}" name="nilai" required class="book-input text-sm">
                                    @foreach(\App\Models\SubKriteria::LABEL_NILAI as $val => $label)
                                        <option value="{{ $val }}">{{ $val }} - {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn-primary whitespace-nowrap">
                                + Tambah Pilihan
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="book-card p-10 text-center">
                    <p class="font-bold text-[#1C2420]">Belum ada kriteria yang tersimpan.</p>
                    <p class="text-sm text-[#4B5851] mt-1">Klik tombol Tambah Kriteria di atas untuk membuat kriteria pertama.</p>
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah / Edit Kriteria -->
        <div x-show="showKriteriaModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-[#1C2420]/55 p-4" role="dialog" aria-modal="true" aria-labelledby="modal-kriteria-title">
            <div @click.away="showKriteriaModal = false" class="book-card w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 bg-[#F3EFE6] border-b border-[#D6CFC2] flex justify-between items-center">
                    <h3 id="modal-kriteria-title" class="font-editorial text-lg font-bold text-[#1C2420]" x-text="kriteriaMode === 'create' ? 'Tambah Kriteria Baru' : 'Ubah Kriteria'"></h3>
                    <button type="button" @click="showKriteriaModal = false" aria-label="Tutup dialog" class="w-9 h-9 rounded-lg hover:bg-[#E4DDD0] text-[#1C2420] font-bold text-lg">&times;</button>
                </div>

                <form :action="kriteriaAction" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="kriteriaMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="m-kode" class="book-label text-xs">Kode Kriteria</label>
                            <input id="m-kode" type="text" name="kode" x-model="kriteriaForm.kode" required maxlength="10" placeholder="C9" class="book-input text-sm uppercase">
                        </div>
                        <div>
                            <label for="m-urutan" class="book-label text-xs">Urutan Tampil</label>
                            <input id="m-urutan" type="number" name="urutan" x-model="kriteriaForm.urutan" min="1" class="book-input text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="m-nama" class="book-label text-xs">Nama Kriteria</label>
                        <input id="m-nama" type="text" name="nama_kriteria" x-model="kriteriaForm.nama_kriteria" required placeholder="Contoh: Kepemilikan KIP" class="book-input text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="m-atribut" class="book-label text-xs">Atribut</label>
                            <select id="m-atribut" name="atribut" x-model="kriteriaForm.atribut" required class="book-input text-sm">
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                        <div>
                            <label for="m-bobot" class="book-label text-xs">Bobot Kepentingan</label>
                            <select id="m-bobot" name="bobot" x-model="kriteriaForm.bobot" required class="book-input text-sm">
                                @foreach(\App\Models\Kriteria::LABEL_BOBOT as $val => $label)
                                    <option value="{{ $val }}">{{ $val }} - {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-2.5 border-t border-[#E4DDD0]">
                        <button type="button" @click="showKriteriaModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Sub-Kriteria -->
        <div x-show="showSubModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-[#1C2420]/55 p-4" role="dialog" aria-modal="true" aria-labelledby="modal-sub-title">
            <div @click.away="showSubModal = false" class="book-card w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 bg-[#F3EFE6] border-b border-[#D6CFC2] flex justify-between items-center">
                    <h3 id="modal-sub-title" class="font-editorial text-lg font-bold text-[#1C2420]">Ubah Pilihan Sub-Kriteria</h3>
                    <button type="button" @click="showSubModal = false" aria-label="Tutup dialog" class="w-9 h-9 rounded-lg hover:bg-[#E4DDD0] text-[#1C2420] font-bold text-lg">&times;</button>
                </div>

                <form :action="subAction" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="ms-nama" class="book-label text-xs">Nama Pilihan</label>
                        <input id="ms-nama" type="text" name="nama_sub_kriteria" x-model="subForm.nama_sub_kriteria" required class="book-input text-sm">
                    </div>

                    <div>
                        <label for="ms-nilai" class="book-label text-xs">Nilai Kelayakan (1-5)</label>
                        <select id="ms-nilai" name="nilai" x-model="subForm.nilai" required class="book-input text-sm">
                            @foreach(\App\Models\SubKriteria::LABEL_NILAI as $val => $label)
                                <option value="{{ $val }}">{{ $val }} - {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-2.5 border-t border-[#E4DDD0]">
                        <button type="button" @click="showSubModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
