<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Mitra;
use App\Models\Surat;
use App\Models\SBKS;
use Carbon\Carbon;
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
            $kegiatan->namaTim = $this->konversiTim($kegiatan->tim);
        }
        return view('kegiatan.index', ['kegiatans' => $kegiatans, 'kegiatanTahunIni' => $kegiatanTahunIni]);
    }

    public function create()
    {
        $mitras = Mitra::where('flag', null)->orderBy('nama', 'asc')->get();
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        $sbks = SBKS::select(['nama_kegiatan', 'singkatan_resmi'])->where('ada_di_simeulue', 1)->distinct('nama_kegiatan')->orderBy('nama_kegiatan', 'asc')->get();
        foreach ($sbks as $item) {
            if ($item->singkatan_resmi) {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan . ' (' . $item->singkatan_resmi . ')';
            } else {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan;
            }
        }
        // dd($sbks);
        // return view('kegiatan.create', ['kegiatans' => $kegiatans, 'mitras' => $mitras]);
        return view('kegiatan.create', ['pegawais' => $pegawais, 'mitras' => $mitras, 'sbks' => $sbks]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:254',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'honor_pengawasan' => 'nullable|numeric',
            'honor_pencacahan' => 'nullable|numeric',
            'id_pjk' => 'required',
            'tim' => 'required',
        ]);

        //untuk menandai apakah ada mitra yang melebihi batas honor
        $mitraMelebihiHonor = $this->validasiHonorMitra($request->mitra, $request->tgl_selesai);
        if (count($mitraMelebihiHonor) > 0) {
            return redirect()->route('kegiatan.create')->with('error', 'Mitra ' . implode(",", $mitraMelebihiHonor) . ' yang melebihi batas honor.')->withInput();
        }
        $kegiatan = new Kegiatan;
        $kegiatan->nama = $request->nama;
        $kegiatan->tgl_mulai = $request->tgl_mulai;
        $kegiatan->tgl_selesai = $request->tgl_selesai;
        $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
        $kegiatan->honor_pengawasan = $request->honor_pengawasan;
        $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
        $kegiatan->honor_pencacahan = $request->honor_pencacahan;
        $kegiatan->id_pjk = $request->id_pjk;
        $kegiatan->tim = $request->tim;
        $kegiatan->progress = $request->progress;
        $kegiatan->save();
        if ($request->pegawai != null) {
            $kegiatan->pegawai()->attach($request->pegawai);
        }
        if ($request->mitra != null) {
            $kegiatan->mitra()->attach($request->mitra);
        }


        if (!$kegiatan->wasRecentlyCreated) {
            return redirect()->route('kegiatan.create')->with('error', 'Gagal.');
        }

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->id])->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        $mitras = Mitra::where('flag', null)->orderBy('nama', 'asc')->get();
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        return view('kegiatan.edit', ['kegiatan' => $kegiatan, 'pegawais' => $pegawais, 'mitras' => $mitras]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validate the request...
        $request->validate([
            'nama' => 'required|max:254',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
            // 'satuan_honor' => 'required',
            'honor_pengawasan' => 'nullable|numeric',
            'honor_pencacahan' => 'nullable|numeric',
            'id_pjk' => 'required',
            'tim' => 'required',
        ]);

        try {
            $kegiatan = Kegiatan::find($id);
            $kegiatan->nama = $request->nama;
            $kegiatan->tgl_mulai = $request->tgl_mulai;
            $kegiatan->tgl_selesai = $request->tgl_selesai;
            $honor_pengawasan_sebelumnya = $kegiatan->honor_pengawasan;
            $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
            $kegiatan->honor_pengawasan = $request->honor_pengawasan;
            $honor_pencacahan_sebelumnya = $kegiatan->honor_pencacahan;
            $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
            $kegiatan->honor_pencacahan = $request->honor_pencacahan;
            $kegiatan->id_pjk = $request->id_pjk;
            $kegiatan->tim = $request->tim;
            $kegiatan->progress = $request->progress;
            $kegiatan->pegawai()->sync($request->pegawai);
            $kegiatan->mitra()->sync($request->mitra);
            $kegiatan->save();

            // Flag apakah ada perubahan honor
            $flag = false;
            if ($honor_pencacahan_sebelumnya != $request->honor_pencacahan || $honor_pengawasan_sebelumnya != $request->honor_pengawasan) {
                $flag = true;
            }
            foreach ($kegiatan->mitra as $mitra) {
                // Isi default tanggal realisasi jika belum diisi
                // if ($mitra->pivot->tgl_realisasi == null) {
                //     $kegiatan->mitra()->updateExistingPivot($mitra->id, ['tgl_realisasi' => $kegiatan->tgl_selesai]);
                // }

                // Hitung estimasi honor
                if ($flag) {
                    $estimasi_honor = 0;
                    $is_pml = 0;
                    if ($mitra->pivot->is_pml == 1) {
                        $estimasi_honor = $request->honor_pengawasan * $mitra->pivot->jumlah;
                        $is_pml = 1;
                    } else {
                        $estimasi_honor = $request->honor_pencacahan * $mitra->pivot->jumlah;
                    }
                    $kegiatan->mitra()->updateExistingPivot($mitra->id, ['estimasi_honor' => $estimasi_honor, 'is_pml' => $is_pml]);
                }
            }
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
        // foreach ($kegiatan->mitra as $mitra) {
        //     $mitra->pjk = Pegawai::find($mitra->id_pjk);
        // }


        $currentMonth = Carbon::parse($kegiatan->tgl_selesai)->month;
        $currentYear = Carbon::parse($kegiatan->tgl_selesai)->year;

        // Total estimasi honor dari kegiatan lain untuk setiap mitra, dengan filter bulan dan tahun ini
        $mitraEstimasiHonors = [];
        foreach ($kegiatan->mitra as $mitra) {
            $estimasiDariLainnya = $mitra->kegiatan()
                ->join('kegiatans as k2', 'k2.id', '=', 'kegiatan_mitras.kegiatan_id') // Alias tabel kegiatans ke 'k2'
                ->where('kegiatan_mitras.kegiatan_id', '<>', $id) // Filter kegiatan lain
                ->whereMonth('k2.tgl_selesai', $currentMonth) // Filter bulan sekarang (kolom 'tgl_selesai' dari alias 'k2')
                ->whereYear('k2.tgl_selesai', $currentYear) // Filter tahun sekarang
                ->selectRaw('SUM(CASE 
                                WHEN kegiatan_mitras.honor IS NOT NULL THEN kegiatan_mitras.honor 
                                ELSE kegiatan_mitras.estimasi_honor 
                            END) as total_honor') // Pilih honor jika ada, estimasi_honor jika tidak ada
                ->value('total_honor'); // Ambil nilai total honor

            $mitraEstimasiHonors[] = [
                'id' => $mitra->id,
                'nama' => $mitra->nama,
                'estimasi_honor_kegiatan_ini' => $mitra->pivot->honor ?? $mitra->pivot->estimasi_honor,
                'estimasi_total_honor' => ($mitra->pivot->honor ?? $mitra->pivot->estimasi_honor) + $estimasiDariLainnya,
            ];
        }
        $kegiatan->pjk = Pegawai::find($kegiatan->id_pjk);
        return view('kegiatan.show', ['kegiatan' => $kegiatan]);
    }


    public function destroy($id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan->pegawai->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada pegawai terlibat.');
        }
        if ($kegiatan->mitra->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada mitra terlibat.');
        }
        $surats = Surat::where('id_kegiatan', $id)->get();
        if ($surats->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena ada nomor surat yang berkaitan.');
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

    // INI TIDAK DIPAKAI
    public function updateTerlibat(Request $request, $id)
    {
        // dd($request->all());
        $kegiatan = Kegiatan::find($id);
        $kegiatan->pegawai()->sync($request->pegawai);
        $kegiatan->mitra()->sync($request->mitra);
        // foreach ($kegiatan->mitra as $mitra) {
        //     if ($mitra->pivot->tgl_realisasi == null) {
        //         $kegiatan->mitra()->updateExistingPivot($mitra->id, ['tgl_realisasi' => $kegiatan->tgl_selesai]);
        //     }
        // }
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan terlibat berhasil diubah.');
    }

    public function estimasiHonor($id)
    {
        $kegiatan = Kegiatan::find($id);
        return view('kegiatan.estimasi-honor', ['kegiatan' => $kegiatan]);
    }

    public function estimasiHonorPost(Request $request, $id)
    {
        // dd($request->all());
        $kegiatan = Kegiatan::find($id);
        // jika tidak ada pml
        if (!$request->has('is_pml')) {
            $request->merge(['is_pml' => []]);
        }
        $estimasi_honor = 0;
        $mitraYangPerluWarning = [];
        foreach ($request->jumlah as $key => $value) {
            // cek apakah PML
            in_array($key, array_keys($request->is_pml)) ? $is_pml = 1 : $is_pml = 0;

            // Hitung estimasi honor
            $is_pml == 1 ? $estimasi_honor = $value * $kegiatan->honor_pengawasan : $estimasi_honor = $value * $kegiatan->honor_pencacahan;

            // cek apakah melebihi batas honor
            if ($estimasi_honor > 3600100) {
                return redirect()->route('kegiatan.estimasi-honor', ['id' => $id])->with('error', 'Ada mitra yang melebihi batas honor.');
            }

            // hitung honor mitra bulan ini tanpa kegiatan yang dipilih
            $honorMitraBulanIni = self::jumlahHonorMitra($key, Carbon::parse($kegiatan->tgl_selesai)->month, Carbon::parse($kegiatan->tgl_selesai)->year, $id);

            // hitung honor mitra jika dengan kegiatan ini
            $honorMitraDenganKegiatanIni = self::jumlahHonorMitra($key, Carbon::parse($kegiatan->tgl_selesai)->month, Carbon::parse($kegiatan->tgl_selesai)->year);



            // cek apakah melebihi batas honor
            if ($honorMitraBulanIni != null) {

                // hitung honor mitra setelah perubahan
                $honorMitraSetelahPerubahan = $honorMitraBulanIni->total_estimasi_honor + $estimasi_honor;

                // if ($honorMitraBulanIni->total_estimasi_honor + $estimasi_honor > 3600100) {
                //     return redirect()->route('kegiatan.estimasi-honor', ['id' => $id])->with('error', 'Mitra ' . $honorMitraBulanIni->nama . ' akan melebihi batas honor jika mendata sebanyak ' . $value . '.');
                // }

                //cek apakah sedang berusaha mengurangi jumlah honor
                if ($honorMitraSetelahPerubahan > 3600100) {
                    if ($honorMitraDenganKegiatanIni->total_estimasi_honor > $honorMitraSetelahPerubahan) {
                        $mitraYangPerluWarning[] = $honorMitraBulanIni->nama;
                    } else {
                        return redirect()->route('kegiatan.estimasi-honor', ['id' => $id])->with('error', 'Mitra ' . $honorMitraBulanIni->nama . ' akan melebihi batas honor jika mendata sebanyak ' . $value . '.');
                    }
                }
            }

            // update jumlah
            $kegiatan->mitra()->updateExistingPivot($key, ['jumlah' => $value]);

            // update estimasi honor
            $kegiatan->mitra()->updateExistingPivot($key, ['estimasi_honor' => $estimasi_honor]);

            // update is_pml
            $kegiatan->mitra()->updateExistingPivot($key, ['is_pml' => $is_pml]);
        }

        // update tgl_realisasi
        // foreach ($request->tgl_realisasi as $key => $value) {
        //     $kegiatan->mitra()->updateExistingPivot($key, ['tgl_realisasi' => $value]);
        // }

        if (count($mitraYangPerluWarning) > 0) {
            return redirect()->route('kegiatan.show', ['id' => $id])->with('warning', 'Pembaruan estimasi honor berhasil, namun mitra (' . implode(", ", $mitraYangPerluWarning) . ')  masih melebihi batas honor. Sebaiknya segera kurangi honor yang diterimanya.');
        }
        return redirect()->route('kegiatan.show', ['id' => $id])->with('success', 'Estimasi honor berhasil diperbarui.');
    }

    public function duplicate($id)
    {
        $kegiatan = Kegiatan::find($id);
        $kegiatanBaru = $kegiatan->replicate();
        $mitraMelebihiHonor = $this->validasiHonorMitra($kegiatan->mitra, $kegiatanBaru->tgl_selesai);
        if (count($mitraMelebihiHonor) > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Mitra (' . implode(",", $mitraMelebihiHonor) . ')  melebihi batas honor.')->withInput();
        }
        $kegiatanBaru->nama = '(Duplikat) ' . $kegiatan->nama;
        $kegiatanBaru->save();
        $kegiatanBaru->pegawai()->attach($kegiatan->pegawai);
        $kegiatanBaru->mitra()->attach($kegiatan->mitra);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diduplikasi.');
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
                # code...
                break;
        }
        return $tim;
    }

    public static function validasiHonorMitra($arrayMitra, $tgl_selesai_kegiatan)
    {
        $mitraMelebihiHonor = [];
        $id_mitra = "";
        if ($arrayMitra == null) {
            return $mitraMelebihiHonor;
        }
        foreach ($arrayMitra as $mitra) {
            if (gettype($mitra) == 'string') {
                $id_mitra = $mitra;
            } else {
                $id_mitra = $mitra->id;
            }
            $honorMitra = self::jumlahHonorMitra($id_mitra, Carbon::parse($tgl_selesai_kegiatan)->month, Carbon::parse($tgl_selesai_kegiatan)->year);
            if ($honorMitra == null) {
                continue;
            }
            if (intval($honorMitra->total_estimasi_honor) > 3600100) {
                array_push($mitraMelebihiHonor, $honorMitra->nama);
            }
        }
        return $mitraMelebihiHonor;
    }

    public static function jumlahHonorMitra($id_mitra, $bulan, $tahun, $idKegiatanPengecualian = null)
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
            ->when($idKegiatanPengecualian, function ($query) use ($idKegiatanPengecualian) {
                if ($idKegiatanPengecualian !== null) {
                    return $query->where('kegiatans.id', '<>', $idKegiatanPengecualian);
                }
            })
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->first();
        return $honorMitra;
    }
}
