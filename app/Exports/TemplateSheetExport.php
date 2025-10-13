<?php

namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import


class TemplateSheetExport extends StringValueBinder implements WithHeadings, WithTitle
{

    public function headings(): array
    {
        return [
            'mitra_id',
            'nama',
            'jumlah',
            'is_pml'
        ];
    }


    public function title(): string
    {
        return 'Template upload';
    }
}
