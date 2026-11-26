<x-app-layout>
    <x-slot name="header">
        Import Data Siswa
    </x-slot>

    <x-page-guide
        id="siswa_import"
        title="Cara Import Data Siswa dari Excel / CSV"
        :steps="['Unduh Template Excel terlebih dahulu', 'Isi data siswa sesuai kolom di template lalu simpan', 'Unggah file di kotak bawah dan klik Baca File', 'Periksa preview data lalu klik Import ke Database']"
    >
        Gunakan fitur ini jika ingin memasukkan banyak siswa sekaligus tanpa mengetik satu per satu.
    </x-page-guide>

    <div class="book-card p-6 sm:p-8" x-data="importHandler()">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 mb-6 border-b border-[#E4DDD0]">
            <div>
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Unggah File Excel atau CSV</h2>
                <p class="text-sm text-[#4B5851] mt-1">Format didukung: .xlsx, .xls, atau .csv (maksimal 10 MB).</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('siswa-import.template', 'xlsx') }}" class="btn-secondary text-xs">
                    <svg class="w-4 h-4 text-[#1B4D3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Template Excel
                </a>
                <a href="{{ route('siswa-import.template', 'csv') }}" class="btn-secondary text-xs">
                    <svg class="w-4 h-4 text-[#1B4D3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Template CSV
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-[#FEE2E2] border-[1.5px] border-[#FCA5A5] text-[#991B1B] p-4 text-sm font-semibold" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form id="uploadForm" @submit.prevent="uploadFile" class="space-y-5">
            @csrf
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[190px] p-6 border-2 border-dashed border-[#C9C0B1] hover:border-[#1B4D3E] rounded-2xl bg-[#FAF7F2] hover:bg-[#F3EFE6] transition-colors text-center">
                <div class="w-12 h-12 rounded-xl bg-[#E8F1EE] border border-[#B8D4CA] text-[#1B4D3E] flex items-center justify-center mb-3" aria-hidden="true">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <p class="text-sm font-bold text-[#1C2420]">Klik di sini untuk memilih file dari komputer atau HP</p>
                <p class="text-xs text-[#4B5851] mt-1">Atau seret file Excel / CSV langsung ke kotak ini</p>
                <p class="mt-3 px-3 py-1 rounded-lg bg-white border border-[#D6CFC2] text-xs font-bold text-[#1B4D3E]" x-text="fileName"></p>
                <input id="dropzone-file" type="file" name="file" class="sr-only" accept=".xlsx,.xls,.csv" @change="fileChanged" required />
            </label>

            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
                <a href="{{ route('siswas.index') }}" class="btn-secondary">
                    &larr; Kembali ke Data Siswa
                </a>
                <button type="submit" class="btn-primary" :disabled="loading">
                    <span x-show="!loading">Baca &amp; Periksa File</span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Sedang membaca file...
                    </span>
                </button>
            </div>
        </form>

        <!-- Sheet Selection -->
        <div x-show="sheets.length > 0" x-cloak class="mt-8 book-panel p-6">
            <h3 class="font-editorial text-lg font-bold text-[#1C2420]">Pilih Lembar (Sheet) Excel</h3>
            <p class="text-sm text-[#4B5851] mt-1 mb-5">Pilih satu sheet yang ingin Anda periksa dan masukkan datanya.</p>

            <form action="{{ route('siswa-import.preview') }}" method="POST">
                @csrf
                <input type="hidden" name="temp_path" :value="tempPath">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    <template x-for="(sheet, index) in sheets" :key="index">
                        <label :for="'sheet_'+index" class="block">
                            <input type="radio" :id="'sheet_'+index" name="sheet_index" :value="index" class="sr-only peer" required>
                            <div class="min-h-[52px] p-4 rounded-xl bg-white border-2 border-[#D6CFC2] peer-checked:border-[#1B4D3E] peer-checked:bg-[#E8F1EE] font-bold text-sm text-[#1C2420] hover:border-[#1B4D3E] transition-colors flex items-center justify-between">
                                <span x-text="sheet" class="truncate"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        Lanjut ke Pratinjau &amp; Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('importHandler', () => ({
                fileName: 'Belum ada file dipilih',
                loading: false,
                sheets: [],
                tempPath: '',

                fileChanged(e) {
                    if (e.target.files.length > 0) {
                        this.fileName = e.target.files[0].name;
                        this.sheets = [];
                    } else {
                        this.fileName = 'Belum ada file dipilih';
                    }
                },

                uploadFile() {
                    const fileInput = document.getElementById('dropzone-file');
                    if (fileInput.files.length === 0) return;

                    this.loading = true;
                    this.sheets = [];

                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route("siswa-import.get-sheets") }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.tempPath = data.path;
                            if (data.isCsv) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("siswa-import.preview") }}';

                                [
                                    ['_token', '{{ csrf_token() }}'],
                                    ['temp_path', this.tempPath],
                                    ['sheet_index', '0'],
                                ].forEach(([name, value]) => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    input.value = value;
                                    form.appendChild(input);
                                });

                                document.body.appendChild(form);
                                form.submit();
                            } else {
                                this.sheets = data.sheets;
                            }
                        } else {
                            alert(data.error || 'Terjadi kesalahan saat membaca file.');
                        }
                    })
                    .catch(error => {
                        this.loading = false;
                        alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                        console.error(error);
                    });
                }
            }));
        });
    </script>
</x-app-layout>
