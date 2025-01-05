<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KegiatanMitra;
use App\Models\KegiatanPegawai;
use App\Models\Pembayaran;
use App\Models\Mitra;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return back()->withErrors([
            'username' => 'Username atau kata sandi salah.',
            'password' => 'Username atau kata sandi salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }

    public function dashboard()
    {
        $mitraAktif = Mitra::where('flag', null)->count();

        // $mitras = Mitra::where('flag', null)->get();
        // foreach ($mitras as $mitra) {
        //     $kec_asal = $mitra->kec_asal;
        //     $mitra->kec_asal = $this->konversiKodeKec($kec_asal);
        //     $mitra->honor = $mitra->kegiatan()->sum('honor');
        //     $mitra->estimasi_honor = $mitra->kegiatan()->sum('estimasi_honor');
        // }

        $bulan = 11;
        $tahun = 2024;
        $mitras = Mitra::with(['kegiatan' => function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_selesai', $bulan)
                ->whereYear('tgl_selesai', $tahun);
        }])->where('flag', null)->get();
        $test = [];
        foreach ($mitras as $mitra) {
            $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
            $mitra->jumlah_kegiatan = $mitra->kegiatan->count();
            $mitra->estimasi_honor = $mitra->kegiatan->sum('estimasi_honor');
        }
        return view('dashboard.mitra', compact('mitraAktif', 'mitras'));
    }

    public function dashboardBulanan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $mitras = Mitra::with(['kegiatan' => function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_selesai', $bulan)
                ->whereYear('tgl_selesai', $tahun);
        }])->where('flag', null)->get();
        $test = [];
        foreach ($mitras as $mitra) {
            $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
            $mitra->jumlah_kegiatan = $mitra->kegiatan->count();
            $mitra->estimasi_honor = $mitra->kegiatan->sum('estimasi_honor');
        }

        return response()->json($mitras);
    }

    public function mitraKegiatanBelumDibayar($id_kegiatan)
    {
        $mitra = KegiatanMitra::where('kegiatan_id', $id_kegiatan)->where('honor', null)->get();
        foreach ($mitra as $m) {
            $m->mitra = Mitra::find($m->mitra_id);
        }
        return response()->json($mitra);
    }

    public function pegawaiKegiatanBelumDibayar($id_kegiatan)
    {
        $pegawai = KegiatanPegawai::where('kegiatan_id', $id_kegiatan)->where('translok',  null)->get();
        foreach ($pegawai as $m) {
            $m->pegawai = Pegawai::find($m->pegawai_id);
        }
        return response()->json($pegawai);
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
