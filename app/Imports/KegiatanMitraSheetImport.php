<?php

namespace App\Imports;

// Define your sheet import logic in a separate class
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanMitraSheetImport implements ToCollection, WithHeadingRow
{
    private $data = [];

    public function collection(Collection $rows)
    {
        $this->data = $rows->toArray();
    }

    public function getData()
    {
        return $this->data;
    }
}
