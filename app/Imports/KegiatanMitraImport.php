<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanMitraImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $this->data = $rows->toArray();
    }

    public function getData()
    {
        return $this->data;
    }
}
