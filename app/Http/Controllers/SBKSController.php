<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SBKS;
use App\Models\Pegawai;
use Carbon\Carbon;

class SBKSController extends Controller
{
    public function index()
    {
        $sbks = SBKS::all();
        $sbks->map(function ($item) {
            $item->nama_tim = $this->konversiTim($item->tim);
            $item->pjk == null ? $item->nama_pjk = '-' : $item->nama_pjk = Pegawai::find(intval($item->pjk))->nama;
            return $item;
        });
        // dd($sbks[0]);
        return view('sbks.index', compact('sbks'));
    }

    public function create()
    {
        return view('sbks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tugas' => 'required',
            'satuan' => 'required',
            'honor_per_satuan' => 'required',
            'tim' => 'required',
            'ada_di_simeulue' => 'required',
            'pjk' => 'required',
            'singkatan_resmi' => 'required',
        ]);

        SBKS::create($request->all());

        return redirect()->route('sbks.index')
            ->with('success', 'SBKS berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sbks = SBKS::find($id);
        return view('sbks.edit', compact('sbks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tugas' => 'required',
            'satuan' => 'required',
            'honor_per_satuan' => 'required',
            'tim' => 'required',
            'ada_di_simeulue' => 'required',
            'pjk' => 'required',
            'singkatan_resmi' => 'required',
        ]);

        $sbks = SBKS::find($id);
        $sbks->update($request->all());

        return redirect()->route('sbks.index')
            ->with('success', 'SBKS berhasil diperbarui');
    }

    public function destroy($id)
    {
        SBKS::destroy($id);
        return redirect()->route('sbks.index')
            ->with('success', 'SBKS berhasil dihapus');
    }

    public function getHonor(Request $request)
    {
        $is_pendataan = false;
        $request->jenis_kegiatan == 'pendataan' ? $is_pendataan = true : $is_pendataan = false;
        $kegiatan_pendataan_atau_pengolahan = null;
        $kegiatan_pengawasan = null;
        if ($is_pendataan) {
            $kegiatan_pendataan_atau_pengolahan = SBKS::where('tugas', 'PPL')->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%')->first();
            $kegiatan_pengawasan = SBKS::where('tugas', 'PML')->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%')->first();
        } else {
            $kegiatan_pendataan_atau_pengolahan = SBKS::where('tugas', 'Pengolahan')->where('nama_kegiatan', $request->nama_kegiatan)->first();
        }
        // return response()->json($kegiatan_pendataan_atau_pengolahan);
        $response = new \stdClass();
        $response->nama_kegiatan = $request->nama_kegiatan . " " . Carbon::now()->locale('id')->translatedFormat('F') . " " . date('Y');

        $response->honor_pendataan_atau_pengolahan = $kegiatan_pendataan_atau_pengolahan->honor_per_satuan;
        $response->satuan_honor_pendataan_atau_pengolahan = $kegiatan_pendataan_atau_pengolahan->satuan;
        $response->tim = $kegiatan_pendataan_atau_pengolahan->tim ?? '-';
        // $response->tim = $this->konversiTim($kegiatan_pengawasan->tim);
        $response->id_pjk = $kegiatan_pendataan_atau_pengolahan->pjk;
        if ($kegiatan_pengawasan != null) {
            $response->honor_pengawasan = $kegiatan_pengawasan->honor_per_satuan;
            $response->satuan_honor_pengawasan = $kegiatan_pengawasan->satuan;
        } else {
            // jika kegiatan pengoalahan tidak ada pengawasan
            $response->honor_pengawasan = 1;
            $response->satuan_honor_pengawasan = 'Dokumen';
        }

        // $response->pengawasan = $kegiatan_pengawasan;

        return response()->json($response);
    }

    private function konversiTim($kodeTim)
    {
        $tim = "";
        switch ($kodeTim) {
            case '11011':
                $tim = "Umum";
                break;
            case '11012':
                $tim = "Sosial";
                break;
            case '11013':
                $tim = "Produksi";
                break;
            case '11014':
                $tim = "Distribusi";
                break;
            case '11015':
                $tim = "Neraca";
                break;
            case '11016':
                $tim = "TI dan Pengolahan";
                break;
            case '11017':
                $tim = 'Diseminasi';
                break;
            case '11018':
                $tim = 'PSS';
                break;
            default:
                $tim = '-';
                break;
        }
        return $tim;
    }
}
