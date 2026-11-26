<?php

namespace App\Http\Controllers;

use App\Http\Requests\KriteriaRequest;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kriterias = Kriteria::with('subKriterias')
            ->when($search, function ($query) use ($search) {
                $query->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama_kriteria', 'like', "%{$search}%")
                    ->orWhereHas('subKriterias', function ($q) use ($search) {
                        $q->where('nama_sub_kriteria', 'like', "%{$search}%");
                    });
            })
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $nextUrutan = (Kriteria::max('urutan') ?? 0) + 1;
        $nextKode = 'C'.$nextUrutan;
        while (Kriteria::where('kode', $nextKode)->exists()) {
            $nextUrutan++;
            $nextKode = 'C'.$nextUrutan;
        }

        return view('kriteria.index', compact('kriterias', 'search', 'nextKode', 'nextUrutan'));
    }

    public function store(KriteriaRequest $request)
    {
        $validated = $request->validated();
        $validated['urutan'] = $validated['urutan'] ?? ((Kriteria::max('urutan') ?? 0) + 1);

        $kriteria = Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$kriteria->nama_kriteria} ({$kriteria->kode}) berhasil ditambahkan! Silakan tambahkan sub-kriterianya.")
            ->with('open_kriteria', $kriteria->id);
    }

    public function update(KriteriaRequest $request, Kriteria $kriteria)
    {
        $validated = $request->validated();
        $validated['urutan'] = $validated['urutan'] ?? $kriteria->urutan;

        $kriteria->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$kriteria->nama_kriteria} ({$kriteria->kode}) berhasil diperbarui!")
            ->with('open_kriteria', $kriteria->id);
    }

    public function destroy(Kriteria $kriteria)
    {
        $nama = "{$kriteria->nama_kriteria} ({$kriteria->kode})";
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$nama} beserta sub-kriterianya berhasil dihapus.");
    }
}
