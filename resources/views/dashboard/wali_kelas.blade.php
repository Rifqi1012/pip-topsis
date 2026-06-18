<x-app-layout>
    <x-slot name="header">
        Dashboard Wali Kelas
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 sm:p-8 text-gray-900">
            <h3 class="text-xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
            <p class="mt-2 text-gray-600">Ini adalah dashboard khusus untuk role <span class="font-semibold text-blue-600">Wali Kelas</span>.</p>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                    <h4 class="font-semibold text-blue-800">Data Siswa</h4>
                    <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                    <h4 class="font-semibold text-green-800">Kriteria Dinilai</h4>
                    <p class="mt-2 text-3xl font-bold text-green-600">0</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
