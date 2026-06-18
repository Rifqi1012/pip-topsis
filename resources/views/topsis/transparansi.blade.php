<x-app-layout>
    <x-slot name="header">
        Transparansi Perhitungan TOPSIS
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('topsis.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Ranking</a>
    </div>

    <!-- 1. Matriks Keputusan (X) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">1. Matriks Keputusan (X)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-2 border-r">Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="px-4 py-2 border-r text-center">{{ $k->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decisionMatrix as $id => $x)
                            <tr class="border-b">
                                <td class="px-4 py-2 border-r font-medium text-gray-900">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="px-4 py-2 border-r text-center">{{ $x[$k->kode] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. Matriks Normalisasi (R) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">2. Matriks Normalisasi (R)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-2 border-r">Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="px-4 py-2 border-r text-center">{{ $k->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($normalizedMatrix as $id => $r)
                            <tr class="border-b">
                                <td class="px-4 py-2 border-r font-medium text-gray-900">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="px-4 py-2 border-r text-center">{{ number_format($r[$k->kode], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Matriks Normalisasi Terbobot (Y) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">3. Matriks Normalisasi Terbobot (Y)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-2 border-r">Siswa</th>
                            @foreach($kriterias as $k)
                                <th class="px-4 py-2 border-r text-center">{{ $k->kode }} (W={{ $k->bobot }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weightedMatrix as $id => $y)
                            <tr class="border-b">
                                <td class="px-4 py-2 border-r font-medium text-gray-900">{{ $siswas[$id]->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="px-4 py-2 border-r text-center">{{ number_format($y[$k->kode], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Solusi Ideal (A+ dan A-) -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">4. Solusi Ideal Positif (A+) & Negatif (A-)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-2 border-r">Solusi Ideal</th>
                            @foreach($kriterias as $k)
                                <th class="px-4 py-2 border-r text-center">{{ $k->kode }} ({{ $k->atribut }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b bg-green-50">
                            <td class="px-4 py-2 border-r font-bold text-green-700">A+ (Positif)</td>
                            @foreach($kriterias as $k)
                                <td class="px-4 py-2 border-r text-center font-semibold text-green-700">{{ number_format($positiveIdeal[$k->kode], 4) }}</td>
                            @endforeach
                        </tr>
                        <tr class="border-b bg-red-50">
                            <td class="px-4 py-2 border-r font-bold text-red-700">A- (Negatif)</td>
                            @foreach($kriterias as $k)
                                <td class="px-4 py-2 border-r text-center font-semibold text-red-700">{{ number_format($negativeIdeal[$k->kode], 4) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. Jarak & Preferensi -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">5. Jarak Solusi & Nilai Preferensi</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-2 border-r">Rank</th>
                            <th class="px-4 py-2 border-r">Siswa</th>
                            <th class="px-4 py-2 border-r text-center">D+ (Jarak Positif)</th>
                            <th class="px-4 py-2 border-r text-center">D- (Jarak Negatif)</th>
                            <th class="px-4 py-2 border-r text-center bg-blue-50">Nilai Preferensi (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $id => $rank)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 border-r text-center font-bold">{{ $rank }}</td>
                                <td class="px-4 py-2 border-r font-medium text-gray-900">{{ $siswas[$id]->nama_siswa }}</td>
                                <td class="px-4 py-2 border-r text-center">{{ number_format($dPlus[$id], 4) }}</td>
                                <td class="px-4 py-2 border-r text-center">{{ number_format($dMinus[$id], 4) }}</td>
                                <td class="px-4 py-2 border-r text-center font-bold text-blue-600 bg-blue-50">{{ number_format($preferences[$id], 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
