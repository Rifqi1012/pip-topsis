<x-app-layout>
    <x-slot name="header">
        Dashboard Kepala Sekolah
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 sm:p-8 text-gray-900">
            <h3 class="text-xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
            <p class="mt-2 text-gray-600">Ini adalah dashboard khusus untuk role <span class="font-semibold text-blue-600">Kepala Sekolah</span>.</p>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                    <h4 class="font-semibold text-blue-800">Total Kuota PIP</h4>
                    <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                    <h4 class="font-semibold text-green-800">Kandidat Disetujui</h4>
                    <p class="mt-2 text-3xl font-bold text-green-600">0</p>
                </div>
            </div>
            
            <div class="mt-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4">Laporan Hasil Perhitungan TOPSIS</h4>
                <div class="text-sm text-gray-500 italic">Belum ada data perhitungan yang disetujui.</div>
            </div>
        </div>
    </div>
</x-app-layout>
