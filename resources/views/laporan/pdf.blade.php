<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerima PIP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 0;
            top: -30px;
            width: 80px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        .info {
            margin-bottom: 15px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 2px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .data-table td.text-center {
            text-align: center;
        }
        .data-table td.text-right {
            text-align: right;
        }
        .badge-hijau {
            color: #10B981;
            font-weight: bold;
        }
        .badge-abu {
            color: #6B7280;
        }
    </style>
</head>
<body>

    <div class="header">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo Sekolah">
        @endif
        <h1 class="title">LAPORAN HASIL PERHITUNGAN PIP</h1>
        <p class="subtitle">SIBATU - Sistem Bantuan Tepat Sasaran (Metode TOPSIS)</p>
    </div>

    <div class="info">
        <table border="0">
            @if(!empty($kelas))
            <tr>
                <td width="18%"><strong>Kelas</strong></td>
                <td width="2%">:</td>
                <td>Kelas {{ $kelas }}</td>
            </tr>
            @endif
            @if(!empty($periode))
            <tr>
                <td><strong>Periode</strong></td>
                <td>:</td>
                <td>{{ $periode }}</td>
            </tr>
            @endif
            <tr>
                <td width="18%"><strong>Tanggal Cetak</strong></td>
                <td width="2%">:</td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Filter Status</strong></td>
                <td>:</td>
                <td>{{ ucfirst($filter) }}</td>
            </tr>
            <tr>
                <td><strong>Total Data</strong></td>
                <td>:</td>
                <td>{{ count($hasil) }} Siswa</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th width="15%">Kode Siswa</th>
                <th width="45%">Nama Siswa</th>
                <th width="12%">Kelas</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($hasil as $h)
                <tr>
                    <td class="text-center">{{ $h->ranking }}</td>
                    <td class="text-center">{{ $h->siswa->kode_siswa ?? '-' }}</td>
                    <td>{{ $h->siswa->nama_siswa ?? 'Siswa Dihapus' }}</td>
                    <td class="text-center">{{ $h->siswa->kelas ?? '-' }}</td>
                    <td class="text-center">
                        @if($h->status_rekomendasi == 'Direkomendasikan')
                            <span class="badge-hijau">Direkomendasikan</span>
                        @else
                            <span class="badge-abu">Cadangan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
