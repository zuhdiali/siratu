<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\KamusSurat;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
use App\Models\FotoSuratMasuk;

class SuratController extends Controller
{
    private function tambahInformasiSurat($surats)
    {
        foreach ($surats as $surat) {
            $surat->pembuat_surat = Pegawai::find($surat->id_pembuat_surat);
            $surat->kegiatan = Kegiatan::find($surat->id_kegiatan);
        }
        return $surats;
    }

    public function tugas()
    {
        $surats = Surat::where('jenis_surat', 'tugas')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.tugas', ['surats' => $surats]);
    }

    public function permintaan()
    {
        $surats = Surat::where('jenis_surat', 'permintaan')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.permintaan', ['surats' => $surats]);
    }

    public function masuk()
    {
        $surats = Surat::where('jenis_surat', 'masuk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        // $surats = $this->tambahInformasiSurat($surats);
        return view('surat.masuk', ['surats' => $surats]);
    }

    public function rincianSuratMasuk($id)
    {
        $surat = Surat::find($id);
        $file = './uploads/surat/' . $surat->file;
        // $surat->foto_surat_masuk = FotoSuratMasuk::where('id_surat', $id)->get();
        // return view('surat.rincian-surat-masuk', ['surat' => $surat]);
        return response()->file($file);
    }

    public function keluar()
    {
        $surats = Surat::where('jenis_surat', 'keluar')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.keluar', ['surats' => $surats]);
    }

    public function spd()
    {
        $surats = Surat::where('jenis_surat', 'spd')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            $surat->pegawai = Pegawai::find($surat->pegawai_yang_bertugas);
        }
        return view('surat.spd', ['surats' => $surats]);
    }

    public function sk()
    {
        $surats = Surat::where('jenis_surat', 'sk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.sk', ['surats' => $surats]);
    }

    public function spk()
    {
        $surats = Surat::where('jenis_surat', 'spk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.spk', ['surats' => $surats]);
    }

    public function create($jenis)
    {
        // BARIS KODE DI BAWAH INI HARUS DIGANTI
        $kegiatans = Kegiatan::all();
        // BARIS KODE DI ATAS INI HARUS DIGANTI
        // dd($kegiatans);
        $noTerakhir = $this->getNoSuratTerakhir($jenis);
        $pegawais = Pegawai::where('flag', null)->get();
        $opsiSuratAwal = KamusSurat::where('tim', '11012')->get();
        $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
        $kamusSuratTeknis = KamusSurat::where('tim', '11012')->orderBy('kode_surat_gabungan', 'desc')->get();
        return view('surat.create', compact('jenis', 'noTerakhir', 'kamusSuratUmum', 'kamusSuratTeknis', 'kegiatans', 'opsiSuratAwal', 'pegawais'));
    }

