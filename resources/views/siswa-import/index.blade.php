<x-app-layout>
    <x-slot name="header">
        Import Data Siswa
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" x-data="importHandler()">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Import Data Siswa (Excel / CSV)</h3>
            <div class="flex space-x-2">
                <a href="{{ route('siswa-import.template', 'xlsx') }}" class="text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 font-medium rounded-lg text-sm px-4 py-2 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template Excel
                </a>
                <a href="{{ route('siswa-import.template', 'csv') }}" class="text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 font-medium rounded-lg text-sm px-4 py-2 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template CSV
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="mb-8">
            <p class="text-gray-600 mb-4 text-sm">Upload file .xlsx, .xls, atau .csv yang berisi data siswa. Harap gunakan template yang telah disediakan untuk memastikan struktur data yang valid.</p>
            
            <form id="uploadForm" @submit.prevent="uploadFile" class="mb-6">
                @csrf
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">XLSX, XLS, or CSV (MAX. 10MB)</p>
                            <p class="text-sm font-medium text-blue-600 mt-2" x-text="fileName"></p>
                        </div>
                        <input id="dropzone-file" type="file" name="file" class="hidden" accept=".xlsx,.xls,.csv" @change="fileChanged" required />
                    </label>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center transition-colors" :disabled="loading" :class="{'opacity-50 cursor-not-allowed': loading}">
                        <span x-show="!loading">Baca File</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Sheet Selection (Appears after file is parsed) -->
        <div x-show="sheets.length > 0" x-cloak class="border border-blue-100 bg-blue-50 rounded-lg p-6 animate-fade-in">
            <h4 class="text-lg font-bold text-blue-800 mb-2">Pilih Sheet yang Ingin Di-import</h4>
            <p class="text-sm text-blue-600 mb-4">File Excel ini memiliki beberapa sheet. Sistem hanya memproses satu sheet pada satu waktu untuk menghindari kesalahan import kelas.</p>
            
            <form action="{{ route('siswa-import.preview') }}" method="POST">
                @csrf
                <input type="hidden" name="temp_path" :value="tempPath">
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <template x-for="(sheet, index) in sheets" :key="index">
                        <div>
                            <input type="radio" :id="'sheet_'+index" name="sheet_index" :value="index" class="hidden peer" required>
                            <label :for="'sheet_'+index" class="inline-flex items-center justify-between w-full p-4 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50 transition-all">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold" x-text="sheet"></div>
                                </div>
                            </label>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
                        Lanjut ke Preview & Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('importHandler', () => ({
                fileName: 'Belum ada file terpilih',
                loading: false,
                sheets: [],
                tempPath: '',
                
                fileChanged(e) {
                    if (e.target.files.length > 0) {
                        this.fileName = e.target.files[0].name;
                        this.sheets = []; // Reset sheets when new file selected
                    } else {
                        this.fileName = 'Belum ada file terpilih';
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
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.tempPath = data.path;
                            
                            // If CSV, automatically submit to preview since it only has 1 sheet
                            if (data.isCsv) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("siswa-import.preview") }}';
                                
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = '{{ csrf_token() }}';
                                form.appendChild(csrfInput);
                                
                                const pathInput = document.createElement('input');
                                pathInput.type = 'hidden';
                                pathInput.name = 'temp_path';
                                pathInput.value = this.tempPath;
                                form.appendChild(pathInput);
                                
                                const indexInput = document.createElement('input');
                                indexInput.type = 'hidden';
                                indexInput.name = 'sheet_index';
                                indexInput.value = '0';
                                form.appendChild(indexInput);
                                
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
                        alert('Terjadi kesalahan jaringan.');
                        console.error(error);
                    });
                }
            }));
        });
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>
