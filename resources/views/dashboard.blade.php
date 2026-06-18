<x-app-layout>
    <div class="max-w-7xl mx-auto pb-10">
        <!-- Dashboard Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard</h2>
        </div>

        <!-- Welcome Box -->
        <!-- <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-xl p-8 mb-8 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute right-20 -bottom-10 w-32 h-32 bg-yellow-400 opacity-20 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-2 flex items-center gap-2">
                    Selamat Datang, {{ explode(' ', $user->name)[0] }}! 
                    <span class="text-yellow-300">👋</span>
                </h3>
                <p class="text-blue-100 text-lg font-medium max-w-2xl">Sistem Pendukung Keputusan Penerima Program Indonesia Pintar (PIP) menggunakan metode TOPSIS.</p>
            </div>
        </div> -->

        <!-- 4 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white border border-slate-200 border-t-4 border-t-blue-500 rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-sm font-semibold text-slate-500 mb-4 text-center uppercase tracking-wider">Jumlah Siswa</p>
                <div class="flex items-center justify-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-extrabold text-slate-800">{{ $jumlahSiswa }}</span>
                        <p class="text-xs font-medium text-slate-500 mt-1">Data Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-slate-200 border-t-4 border-t-yellow-400 rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-sm font-semibold text-slate-500 mb-4 text-center uppercase tracking-wider">Jumlah Kriteria</p>
                <div class="flex items-center justify-center gap-4">
                    <div class="p-3 bg-yellow-50 rounded-full group-hover:bg-yellow-100 transition-colors">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-extrabold text-slate-800">{{ $jumlahKriteria }}</span>
                        <p class="text-xs font-medium text-slate-500 mt-1">Data Kriteria</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-slate-200 border-t-4 border-t-indigo-500 rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-sm font-semibold text-slate-500 mb-4 text-center uppercase tracking-wider">Jumlah Penilaian</p>
                <div class="flex items-center justify-center gap-4">
                    <div class="p-3 bg-indigo-50 rounded-full group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-extrabold text-slate-800">{{ $jumlahPenilaian }}</span>
                        <p class="text-xs font-medium text-slate-500 mt-1">Data Penilaian</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white border border-slate-200 border-t-4 border-t-emerald-500 rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-sm font-semibold text-slate-500 mb-4 text-center uppercase tracking-wider">Jumlah Penerima</p>
                <div class="flex items-center justify-center gap-4">
                    <div class="p-3 bg-emerald-50 rounded-full group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-extrabold text-slate-800">{{ $jumlahPenerima }}</span>
                        <p class="text-xs font-medium text-slate-500 mt-1">Siswa PIP</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-8 py-5 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Hasil Seleksi PIP
                </h3>
            </div>
            <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                <!-- Icon Circle -->
                <div class="flex-shrink-0 w-24 h-24 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full flex items-center justify-center border-4 border-white shadow-sm ring-1 ring-slate-100">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <!-- Text -->
                <div class="flex-1 text-[15px] text-slate-600 leading-relaxed">
                    <p class="mb-2">Proses seleksi penerima bantuan Program Indonesia Pintar (PIP) telah dilakukan menggunakan metode <strong class="text-blue-600">TOPSIS</strong>.</p>
                    <p>Gunakan menu Perhitungan TOPSIS untuk melihat hasil perankingan dan rekomendasi penerima bantuan secara menyeluruh.</p>
                </div>
                <!-- Button -->
                <div class="flex-shrink-0 mt-6 md:mt-0">
                    <a href="{{ route('topsis.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm gap-2">
                        <span>Lihat Hasil Perankingan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
