<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\Kelas;
use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Siswa::class);

        $query = Siswa::with(['user']);

        if (auth()->user()->role === 'wali_kelas') {
            $query->where('user_id', auth()->id());
        }

        // Filters for Kepala Sekolah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('kode_siswa', 'like', "%{$search}%");
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

        $waliKelasList = User::where('role', 'wali_kelas')->get();
        $kelasList = Kelas::orderBy('nama', 'asc')->pluck('nama');
        if ($kelasList->isEmpty()) {
            $kelasList = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        }
        $periodeList = TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');
        if ($periodeList->isEmpty()) {
            $periodeList = Siswa::select('periode')->whereNotNull('periode')->distinct()->pluck('periode');
        }

        return view('siswas.index', compact('siswas', 'waliKelasList', 'kelasList', 'periodeList'));
    }

    public function create()
    {
        $this->authorize('create', Siswa::class);

        $kelasList = Kelas::orderBy('nama', 'asc')->pluck('nama');
        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');

        return view('siswas.create', compact('kelasList', 'tahunAjarans'));
    }

    public function store(StoreSiswaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Generate kode_siswa (e.g. SW-0001)
        $latest = Siswa::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $data['kode_siswa'] = 'SW-'.str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Siswa::create($data);

        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $this->authorize('view', $siswa);
        $siswa->load(['user', 'penilaians.subKriteria']);
        $kriterias = Kriteria::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();
        $penilaians = $siswa->penilaians->keyBy('kriteria_id');

        return view('siswas.show', compact('siswa', 'kriterias', 'penilaians'));
    }

    public function edit(Siswa $siswa)
    {
        $this->authorize('update', $siswa);

        $kelasList = Kelas::orderBy('nama', 'asc')->pluck('nama');
        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');

        return view('siswas.edit', compact('siswa', 'kelasList', 'tahunAjarans'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        $siswa->update($request->validated());

        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        $this->authorize('delete', $siswa);
        $siswa->delete();

        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil dihapus (soft delete).');
    }
}