    public function store(Request $request, $jenis)
    {
        if ($jenis != 'masuk') {
            $request->validate([
                'id_kegiatan' => 'required',
                'tim' => 'required',
                'kode' => 'required',
                'perihal' => 'required',
            ]);
            $kegiatan = Kegiatan::find($request->id_kegiatan);
            if ($kegiatan->honor_pengawasan == null || $kegiatan->honor_pencacahan == null) {
                return redirect()->back()->with('error', 'Kegiatan yang dipilih belum memiliki honor pengawasan atau pencacahan.');
            } else {
                if ($kegiatan->mitra->count() == 0) {
                    return redirect()->back()->with('error', 'Kegiatan yang dipilih belum memiliki mitra.');
                } else {
                    foreach ($kegiatan->mitra as $mitra) {
                        if ($mitra->pivot->estimasi_honor == null) {
                            return redirect()->back()->with('error', 'Ada mitra yang belum memiliki estimasi honor dari kegiatan yang dipilih.');
                        }
                    }
                }
            }
        } else {
            $request->validate([
                'dinas_surat_masuk' => 'required',
                'no_surat_masuk' => 'required',
                'file' => 'required|mimes:pdf',
            ]);
            // $totalFoto = count($request->file('files'));
            // for ($i = 0; $i < $totalFoto; $i++) {
            //     $request->validate([
            //         'file.' . $i => 'mimes:png,jpg,jpeg,webp,svg',
            //     ]);
            // }
        }
        // dd($request->all());
        if ($jenis == 'spd') {
            $request->validate([
                'tgl_awal_kegiatan' => 'required|date',
                'tgl_akhir_kegiatan' => 'required|date',
                'pegawai_yang_bertugas' => 'required',
            ]);
        }

        $surat = new Surat();
        $surat->jenis_surat = $jenis;
        $surat->perihal = $request->perihal;

        if ($jenis == 'spd') {
            $surat->tgl_awal_kegiatan = $request->tgl_awal_kegiatan;
            $surat->tgl_akhir_kegiatan = $request->tgl_akhir_kegiatan;
            $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
        }

        if ($jenis != 'masuk') {
            $noTerakhir = $this->getNoSuratTerakhir($jenis);
            $surat->nomor_surat = $this->generateNomorSurat($request->tim, $request->kode, $jenis, $noTerakhir);
            $surat->no_terakhir = $noTerakhir + 1;
            $surat->id_kegiatan = $request->id_kegiatan;
            // YANG DI BAWAH INI HARUS DIGANTI
            $surat->id_pembuat_surat = Auth::user()->id;
            // $surat->id_pembuat_surat = 16; // ganti dengan id user yang login
            // YANG DI ATAS INI HARUS DIGANTI
            $surat->save();
        } else {
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->no_surat_masuk = $request->no_surat_masuk;
            $surat->save();
            // if ($request->has('files')) {
            //     $i = 0;
            //     foreach ($request->file('files') as $file) {
            //         $extension = $file->getClientOriginalExtension();
            //         $filename = date('Y-m-d') . '_' . time() . '_' . $i . '.' . $extension;
            //         $path = 'uploads/surat/';
            //         $file->move($path, $filename);
            //         $fotoSuratMasuk = new FotoSuratMasuk();
            //         $fotoSuratMasuk->id_surat = $surat->id;
            //         $fotoSuratMasuk->filename = $filename;
            //         $fotoSuratMasuk->save();
            //         $i++;
            //     }
            // }
            if ($request->has('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = date('Y-m-d') . '_' . time() . '.' . $extension;
                $path = 'uploads/surat/';
                $file->move($path, $filename);
                $surat->file = $filename;
                $surat->save();
            }
        }




        // YANG DI BAWAH INI HARUS DIGANTI
        // $surat->id_pembuat_surat = Auth::user()->id;
        // $surat->id_pembuat_surat = 16; // ganti dengan id user yang login
        // YANG DI ATAS INI HARUS DIGANTI



        return redirect()->route('surat.' . $jenis)->with('success', 'Surat berhasil dibuat.');
    }

    public function edit($jenis, $id)
    {
        // BARIS KODE DI BAWAH INI HARUS DIGANTI
        $kegiatans = Kegiatan::all();
        // BARIS KODE DI ATAS INI HARUS DIGANTI

        $surat = Surat::find($id);
        $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
        $pegawais = Pegawai::where('flag', null)->get();

        $pecahanSurat = explode("/", $surat->nomor_surat);
        $surat->tim = $pecahanSurat[1];
        $surat->kode_surat = $pecahanSurat[2];
        $opsiSuratAwal = KamusSurat::where('tim', $surat->tim)->get();
        $kamusSuratTeknis = KamusSurat::where('tim', '11012')->orderBy('kode_surat_gabungan', 'desc')->get();
        $noTerakhir = $surat->no_terakhir;
        return view('surat.edit', [
            'surat' => $surat,
            'kamusSuratUmum' => $kamusSuratUmum,
            'kamusSuratTeknis' => $kamusSuratTeknis,
            'jenis' => $jenis,
            'pecahanSurat' => $pecahanSurat,
            'noTerakhir' => $noTerakhir,
            'opsiSuratAwal' => $opsiSuratAwal,
            'kegiatans' => $kegiatans,
            'pegawais' => $pegawais,
        ]);
    }

    public function update(Request $request, $jenis, $id)
    {
        // dd($request->all());
        $request->validate([
            'id_kegiatan' => 'required',
            'tim' => 'required',
            'kode' => 'required',
            'perihal' => 'required',
        ]);

        $surat = Surat::find($id);

        if ($jenis == 'masuk') {
            $request->validate([
                'dinas_surat_masuk' => 'required',
                'no_surat_masuk' => 'required',
                'file' => 'nullable|mimes:png,jpg,jpeg,webp,svg',
            ]);
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->no_surat_masuk = $request->no_surat_masuk;
            if ($request->has('file')) {
                // dd($request->all());
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();

                $filename = date('Y-m-d') . '_' . time() . '_' . '.' . $extension;

                $path = 'uploads/surat/';
                $file->move($path, $filename);
                $surat->file = $filename;
            }
        } else if ($jenis == 'spd') {
            $request->validate([
                'tgl_awal_kegiatan' => 'required|date',
                'tgl_akhir_kegiatan' => 'required|date',
                'pegawai_yang_bertugas' => 'required',
            ]);
            $surat->tgl_awal_kegiatan = $request->tgl_awal_kegiatan;
            $surat->tgl_akhir_kegiatan = $request->tgl_akhir_kegiatan;
            $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
        }
        $noTerakhir = $surat->no_terakhir;
        $surat->nomor_surat = $this->generateNomorSurat($request->tim, $request->kode, $jenis, $noTerakhir - 1);
        $surat->id_kegiatan = $request->id_kegiatan;
        $surat->perihal = $request->perihal;
        $surat->save();

        return redirect()->route('surat.' . $jenis)->with('success', 'Surat berhasil diubah.');
    }

    public function destroy($id)
    {
        // tambahkan validasi jik yang login bukan admin maka tidak bisa menghapus
        if (Auth::user()->role != 'Admin') {
            return redirect()->back()->with('error', 'Silakan hubungi pegawai TU untuk menghapus nomor surat.');
        }
        $surat = Surat::find($id);
        $surat->flag = 'Dihapus';
        $surat->save();
        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }

    public function getKodeSurat($tim)
    {
        if ($tim == "11011") {
            $kodeSurat = KamusSurat::where('tim', $tim)->get();
            return response()->json($kodeSurat);
        } else {
            $kodeSurat = KamusSurat::where('tim', $tim)->get();
        }
        return response()->json($kodeSurat);
    }

    private function getNoSuratTerakhir($jenis)
    {
        $suratTerakhir = Surat::where('jenis_surat', $jenis)->orderBy('no_terakhir', 'desc')->first();
        if ($suratTerakhir == null) {
            $noTerakhir = 0;
        } else {
            $noTerakhir = $suratTerakhir->no_terakhir;
        }
        return $noTerakhir;
    }

    private function generateNomorSurat($tim, $kode, $jenis, $noTerakhir)
    {
        $noSurat = "";
        if ($jenis == "spd") {
            $noSurat =  str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT) . "/" . $tim . "/" . $kode . "/" . date("m") . "/" . date("Y");
        } else {
            $noSurat = "B-" . str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT) . "/" . $tim . "/" . $kode . "/" . date("m") . "/" . date("Y");
        }
        return $noSurat;
    }
}
