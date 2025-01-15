<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;


class PegawaiController extends Controller
{
    public function index()
    {
        $pegawaiAktif = Pegawai::where('flag', null)->count();
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        foreach ($pegawais as $pegawai) {
            $kec_asal = $pegawai->kec_asal;
            $pegawai->kec_asal = $this->konversiKodeKec($kec_asal);
            $pegawai->nama_tim = $this->konversiTim($pegawai->tim);
        }
        return view('pegawai.index', ['pegawais' => $pegawais, 'pegawaiAktif' => $pegawaiAktif]);
    }

    public function user()
    {
        $totalUser = Pegawai::count();
        $pegwais = Pegawai::all();
        return view('user.index', ['users' => $pegwais, 'totalUser' => $totalUser]);
    }

    public function create()
    {
        // $ruangans = Ruangan::orderBy('nama', 'asc')->get();
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
            'nip' => 'required|numeric|digits:18',
            'no_rek' => 'required|numeric',
            'kec_asal' => 'required',
            'tim' => 'required',
            'username' => 'required|unique:pegawais',
            'role' => 'required',
            'password' => 'required',
            'confirm-password' => 'required|same:password',
            'path' => 'required',
        ]);

        $pegawai = new Pegawai;
        $pegawai->nama = $request->nama;
        $pegawai->nip = $request->nip;
        $pegawai->no_rek = $request->no_rek;
        $pegawai->kec_asal = $request->kec_asal;
        $pegawai->username = $request->username;
        $pegawai->role = $request->role;
        $pegawai->password = Hash::make($request->password);
        $pegawai->save();

        if (!$pegawai->wasRecentlyCreated) {
            return redirect()->route('pegawai.create')->with('error', 'Gagal.');
        }

        if (str_contains($request->path, 'user')) {
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } else {
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
        }
    }

    public function edit($id)
    {
        $pegawai = Pegawai::find($id);
        return view('pegawai.edit', ['pegawai' => $pegawai]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:50',
            'nip' => 'required|numeric|digits:18',
            'no_rek' => 'required|numeric',
            'kec_asal' => 'required',
            'flag' => 'required',
            'path' => 'required',
            'tim' => 'required',
            'role' => 'required',
            'password' => 'nullable',
            'confirm-password' => 'same:password',
        ]);

        $pegawai = Pegawai::find($id);
        $pegawai->nama = $request->nama;
        $pegawai->nip = $request->nip;
        $pegawai->no_rek = $request->no_rek;
        $pegawai->kec_asal = $request->kec_asal;
        $pegawai->tim = $request->tim;
        if ($id != 0) {
            $pegawai->username = $request->username;
        }

        if ($request->password != null) {
            $pegawai->password = Hash::make($request->password);
        }

        $pegawai->role = $request->role;
        if ($request->flag == "Aktif") {
            $pegawai->flag = null;
        } else {
            $pegawai->flag = $request->flag;
        }
        $pegawai->save();

        if (str_contains($request->path, 'user')) {
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil diubah.');
        } else {
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diubah.');
        }
    }

    public function destroy($id)
    {
        // $penilaians = Penilaian::where('pegawai_id', $id)->get();
        // $penilaians->each->delete();
        $pegawai = Pegawai::find($id);
        $pegawai->flag = "Dihapus";
        $pegawai->save();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditandai sebagai tidak aktif.');
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

    private function konversiTim($kodeTim)
    {
        $tim = "";
        switch ($kodeTim) {
            case '11011':
                $tim = "Umum";
                break;
            case '11012':
                $tim = "Statistik Sosial";
                break;
            case '11013':
                $tim = "Statistik Ekonomi Produksi";
                break;
            case '11014':
                $tim = "Statistik Ekonomi Distribusi";
                break;
            case '11015':
                $tim = "Neraca dan Analisis Statistik";
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
                # code...
                break;
        }
        return $tim;
    }
}
