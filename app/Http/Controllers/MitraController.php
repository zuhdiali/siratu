<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;

class MitraController extends Controller
{
    public function index()
    {
        $mitraAktif = Mitra::where('flag', null)->count();
        $mitras = Mitra::orderBy('nama', 'asc')->get();
        foreach ($mitras as $mitra) {
            $kec_asal = $mitra->kec_asal;
            $mitra->kec_asal = $this->konversiKodeKec($kec_asal);
        }
        return view('mitra.index', ['mitras' => $mitras, 'mitraAktif' => $mitraAktif]);
    }

    public function create()
    {
        return view('mitra.create');
    }

    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
            'id_mitra' => 'required|numeric',
            'no_rek' => 'required|numeric',
            'kec_asal' => 'required',
        ]);

        $mitra = new Mitra;
        $mitra->nama = $request->nama;
        $mitra->id_mitra = $request->id_mitra;
        $mitra->no_rek = $request->no_rek;
        $mitra->kec_asal = $request->kec_asal;
        $mitra->save();

        if (!$mitra->wasRecentlyCreated) {
            return redirect()->route('mitra.create')->with('error', 'Gagal.');
        }

        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mitra = Mitra::find($id);
        return view('mitra.edit', ['mitra' => $mitra]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
            'id_mitra' => 'required|numeric|digits:18',
            'no_rek' => 'required|numeric',
            'kec_asal' => 'required',
            'flag' => 'required',
        ]);

        $mitra = Mitra::find($id);
        $mitra->nama = $request->nama;
        $mitra->id_mitra = $request->id_mitra;
        $mitra->no_rek = $request->no_rek;
        $mitra->kec_asal = $request->kec_asal;
        if ($request->flag == "Aktif") {
            $mitra->flag = null;
        } else {
            $mitra->flag = $request->flag;
        }
        $mitra->save();

        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil diubah.');
    }

    public function destroy($id)
    {
        // $penilaians = Penilaian::where('mitra_id', $id)->get();
        // $penilaians->each->delete();
        $mitra = Mitra::find($id);
        $mitra->flag = "Dihapus";
        $mitra->save();
        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil ditandai sebagai tidak aktif.');
    }

    private function konversiKodeKec($id)
    {
        $kec = "";
        switch ($id) {
            case '010':
                $kec = "Teupah Selatan";
                break;
            case '020':
                $kec = "Simeulue Timur";
                break;
            case '021':
                $kec = "Teupah Barat";
                break;
            case '022':
                $kec = "Teupah Tengah";
                break;
            case '030':
                $kec = "Simeulue Tengah";
                break;
            case '031':
                $kec = "Teluk Dalam";
                break;
            case '032':
                $kec = "Simeulue Cut";
                break;
            case '040':
                $kec = "Salang";
                break;
            case '050':
                $kec = "Simeulue Barat";
                break;
            case '051':
                $kec = "Alafan";
                break;
            default:
                $kec = "";
                break;
        }
        return $kec;
    }
}