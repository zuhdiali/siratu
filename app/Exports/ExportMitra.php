<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportMitra implements WithMultipleSheets
{

    /**
     * Method ini akan membuat array berisi class-class
     * yang akan menjadi sheet di file Excel.
     */
    public function sheets(): array
    {
        return [
            // Sheet 1
            new TemplateSheetExport(),

            // Sheet 2
            new AllMitraSheetExport(),
        ];
    }
}
