<?php

namespace App\Http\Controllers;

use App\Http\Requests\KelasRequest;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::orderBy('nama', 'asc')->paginate(15);

        return view('kelas.index', compact('kelasList'));
    }

    public function store(KelasRequest $request)
    {
        $validated = $request->validated();
        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', "Kelas {$validated['nama']} berhasil ditambahkan.");
    }

    public function update(KelasRequest $request, Kelas $kelas)
    {
        $validated = $request->validated();
        DB::transaction(function () use ($kelas, $validated) {
            Siswa::withTrashed()->where('kelas', $kelas->nama)->update(['kelas' => $validated['nama']]);
            $kelas->update($validated);
        });

        return redirect()->route('kelas.index')->with('success', "Kelas berhasil diperbarui menjadi {$validated['nama']}.");
    }

    public function destroy(Kelas $kelas)
    {
        if (Siswa::withTrashed()->where('kelas', $kelas->nama)->exists()) {
            return back()->with('error', 'Kelas masih digunakan oleh data siswa dan tidak dapat dihapus.');
        }

        $nama = $kelas->nama;
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', "Kelas {$nama} berhasil dihapus.");
    }
}
