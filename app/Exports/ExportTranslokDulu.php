<?php

namespace App\Exports;

use App\Models\Kegiatan; // <-- Penting: Import model Kegiatan
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Tiga library di bawah ini berguna untuk mengonversi NIK ke format teks, agar tidak menjadi seperti ini formatnya: 1,10907E+15
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder; // <-- 1. Import
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import

class ExportTranslok extends StringValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    protected $kegiatan;
    protected $tgl_mulai;
    protected $tgl_selesai;
    protected $tujuan;

    /**
     * Kita menggunakan constructor untuk menerima objek Kegiatan
     * dari controller.
     */
    public function __construct(Kegiatan $kegiatan, $tgl_mulai, $tgl_selesai, $tujuan)
    {
        $this->kegiatan = $kegiatan;
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->tujuan = $tujuan;
    }

    /**
     * Method ini akan mengambil data relasi mitra dari
     * objek kegiatan yang sudah kita simpan.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->kegiatan->mitra; // Ini akan mengembalikan collection Mitra yang berelasi
    }

    /**
     * Method ini untuk mendefinisikan judul setiap kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'NIP Lama',                 // A
            'Tujuan ke-',               // B
            'Asal',                     // C
            'Tujuan',                   // D
            'Berangkat (yyyy-mm-dd)',   // E
            'Pulang (yyyy-mm-dd)',      // F
        ];
    }

    /**
     * Method ini untuk memetakan/memformat data setiap baris.
     * Di sinilah kita akan mengakses data pivot.
     * @param mixed $mitra
     */
    public function map($mitra): array
    {
        return [
            $mitra->nik, // Asumsi di model Mitra ada kolom 'nama'
            '1101' . $this->tujuan,
            '1101' . $mitra->kec_asal,
            '1101' . $this->tujuan,
            $this->tgl_mulai,
            $this->tgl_selesai,
        ];
    }

    /**
     * Method baru untuk memformat kolom NIK sebagai Teks.
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
