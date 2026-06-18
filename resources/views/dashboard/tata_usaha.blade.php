<x-app-layout>
    <x-slot name="header">
        Dashboard Tata Usaha
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="p-6 sm:p-8 text-gray-900">
            <h3 class="text-xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
            <p class="mt-2 text-gray-600">Ini adalah dashboard khusus untuk role <span class="font-semibold text-blue-600">Tata Usaha</span>.</p>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                    <h4 class="font-semibold text-blue-800">Total Pendaftar</h4>
                    <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-100">
                    <h4 class="font-semibold text-purple-800">Berkas Terverifikasi</h4>
                    <p class="mt-2 text-3xl font-bold text-purple-600">0</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-6 border border-orange-100">
                    <h4 class="font-semibold text-orange-800">Menunggu Proses</h4>
                    <p class="mt-2 text-3xl font-bold text-orange-600">0</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
