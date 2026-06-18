<?php

namespace App\Http\Controllers;

use App\Models\SubKriteria;
use App\Models\Kriteria;
use App\Http\Requests\UpdateSubKriteriaRequest;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $subKriterias = SubKriteria::with('kriteria')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_sub_kriteria', 'like', "%{$search}%")
                      ->orWhereHas('kriteria', function ($q) use ($search) {
                          $q->where('nama_kriteria', 'like', "%{$search}%");
                      });
            })
            ->orderBy('kriteria_id')
            ->orderBy('nilai', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('sub_kriteria.index', compact('subKriterias', 'search'));
    }

    public function show(SubKriteria $subKriteria)
    {
        $subKriteria->load('kriteria');
        return view('sub_kriteria.show', compact('subKriteria'));
    }

    public function edit(SubKriteria $subKriteria)
    {
        return view('sub_kriteria.edit', compact('subKriteria'));
    }

    public function update(UpdateSubKriteriaRequest $request, SubKriteria $subKriteria)
    {
        $subKriteria->update($request->validated());
        return redirect()->route('sub_kriteria.index')->with('success', 'Sub Kriteria berhasil diupdate!');
    }
}
