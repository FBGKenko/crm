<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importAdapterExcel implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return $collection;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
