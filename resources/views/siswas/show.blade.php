<x-app-layout>
    <x-slot name="header">
        Detail Data Siswa
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Data Identitas -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Identitas</h3>
                    @if($siswa->status_data === 'submitted')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Submitted</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Draft</span>
                    @endif
                </div>
                
                <ul class="space-y-4">
                    <li>
                        <span class="block text-sm text-gray-500">Kode Siswa</span>
                        <span class="font-semibold text-lg">{{ $siswa->kode_siswa }}</span>
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Nama Siswa</span>
                        <span class="font-semibold">{{ $siswa->nama_siswa }}</span>
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Kelas</span>
                        <span class="font-semibold">{{ $siswa->kelas }}</span>
                    </li>
                    <li>
                        <span class="block text-sm text-gray-500">Wali Kelas Penginput</span>
                        <span class="font-semibold">{{ $siswa->user->name }}</span>
                    </li>
                </ul>

                <div class="mt-8 flex space-x-3">
                    <a href="{{ route('siswas.index') }}" class="w-full text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Kembali</a>
                    
                    @can('update', $siswa)
                    <a href="{{ route('siswas.edit', $siswa->id) }}" class="w-full text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Edit Data</a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Data Penilaian -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Data Penilaian</h3>
                
                <ul class="space-y-4">
                    <li class="border-b pb-3">
                        <span class="block text-sm font-semibold text-gray-700">Pekerjaan Ayah ({{ $siswa->c1->kriteria->kode ?? 'C1' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c1->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c1->nilai }}</span>
                        </div>
                    </li>
                    <li class="border-b pb-3">
                        <span class="block text-sm font-semibold text-gray-700">Penghasilan Ayah ({{ $siswa->c2->kriteria->kode ?? 'C2' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c2->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c2->nilai }}</span>
                        </div>
                    </li>
                    <li class="border-b pb-3">
                        <span class="block text-sm font-semibold text-gray-700">Pekerjaan Ibu ({{ $siswa->c3->kriteria->kode ?? 'C3' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c3->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c3->nilai }}</span>
                        </div>
                    </li>
                    <li class="border-b pb-3">
                        <span class="block text-sm font-semibold text-gray-700">Penghasilan Ibu ({{ $siswa->c4->kriteria->kode ?? 'C4' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c4->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c4->nilai }}</span>
                        </div>
                    </li>
                    <li class="border-b pb-3">
                        <span class="block text-sm font-semibold text-gray-700">Jumlah Tanggungan Keluarga ({{ $siswa->c5->kriteria->kode ?? 'C5' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c5->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c5->nilai }}</span>
                        </div>
                    </li>
                    <li class="pb-1">
                        <span class="block text-sm font-semibold text-gray-700">Status Siswa ({{ $siswa->c6->kriteria->kode ?? 'C6' }})</span>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-gray-600">{{ $siswa->c6->nama_sub_kriteria }}</span>
                            <span class="font-bold text-blue-600">Nilai: {{ $siswa->c6->nilai }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
