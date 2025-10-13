<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KegiatanMitraImport implements WithMultipleSheets
{
    protected $sheetImport;

    public function __construct()
    {
        $this->sheetImport = new KegiatanMitraSheetImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->sheetImport, // Only process the first sheet (index 0)
        ];
    }

    public function getData()
    {
        return $this->sheetImport->getData();
    }
}
