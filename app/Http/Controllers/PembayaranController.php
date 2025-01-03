<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Kegiatan;
use App\Models\Pembayaran;
use App\Models\KegiatanMitra;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = KegiatanMitra::where('honor', '!=', null)->get();
        foreach ($pembayarans as $pembayaran) {
            $pembayaran->kegiatan = Kegiatan::find($pembayaran->kegiatan_id);
            $pembayaran->mitra = Mitra::find($pembayaran->mitra_id);
            $pembayaran->bukti_pembayaran = Pembayaran::find($pembayaran->bukti_pembayaran_id);
        }
        // dd($pembayarans);
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create($jenis)
    {
        $kegiatans = Kegiatan::all();
        return view('pembayaran.create', compact('kegiatans', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            // 'nominal' => 'required|numeric',
            'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg,gif,svg',
            // 'honor' => 'required|numeric',
        ]);
        // dd($request->all());
        $kegiatan = Kegiatan::find($request->id_kegiatan);
        $pembayaran = new Pembayaran();
        if ($request->has('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = str_replace(' ', '_', $kegiatan->nama) .  '_' . date('Y_m_d') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/pembayaran/', $filename);
            $pembayaran->bukti_pembayaran = $filename;
            // $kegiatan->mitra()->updateExistingPivot($request->id_mitra, ['bukti_pembayaran' => $filename]);
        }

        $pembayaran->id_kegiatan = $request->id_kegiatan;
        $pembayaran->save();

        foreach ($request->honor as $key => $value) {
            // dd($key, $value);
            if ($value == null || $value == "") {
                continue;
            }
            $kegiatan->mitra()->updateExistingPivot($key, ['honor' => $value]);
            $kegiatan->mitra()->updateExistingPivot($key, ['bukti_pembayaran_id' => $pembayaran->id]);
            // $kegiatan_mitra = new KegiatanMitra();
            // $kegiatan_mitra->kegiatan_id = $request->id_kegiatan;
            // $kegiatan_mitra->mitra_id = $honor['id_mitra'];
            // $kegiatan_mitra->honor = $honor['nominal'];
            $kegiatan->save();
        }

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran created successfully.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::find($id);
        $pembayaran->mitra_dibayar = KegiatanMitra::where('bukti_pembayaran_id', $id)->get();
        foreach ($pembayaran->mitra_dibayar as $m) {
            $m->mitra = Mitra::find($m->mitra_id);
        }
        $kegiatan = Kegiatan::find($pembayaran->id_kegiatan);
        // $pembayaran = KegiatanMitra::find($id);
        // $pembayaran->mitra = Mitra::find($pembayaran->mitra_id);
        // $pembayaran->kegiatan = Kegiatan::find($pembayaran->kegiatan_id);

        // $mitras = KegiatanMitra::where(function ($query) use ($pembayaran) {
        //     $query->where('honor', null)->orWhere('mitra_id', $pembayaran->mitra_id);
        // })->where('kegiatan_id', $pembayaran->kegiatan->id)->get();
        // foreach ($mitras as $m) {
        //     $m->mitra = Mitra::find($m->mitra_id);
        // }
        // // dd($mitras);
        // $kegiatans = Kegiatan::all();
        return view('pembayaran.edit', compact('pembayaran', 'kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'id_kegiatan' => 'required',
            // 'nominal' => 'required|numeric',
            'bukti_pembayaran' => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            // 'id_mitra' => 'required',
            // 'id_mitra_sebelumnya' => 'required',
        ]);


        $pembayaran = Pembayaran::find($id);
        $kegiatan = Kegiatan::find($pembayaran->id_kegiatan);

        if ($request->has('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = str_replace(' ', '_', $kegiatan->nama) . '_' . $pembayaran->mitra_id . '_' . date('Y_m_d') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/pembayaran/', $filename);
            $pembayaran->bukti_pembayaran = $filename;
        }
        $pembayaran->save();

        foreach ($request->honor as $key => $value) {
            // dd($key, $value);
            $kegiatan->mitra()->updateExistingPivot($key, ['honor' => $value]);
            $kegiatan->mitra()->updateExistingPivot($key, ['bukti_pembayaran_id' => $id]);
            // $kegiatan_mitra = new KegiatanMitra();
            // $kegiatan_mitra->kegiatan_id = $request->id_kegiatan;
            // $kegiatan_mitra->mitra_id = $honor['id_mitra'];
            // $kegiatan_mitra->honor = $honor['nominal'];
            $kegiatan->save();
        }

        // $pembayaran_mitra_sebelumnya = KegiatanMitra::where('kegiatan_id', $pembayaran_mitra->kegiatan_id)->where('mitra_id', $pembayaran_mitra->mitra_id)->first();

        // if ($pembayaran_mitra->mitra_id != $request->id_mitra) {

        //     $pembayaran_mitra_sebelumnya->bukti_pembayaran = null;
        //     $pembayaran_mitra_sebelumnya->honor = null;

        //     $pembayaran_mitra->honor = $request->nominal;
        // } else {
        //     $pembayaran_mitra->honor = $request->nominal;
        // }

        // if ($request->has('bukti_pembayaran')) {
        //     $file = $request->file('bukti_pembayaran');
        //     $filename = str_replace(' ', '_', $kegiatan->nama) . '_' . $request->id_mitra . '_' . date('Y_m_d') . '_' . time() . '.' . $file->getClientOriginalExtension();
        //     $file->move('uploads/pembayaran/', $filename);
        //     if ($pembayaran_mitra->bukti_pembayaran != null) {
        //         unlink('uploads/pembayaran/' . $pembayaran_mitra->bukti_pembayaran);
        //     }
        //     if ($pembayaran_mitra->mitra_id != $request->id_mitra) {
        //         $pembayaran_mitra->bukti_pembayaran = $filename;
        //     } else {
        //         $pembayaran_mitra_sebelumnya->bukti_pembayaran = $filename;
        //     }
        // }
        // $pembayaran_mitra->save();
        // $pembayaran_mitra_sebelumnya->save();
        // if ($request->id_mitra != $request->id_mitra_sebelumnya) {
        //     $kegiatan->mitra()->updateExistingPivot($pembayaran_mitra->mitra_id, ['honor' => null]);
        //     $kegiatan->mitra()->updateExistingPivot($pembayaran_mitra->mitra_id, ['bukti_pembayaran' => null]);
        // }

        // // $pembayaran->id_kegiatan = $request->id_kegiatan;
        // $kegiatan = Kegiatan::find($request->id_kegiatan);
        // $kegiatan->mitra()->updateExistingPivot($request->id_mitra, ['honor' => $request->nominal]);
        // $pembayaran_mitra->save();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = KegiatanMitra::find($id);
        $pembayaran->honor = null;
        $pembayaran->bukti_pembayaran_id = null;
        $pembayaran->save();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
