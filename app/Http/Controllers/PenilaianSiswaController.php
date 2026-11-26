<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PenilaianSiswaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        if (! in_array(auth()->user()->role, ['wali_kelas', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Siswa::with(['user', 'penilaians']);

        if (auth()->user()->role === 'wali_kelas') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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

        if ($request->filled('wali_kelas_id') && auth()->user()->role === 'kepala_sekolah') {
            $query->where('user_id', $request->wali_kelas_id);
        }

        $siswas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('nama', 'asc')->pluck('nama');
        if ($kelasList->isEmpty()) {
            $kelasList = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        }
        $periodeList = TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');
        if ($periodeList->isEmpty()) {
            $periodeList = Siswa::select('periode')->whereNotNull('periode')->distinct()->pluck('periode');
        }

        $kriteriaIds = Kriteria::pluck('id');
        $totalKriteria = $kriteriaIds->count();

        return view('penilaian-siswa.index', compact('siswas', 'kelasList', 'periodeList', 'kriteriaIds', 'totalKriteria'));
    }

    public function edit(Siswa $siswa)
    {
        $this->authorize('update', $siswa);

        $kriterias = Kriteria::with('subKriterias')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $existingPenilaian = $siswa->penilaians()
            ->pluck('sub_kriteria_id', 'kriteria_id')
            ->toArray();

        return view('penilaian-siswa.edit', compact('siswa', 'kriterias', 'existingPenilaian'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $this->authorize('update', $siswa);

        $kriterias = Kriteria::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();

        if ($kriterias->isEmpty()) {
            return redirect()->route('penilaian-siswa.index')
                ->with('error', 'Belum ada kriteria yang dikonfigurasi oleh Kepala Sekolah.');
        }

        $rules = [];
        $messages = [];
        foreach ($kriterias as $kriteria) {
            $key = "penilaian.{$kriteria->id}";
            $rules[$key] = [
                'required',
                Rule::exists('sub_kriterias', 'id')->where('kriteria_id', $kriteria->id),
            ];
            $messages["{$key}.required"] = "Kriteria {$kriteria->nama_kriteria} ({$kriteria->kode}) wajib diisi.";
            $messages["{$key}.exists"] = "Pilihan untuk {$kriteria->nama_kriteria} tidak valid.";
        }

        $validated = $request->validate($rules, $messages);

        DB::transaction(function () use ($siswa, $kriterias, $validated) {
            foreach ($kriterias as $kriteria) {
                $subKriteriaId = $validated['penilaian'][$kriteria->id];
                PenilaianSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'kriteria_id' => $kriteria->id,
                    ],
                    [
                        'sub_kriteria_id' => $subKriteriaId,
                    ]
                );
            }
        });

        return redirect()->route('penilaian-siswa.index')
            ->with('success', 'Data penilaian kriteria siswa berhasil disimpan.');
    }
}
