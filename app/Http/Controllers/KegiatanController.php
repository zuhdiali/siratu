<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Mitra;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatanTahunIni = Kegiatan::whereRaw(DB::raw('YEAR(tgl_selesai) = ' . date('Y')))->count();
        $kegiatans = Kegiatan::orderBy('tgl_selesai', 'asc')->get();
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->honor_pencacahan == null) {
                $kegiatan->honor_pencacahan = 0;
            }
            if ($kegiatan->honor_pengawasan == null) {
                $kegiatan->honor_pengolahan = 0;
            }
            $kegiatan->pjk = Pegawai::find($kegiatan->id_pjk);
        }
        return view('kegiatan.index', ['kegiatans' => $kegiatans, 'kegiatanTahunIni' => $kegiatanTahunIni]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        // $mitras = Mitra::orderBy('nama', 'asc')->get();
        // return view('kegiatan.create', ['kegiatans' => $kegiatans, 'mitras' => $mitras]);
        return view('kegiatan.create', ['pegawais' => $pegawais]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'honor_pengawasan' => 'nullable|numeric',
            'honor_pencacahan' => 'nullable|numeric',
            'id_pjk' => 'required',
        ]);


        $kegiatan = new Kegiatan;
        $kegiatan->nama = $request->nama;
        $kegiatan->tgl_mulai = $request->tgl_mulai;
        $kegiatan->tgl_selesai = $request->tgl_selesai;
        $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
        $kegiatan->honor_pengawasan = $request->honor_pengawasan;
        $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
        $kegiatan->honor_pencacahan = $request->honor_pencacahan;
        $kegiatan->id_pjk = $request->id_pjk;
        $kegiatan->save();

        if (!$kegiatan->wasRecentlyCreated) {
            return redirect()->route('kegiatan.create')->with('error', 'Gagal.');
        }

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        $mitras = Mitra::orderBy('nama', 'asc')->get();
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        return view('kegiatan.edit', ['kegiatan' => $kegiatan, 'pegawais' => $pegawais, 'mitras' => $mitras]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
            // 'satuan_honor' => 'required',
            'honor_pengawasan' => 'nullable|numeric',
            'honor_pencacahan' => 'nullable|numeric',
            'id_pjk' => 'required',
        ]);

        try {
            $kegiatan = Kegiatan::find($id);
            $kegiatan->nama = $request->nama;
            $kegiatan->tgl_mulai = $request->tgl_mulai;
            $kegiatan->tgl_selesai = $request->tgl_selesai;
            $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
            $kegiatan->honor_pengawasan = $request->honor_pengawasan;
            $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
            $kegiatan->honor_pencacahan = $request->honor_pencacahan;
            $kegiatan->id_pjk = $request->id_pjk;
            $kegiatan->pegawai()->sync($request->pegawai);
            $kegiatan->mitra()->sync($request->mitra);
            $kegiatan->save();
        } catch (\Throwable $th) {
            return redirect()->route('kegiatan.edit', ['id' => $id])->with('error', 'Gagal.');
        }

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diubah.');
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan->honor_pencacahan == null) {
            $kegiatan->honor_pencacahan = 0;
        }
        if ($kegiatan->honor_pengawasan == null) {
            $kegiatan->honor_pengolahan = 0;
        }
        $kegiatan->pjk = Pegawai::find($kegiatan->id_pjk);
        return view('kegiatan.show', ['kegiatan' => $kegiatan]);
    }


    public function destroy($id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan->pegawai->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada kegiatan terlibat.');
        }
        if ($kegiatan->mitra->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada mitra terlibat.');
        }
        $nama = $kegiatan->nama;
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $nama . ' berhasil dihapus.');
    }

    public function editTerlibat($id)
    {
        $kegiatan = Kegiatan::find($id);
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        $mitras = Mitra::orderBy('nama', 'asc')->get();
        return view('kegiatan.edit-terlibat', ['kegiatan' => $kegiatan, 'pegawais' => $pegawais, 'mitras' => $mitras]);
    }

    public function updateTerlibat(Request $request, $id)
    {
        // dd($request->all());
        $kegiatan = Kegiatan::find($id);
        $kegiatan->pegawai()->sync($request->pegawai);
        $kegiatan->mitra()->sync($request->mitra);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan terlibat berhasil diubah.');
    }
}
