<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama', 'asc')->get();
        $totalRuangan = count($ruangans);
        return view('ruangan.index', ['ruangans' => $ruangans, 'totalRuangan' => $totalRuangan]);
    }

    public function create()
    {
        return view('ruangan.create');
    }

    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
        ]);

        $ruangan = new Ruangan;
        $ruangan->nama = $request->nama;
        $ruangan->save();

        if (!$ruangan->wasRecentlyCreated) {
            return redirect()->route('ruangan.create')->with('error', 'Gagal.');
        }

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::find($id);
        return view('ruangan.edit', ['ruangan' => $ruangan]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
        ]);

        $ruangan = Ruangan::find($id);
        $ruangan->nama = $request->nama;
        $ruangan->save();

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diubah.');
    }

    public function destroy($id)
    {
        $pegawais = Pegawai::where('ruangan', $id)->get();
        if (count($pegawais) > 0) {
            return redirect()->route('ruangan.index')->with('error', 'Ruangan tidak bisa dihapus karena masih ada pegawai yang menggunakan ruangan ini.');
        }
        $ruangan = Ruangan::find($id);
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
