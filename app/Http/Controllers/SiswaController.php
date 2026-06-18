<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        // Filters for Tata Usaha
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('kode_siswa', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('wali_kelas_id') && auth()->user()->role === 'tata_usaha') {
            $query->where('user_id', $request->wali_kelas_id);
        }

        $siswas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        $waliKelasList = User::where('role', 'wali_kelas')->get();
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas');

        return view('siswas.index', compact('siswas', 'waliKelasList', 'kelasList'));
    }

    public function create()
    {
        $this->authorize('create', Siswa::class);

        $c1 = Kriteria::where('kode', 'C1')->first()->subKriterias;
        $c2 = Kriteria::where('kode', 'C2')->first()->subKriterias;
        $c3 = Kriteria::where('kode', 'C3')->first()->subKriterias;
        $c4 = Kriteria::where('kode', 'C4')->first()->subKriterias;
        $c5 = Kriteria::where('kode', 'C5')->first()->subKriterias;
        $c6 = Kriteria::where('kode', 'C6')->first()->subKriterias;

        return view('siswas.create', compact('c1', 'c2', 'c3', 'c4', 'c5', 'c6'));
    }

    public function store(StoreSiswaRequest $request)
    {
        $this->authorize('create', Siswa::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        // Generate kode_siswa (e.g. SW-0001)
        $latest = Siswa::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $data['kode_siswa'] = 'SW-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Siswa::create($data);

        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $this->authorize('view', $siswa);
        $siswa->load(['user', 'c1.kriteria', 'c2.kriteria', 'c3.kriteria', 'c4.kriteria', 'c5.kriteria', 'c6.kriteria']);
        return view('siswas.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $this->authorize('update', $siswa);

        $c1 = Kriteria::where('kode', 'C1')->first()->subKriterias;
        $c2 = Kriteria::where('kode', 'C2')->first()->subKriterias;
        $c3 = Kriteria::where('kode', 'C3')->first()->subKriterias;
        $c4 = Kriteria::where('kode', 'C4')->first()->subKriterias;
        $c5 = Kriteria::where('kode', 'C5')->first()->subKriterias;
        $c6 = Kriteria::where('kode', 'C6')->first()->subKriterias;

        return view('siswas.edit', compact('siswa', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        $this->authorize('update', $siswa);

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
