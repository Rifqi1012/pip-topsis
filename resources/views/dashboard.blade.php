<x-app-layout>
    <div class="space-y-7">
        <!-- Editorial Welcome Hero -->
        <section class="rounded-2xl bg-[#1B4D3E] text-white border-2 border-[#143B2F] shadow-[0_4px_0_0_#102D24] p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#FDE68A]">
                        {{ Auth::user()->role === 'kepala_sekolah' ? 'Panel Kepala Sekolah' : 'Panel Wali Kelas' }}
                    </p>
                    <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-white mt-1.5 leading-snug">
                        Halo, {{ Auth::user()->name }}
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-[#E2ECE8] leading-relaxed">
                        Selamat datang di <strong>SIBATU (Sistem Bantuan Tepat Sasaran)</strong>.
                        @if(Auth::user()->role === 'wali_kelas')
                            Ikuti urutan langkah di bawah untuk memasukkan data siswa dan mengisi penilaian kriteria dengan mudah.
                        @else
                            Kelola data master sekolah, jalankan seleksi penerima bantuan, dan unduh laporan resmi dari satu tempat.
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 flex-shrink-0">
                    @if(Auth::user()->role === 'wali_kelas')
                        <a href="{{ route('siswas.create') }}" class="btn-accent">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Siswa Baru
                        </a>
                        <a href="{{ route('penilaian-siswa.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2.5 rounded-xl bg-white text-[#1B4D3E] font-bold text-sm border border-white hover:bg-[#FAF7F2] transition-colors">
                            Isi Penilaian Siswa
                        </a>
                    @else
                        <a href="{{ route('topsis.index') }}" class="btn-accent">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Buka Proses Seleksi
                        </a>
                        <a href="{{ route('kriteria.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2.5 rounded-xl bg-white text-[#1B4D3E] font-bold text-sm border border-white hover:bg-[#FAF7F2] transition-colors">
                            Kelola Kriteria
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Ringkasan Data Nyata (Editorial Number Hierarchy) -->
        <section aria-label="Ringkasan data">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="book-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#4B5851]">
                        {{ Auth::user()->role === 'wali_kelas' ? 'Siswa Anda' : 'Total Siswa' }}
                    </p>
                    <p class="font-editorial text-4xl font-bold text-[#1C2420] mt-2">{{ $jumlahSiswa }}</p>
                    <p class="text-xs font-medium text-[#4B5851] mt-1">Tercatat di sistem</p>
                </div>

                <div class="book-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#4B5851]">Kriteria Aktif</p>
                    <p class="font-editorial text-4xl font-bold text-[#1B4D3E] mt-2">{{ $jumlahKriteria }}</p>
                    <p class="text-xs font-medium text-[#4B5851] mt-1">Dasar penilaian bantuan</p>
                </div>

                <div class="book-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#4B5851]">Siswa Siap Seleksi</p>
                    <p class="font-editorial text-4xl font-bold text-[#B45309] mt-2">{{ $jumlahPenilaian }}</p>
                    <p class="text-xs font-medium text-[#4B5851] mt-1">Berstatus siap diproses</p>
                </div>

                <div class="book-card p-5 bg-[#E8F1EE] border-[#B8D4CA]">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#1B4D3E]">Rekomendasi Penerima</p>
                    <p class="font-editorial text-4xl font-bold text-[#1B4D3E] mt-2">{{ $jumlahPenerima }}</p>
                    <p class="text-xs font-medium text-[#2E574A] mt-1">Sesuai hasil seleksi terbaru</p>
                </div>
            </div>
        </section>

        <!-- Alur Kerja Terpandu (Ramah Orang Awam) -->
        <section class="book-card p-6 sm:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-6 border-b border-[#E4DDD0]">
                <div>
                    <h2 class="font-editorial text-xl font-bold text-[#1C2420]">Panduan Langkah Kerja</h2>
                    <p class="text-sm text-[#4B5851] mt-0.5">Bingung mulai dari mana? Ikuti urutan langkah sederhana di bawah ini.</p>
                </div>
            </div>

            @if(Auth::user()->role === 'wali_kelas')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#1B4D3E] text-white font-editorial font-bold text-lg">1</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Masukkan Data Siswa</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Tambahkan siswa satu per satu atau unggah sekaligus dari file Excel/CSV melalui menu Data Siswa.
                            </p>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('siswas.index') }}" class="btn-secondary text-xs py-2 px-3">Buka Data Siswa</a>
                            <a href="{{ route('siswa-import.index') }}" class="btn-secondary text-xs py-2 px-3">Import Excel</a>
                        </div>
                    </div>

                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#1B4D3E] text-white font-editorial font-bold text-lg">2</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Isi Penilaian Kriteria</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Pilih kondisi keluarga masing-masing siswa sesuai kriteria aktif. Pastikan semua kriteria terisi lengkap.
                            </p>
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('penilaian-siswa.index') }}" class="btn-primary text-xs py-2 px-4">Mulai Penilaian</a>
                        </div>
                    </div>

                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#B45309] text-white font-editorial font-bold text-lg">3</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Pastikan Status Siap</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Ubah status data siswa menjadi <strong>Siap Seleksi (Submitted)</strong> agar Kepala Sekolah dapat memproses perankingan.
                            </p>
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('siswas.index') }}" class="btn-secondary text-xs py-2 px-3">Cek Status Siswa</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#1B4D3E] text-white font-editorial font-bold text-lg">1</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Siapkan Data Master</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Pastikan daftar kelas, periode tahun ajaran, dan kriteria penilaian sudah sesuai kebutuhan sekolah.
                            </p>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('kelas.index') }}" class="btn-secondary text-xs py-2 px-3">Data Kelas</a>
                            <a href="{{ route('kriteria.index') }}" class="btn-secondary text-xs py-2 px-3">Data Kriteria</a>
                        </div>
                    </div>

                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#1B4D3E] text-white font-editorial font-bold text-lg">2</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Atur Kuota &amp; Cek Siswa</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Tentukan jumlah kuota penerima bantuan dan pantau data siswa yang sudah diinput oleh Wali Kelas.
                            </p>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('pengaturan.index') }}" class="btn-secondary text-xs py-2 px-3">Atur Kuota</a>
                            <a href="{{ route('siswas.index') }}" class="btn-secondary text-xs py-2 px-3">Lihat Siswa</a>
                        </div>
                    </div>

                    <div class="book-panel p-5 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#B45309] text-white font-editorial font-bold text-lg">3</span>
                            <h3 class="mt-3 text-base font-bold text-[#1C2420]">Hitung Ranking &amp; Cetak</h3>
                            <p class="mt-1.5 text-sm text-[#4B5851] leading-relaxed">
                                Jalankan perhitungan TOPSIS per kelas dan periode, periksa transparansi nilai, lalu unduh laporan PDF atau Excel.
                            </p>
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('topsis.index') }}" class="btn-primary text-xs py-2 px-4">Proses Seleksi</a>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
