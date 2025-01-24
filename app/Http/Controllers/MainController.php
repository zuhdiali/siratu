<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

use App\Models\KegiatanMitra;
use App\Models\KegiatanPegawai;
use App\Models\Pembayaran;
use App\Models\Mitra;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // $bulan = $request->bulan ?? date('m');
        $tahun = date('Y');

        $mitraAdaHonor = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_mitras.kegiatan_id') as total_kegiatan"), DB::raw("SUM(estimasi_honor) as total_estimasi_honor"), DB::raw("SUM(honor) as total_honor"))
            ->leftJoin('kegiatan_mitras', 'mitras.id', '=', 'kegiatan_mitras.mitra_id')
            ->leftJoin('kegiatans', 'kegiatan_mitras.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('YEAR(kegiatans.tgl_selesai) = ' . $tahun)
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->get();

        $mitras = Mitra::where('flag', null)->get();
        $mitraAktif = $mitras->count();
        $mitraAktif--; // mengurangi 1 karena ada mitra bayangan


        foreach ($mitraAdaHonor as $m) {
            $m->kec_asal = $this->konversiKodeKec($m->kec_asal);
            if (str_contains($m->nama, 'bayangan')) {
                $mitraAdaHonor = $mitraAdaHonor->reject(function ($item) use ($m) {
                    return $item->mitra_id == $m->mitra_id;
                });
            }
        }
        foreach ($mitras as $mitra) {
            if (!in_array($mitra->nama, array_column($mitraAdaHonor->toArray(), 'nama')) && !str_contains($mitra->nama, 'bayangan')) {
                $mitra->total_kegiatan = 0;
                $mitra->total_estimasi_honor = 0;
                $mitra->total_honor = 0;
                $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
                $mitraAdaHonor->push($mitra);
            }
        }
        // dd($mitraAdaHonor[17]);
        return view('dashboard.mitra', compact('mitraAktif', 'mitraAdaHonor'));
    }

    public function dashboardBulanan(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $mitraAdaHonor = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_mitras.kegiatan_id') as total_kegiatan"), DB::raw("SUM(estimasi_honor) as total_estimasi_honor"), DB::raw("SUM(honor) as total_honor"))
            ->leftJoin('kegiatan_mitras', 'mitras.id', '=', 'kegiatan_mitras.mitra_id')
            ->leftJoin('kegiatans', 'kegiatan_mitras.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('MONTH(kegiatans.tgl_selesai) = ' . $bulan)
            ->whereRaw('YEAR(kegiatans.tgl_selesai) = ' . $tahun)
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->get();

        foreach ($mitraAdaHonor as $m) {
            $m->kec_asal = $this->konversiKodeKec($m->kec_asal);
            $m->total_honor = $m->total_honor ?? 0;
            $m->total_estimasi_honor = $m->total_estimasi_honor ?? 0;
            $m->total_kegiatan = $m->total_kegiatan ?? 0;
            if (str_contains($m->nama, 'bayangan')) {
                $mitraAdaHonor = $mitraAdaHonor->reject(function ($item) use ($m) {
                    return $item->mitra_id == $m->mitra_id;
                });
            }
        }

        $mitras = Mitra::where('flag', null)->get();
        foreach ($mitras as $mitra) {
            if (!in_array($mitra->nama, array_column($mitraAdaHonor->toArray(), 'nama')) && !str_contains($mitra->nama, 'bayangan')) {
                $mitra->total_kegiatan = 0;
                $mitra->total_estimasi_honor = 0;
                $mitra->total_honor = 0;
                $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
                $mitraAdaHonor->push($mitra);
            }
        }

        if (is_object($mitras)) {
            $dummy = $mitraAdaHonor;
            $mitraAdaHonor = [];
            foreach ($dummy as $d) {
                array_push($mitraAdaHonor, $d);
            }
        }

        return response()->json($mitraAdaHonor);
    }

    public static function jumlahHonorMitra($id_mitra, $bulan, $tahun)
    {
        // $request->validate([
        //     'id_mitra' => 'required',
        //     'bulan' => 'required',
        //     'tahun' => 'required',
        // ]);

        $honorMitra = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_mitras.kegiatan_id') as total_kegiatan"), DB::raw("SUM(estimasi_honor) as total_estimasi_honor"), DB::raw("SUM(honor) as total_honor"))
            ->where('mitras.id', $id_mitra)
            ->leftJoin('kegiatan_mitras', 'mitras.id', '=', 'kegiatan_mitras.mitra_id')
            ->leftJoin('kegiatans', 'kegiatan_mitras.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('MONTH(kegiatans.tgl_selesai) = ' . $bulan)
            ->whereRaw('YEAR(kegiatans.tgl_selesai) = ' . $tahun)
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->first();
        return $honorMitra;
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
