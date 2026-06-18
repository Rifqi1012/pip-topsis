<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Unauthorized action.');
        }

        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;
        return view('pengaturan.index', compact('kuota'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'kuota_penerima' => 'required|integer|min:1'
        ]);

        Pengaturan::updateOrCreate(
            ['key' => 'kuota_penerima'],
            ['value' => $request->kuota_penerima]
        );

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
