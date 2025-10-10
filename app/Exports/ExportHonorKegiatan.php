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

class ExportHonorKegiatan extends StringValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    protected $kegiatan;

    /**
     * Kita menggunakan constructor untuk menerima objek Kegiatan
     * dari controller.
     */
    public function __construct(Kegiatan $kegiatan)
    {
        $this->kegiatan = $kegiatan;
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
            'NIP Lama',     // A
            'TanggalAwal',  // B
            'TanggalAkhir', // C
            'BulanMulai',   // D
            'BulanSelesai', // E
            'Volume',       // F
            'HargaSatuan'   // G
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
            '',
            '',
            '',
            '',
            $mitra->pivot->jumlah,
            $mitra->pivot->is_pml ? $this->kegiatan->honor_pengawasan : $this->kegiatan->honor_pencacahan,
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
