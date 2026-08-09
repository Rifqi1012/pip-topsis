<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'wali_kelas' && auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Unauthorized action.');
        }

        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->paginate(10);
        return view('tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'wali_kelas' && auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tahun_mulai' => 'required|digits:4|numeric|min:2000|max:2100',
            'tahun_selesai' => 'required|digits:4|numeric|gt:tahun_mulai'
        ], [
            'tahun_selesai.gt' => 'Tahun selesai harus lebih besar dari tahun mulai.',
            'digits' => 'Format tahun harus 4 digit angka.'
        ]);

        $nama = $request->tahun_mulai . '/' . $request->tahun_selesai;

        if (TahunAjaran::where('nama', $nama)->exists()) {
            return back()->withInput()->with('error', "Periode tahun ajaran {$nama} sudah ada.");
        }

        TahunAjaran::create([
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
            'nama' => $nama
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Periode Tahun Ajaran berhasil ditambahkan.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if (auth()->user()->role !== 'wali_kelas' && auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Unauthorized action.');
        }

        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Periode Tahun Ajaran berhasil dihapus.');
    }
}
