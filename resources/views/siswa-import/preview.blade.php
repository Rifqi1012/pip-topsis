<x-app-layout>
    <x-slot name="header">
        Pratinjau Data Import
    </x-slot>

    <div class="book-card p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-5 border-b border-[#E4DDD0]">
            <div>
                <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Hasil Pemeriksaan File</h2>
                <p class="text-sm text-[#4B5851] mt-0.5">Periksa ringkasan di bawah sebelum menyimpan data ke sistem.</p>
            </div>
            <a href="{{ route('siswa-import.index') }}" class="btn-secondary text-xs">
                &larr; Upload Ulang File
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="book-panel p-4">
                <div class="text-xs font-bold uppercase text-[#4B5851]">Total Baris</div>
                <div class="font-editorial text-3xl font-bold text-[#1C2420] mt-1">{{ $summary['total'] }}</div>
            </div>
            <div class="rounded-xl p-4 bg-[#DCFCE7] border border-[#86EFAC]">
                <div class="text-xs font-bold uppercase text-[#166534]">Data Valid</div>
                <div class="font-editorial text-3xl font-bold text-[#166534] mt-1">{{ $summary['valid'] }}</div>
            </div>
            <div class="rounded-xl p-4 {{ $summary['error'] > 0 ? 'bg-[#FEE2E2] border border-[#FCA5A5]' : 'book-panel' }}">
                <div class="text-xs font-bold uppercase {{ $summary['error'] > 0 ? 'text-[#991B1B]' : 'text-[#4B5851]' }}">Data Bermasalah</div>
                <div class="font-editorial text-3xl font-bold {{ $summary['error'] > 0 ? 'text-[#991B1B]' : 'text-[#1C2420]' }} mt-1">{{ $summary['error'] }}</div>
            </div>
            <div class="rounded-xl p-4 {{ $summary['duplicate'] > 0 ? 'bg-[#FEF3C7] border border-[#FCD34D]' : 'book-panel' }}">
                <div class="text-xs font-bold uppercase {{ $summary['duplicate'] > 0 ? 'text-[#92400E]' : 'text-[#4B5851]' }}">NISN Duplikat</div>
                <div class="font-editorial text-3xl font-bold {{ $summary['duplicate'] > 0 ? 'text-[#92400E]' : 'text-[#1C2420]' }} mt-1">{{ $summary['duplicate'] }}</div>
            </div>
        </div>

        @if(!$summary['can_import'])
            <div class="rounded-xl bg-[#FEE2E2] border-[1.5px] border-[#FCA5A5] text-[#991B1B] p-5" role="alert">
                <p class="font-bold text-base">Data Belum Bisa Disimpan</p>
                <p class="text-sm mt-1">Terdapat {{ $summary['error'] }} baris yang bermasalah. Perbaiki dulu baris bertanda merah di file Excel Anda, lalu unggah ulang.</p>
            </div>
        @else
            <div class="rounded-xl bg-[#E8F1EE] border-[1.5px] border-[#1B4D3E] p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4" role="status">
                <div>
                    <p class="font-bold text-base text-[#1B4D3E]">Semua Data Siap Dimasukkan</p>
                    <p class="text-sm text-[#2E574A] mt-0.5">Sebanyak {{ $summary['valid'] }} data siswa valid dan siap disimpan.</p>
                </div>
                <form action="{{ route('siswa-import.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary" onclick="return confirm('Simpan {{ $summary['valid'] }} data siswa ini ke database?');">
                        Simpan {{ $summary['valid'] }} Data Siswa
                    </button>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-[#D6CFC2]">
            <table class="book-table whitespace-nowrap">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Baris</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nama &amp; NISN</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Tahun Ajaran</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr class="{{ !$row['is_valid'] ? 'bg-[#FEF2F2]' : '' }}">
                            <td class="text-center font-bold {{ !$row['is_valid'] ? 'text-[#991B1B]' : 'text-[#1C2420]' }}">{{ $row['row_number'] }}</td>
                            <td>
                                @if($row['is_valid'])
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">Valid</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FEE2E2] text-[#991B1B] border border-[#FCA5A5]">Perlu Diperbaiki</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold text-[#1C2420]">{{ $row['nama_siswa'] ?: '(Kosong)' }}</div>
                                <div class="text-xs text-[#4B5851]">{{ $row['nisn'] ?: '-' }}</div>
                            </td>
                            <td>{{ $row['kelas'] }}</td>
                            <td>{{ $row['tahun_ajaran'] }}</td>
                            <td>
                                @if(count($row['errors']) > 0)
                                    <ul class="list-disc list-inside text-xs font-semibold text-[#991B1B]">
                                        @foreach($row['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-xs text-[#166534] font-semibold">Siap disimpan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
