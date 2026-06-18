<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Http\Requests\UpdateKriteriaRequest;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('urutan', 'asc')->get();
        return view('kriteria.index', compact('kriterias'));
    }

    public function show(Kriteria $kriteria)
    {
        $kriteria->load('subKriterias');
        return view('kriteria.show', compact('kriteria'));
    }

    public function edit(Kriteria $kriteria)
    {
        return view('kriteria.edit', compact('kriteria'));
    }

    public function update(UpdateKriteriaRequest $request, Kriteria $kriteria)
    {
        $kriteria->update($request->validated());
        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diupdate!');
    }
}
