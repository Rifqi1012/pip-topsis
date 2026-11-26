<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function store(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_sub_kriteria' => 'required|string|max:255',
            'nilai' => 'required|integer|min:1|max:5',
        ]);

        $kriteria->subKriterias()->create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', "Sub-kriteria berhasil ditambahkan ke {$kriteria->nama_kriteria} ({$kriteria->kode}).")
            ->with('open_kriteria', $kriteria->id);
    }

    public function update(Request $request, SubKriteria $subKriteria)
    {
        $validated = $request->validate([
            'nama_sub_kriteria' => 'required|string|max:255',
            'nilai' => 'required|integer|min:1|max:5',
        ]);

        $subKriteria->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Sub-kriteria berhasil diperbarui!')
            ->with('open_kriteria', $subKriteria->kriteria_id);
    }

    public function destroy(SubKriteria $subKriteria)
    {
        $kriteriaId = $subKriteria->kriteria_id;
        $subKriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Sub-kriteria berhasil dihapus.')
            ->with('open_kriteria', $kriteriaId);
    }
}
