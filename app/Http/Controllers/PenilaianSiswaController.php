<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PenilaianSiswaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Only allow Wali Kelas or Tata Usaha
        if (!in_array(auth()->user()->role, ['wali_kelas', 'tata_usaha'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Siswa::with(['user', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6']);
        
        if (auth()->user()->role === 'wali_kelas') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('kode_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        $siswas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $kelasList = collect(['1', '2A', '2B', '3A', '3B', '4', '5', '6']);
        $periodeList = \App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');
        if ($periodeList->isEmpty()) {
            $periodeList = Siswa::select('periode')->whereNotNull('periode')->distinct()->pluck('periode');
        }

        return view('penilaian-siswa.index', compact('siswas', 'kelasList', 'periodeList'));
    }

    public function edit(Siswa $siswa)
    {
        // Similar authorization as Siswa update
        $this->authorize('update', $siswa);

        $c1 = Kriteria::where('kode', 'C1')->first()->subKriterias;
        $c2 = Kriteria::where('kode', 'C2')->first()->subKriterias;
        $c3 = Kriteria::where('kode', 'C3')->first()->subKriterias;
        $c4 = Kriteria::where('kode', 'C4')->first()->subKriterias;
        $c5 = Kriteria::where('kode', 'C5')->first()->subKriterias;
        $c6 = Kriteria::where('kode', 'C6')->first()->subKriterias;

        return view('penilaian-siswa.edit', compact('siswa', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $this->authorize('update', $siswa);

        $validated = $request->validate([
            'c1_id' => 'required|exists:sub_kriterias,id',
            'c2_id' => 'required|exists:sub_kriterias,id',
            'c3_id' => 'required|exists:sub_kriterias,id',
            'c4_id' => 'required|exists:sub_kriterias,id',
            'c5_id' => 'required|exists:sub_kriterias,id',
            'c6_id' => 'required|exists:sub_kriterias,id',
        ], [
            'required' => 'Kriteria ini wajib diisi.',
            'exists' => 'Pilihan kriteria tidak valid.'
        ]);

        $siswa->update($validated);

        return redirect()->route('penilaian-siswa.index')->with('success', 'Data penilaian kriteria siswa berhasil disimpan.');
    }
}
