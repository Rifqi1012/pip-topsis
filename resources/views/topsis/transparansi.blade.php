<x-app-layout>
    <x-slot name="header">
        Transparansi Perhitungan TOPSIS
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('topsis.index', ['kelas' => $kelas, 'periode' => $periode]) }}" class="btn-secondary text-xs">
                &larr; Kembali ke Hasil Ranking
            </a>
            <span class="px-3 py-1.5 rounded-xl bg-[#E8F1EE] border border-[#B8D4CA] text-xs font-bold text-[#1B4D3E]">
                Kelas {{ $kelas ?: 'Semua' }} &bull; Periode {{ $periode ?: 'Semua' }}
            </span>
        </div>

        <x-page-guide id="topsis_transparansi" title="Cara Membaca Transparansi Hitungan">
            Halaman ini menampilkan tahapan matematis metode TOPSIS dari nilai mentah hingga skor akhir (<strong>Nilai Preferensi V</strong>). Semakin mendekati <strong>1.0000</strong>, semakin tinggi prioritas siswa untuk menerima bantuan.
        </x-page-guide>

        <!-- 1. Matriks Keputusan (X) -->
        <section class="book-card overflow-hidden">
            <div class="p-5 border-b border-[#E4DDD0] bg-[#FAF7F2]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">1. Matriks Keputusan (X)</h2>
                <p class="text-xs text-[#4B5851] mt-0.5">Nilai asli pilihan sub-kriteria (skala 1-5) untuk setiap siswa.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="book-table whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="text-center">{{ $k->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decisionMatrix as $id => $x)
                            <tr>
                                <td class="font-bold text-[#1C2420]">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="text-center font-semibold">{{ $x[$k->kode] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 2. Matriks Normalisasi (R) -->
        <section class="book-card overflow-hidden">
            <div class="p-5 border-b border-[#E4DDD0] bg-[#FAF7F2]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">2. Matriks Normalisasi (R)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="book-table whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="text-center">{{ $k->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($normalizedMatrix as $id => $r)
                            <tr>
                                <td class="font-bold text-[#1C2420]">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="text-center">{{ number_format($r[$k->kode], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 3. Matriks Normalisasi Terbobot (Y) -->
        <section class="book-card overflow-hidden">
            <div class="p-5 border-b border-[#E4DDD0] bg-[#FAF7F2]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">3. Matriks Normalisasi Terbobot (Y)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="book-table whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="text-center">{{ $k->kode }} (W={{ $k->bobot }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weightedMatrix as $id => $y)
                            <tr>
                                <td class="font-bold text-[#1C2420]">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="text-center">{{ number_format($y[$k->kode], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 4. Solusi Ideal -->
        <section class="book-card overflow-hidden">
            <div class="p-5 border-b border-[#E4DDD0] bg-[#FAF7F2]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">4. Solusi Ideal Positif (A+) &amp; Negatif (A-)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="book-table whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>Solusi Ideal</th>
                            @foreach($kriterias as $k)
                                <th class="text-center">{{ $k->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-[#DCFCE7]/60">
                            <td class="font-bold text-[#166534]">A+ (Positif)</td>
                            @foreach($kriterias as $k)
                                <td class="text-center font-bold text-[#166534]">{{ number_format($positiveIdeal[$k->kode], 4) }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-[#FEE2E2]/60">
                            <td class="font-bold text-[#991B1B]">A- (Negatif)</td>
                            @foreach($kriterias as $k)
                                <td class="text-center font-bold text-[#991B1B]">{{ number_format($negativeIdeal[$k->kode], 4) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 5. Jarak & Preferensi -->
        <section class="book-card overflow-hidden">
            <div class="p-5 border-b border-[#E4DDD0] bg-[#FAF7F2]">
                <h2 class="font-editorial text-lg font-bold text-[#1C2420]">5. Jarak Solusi &amp; Nilai Preferensi Akhir</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="book-table whitespace-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center w-24">Rank</th>
                            <th>Siswa</th>
                            <th class="text-center">D+ (Jarak Positif)</th>
                            <th class="text-center">D- (Jarak Negatif)</th>
                            <th class="text-center bg-[#E8F1EE] text-[#1B4D3E]">Nilai Preferensi (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $id => $rank)
                            <tr>
                                <td class="text-center font-editorial font-bold text-base text-[#1B4D3E]">{{ $rank }}</td>
                                <td class="font-bold text-[#1C2420]">{{ $siswas[$id]->nama_siswa }}</td>
                                <td class="text-center">{{ number_format($dPlus[$id], 4) }}</td>
                                <td class="text-center">{{ number_format($dMinus[$id], 4) }}</td>
                                <td class="text-center font-extrabold text-[#1B4D3E] bg-[#E8F1EE]/60">{{ number_format($preferences[$id], 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
