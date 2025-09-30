<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MediaAfterImport implements FromCollection, WithHeadings
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function headings(): array
    {
        return [
            'NO.',
            'NAMA MEDIA',
            'NAMA PERUSAHAAN',
            // 'ALAMAT',
            'PLATFORM',
            'USERNAME',
            'PASSWORD'
        ];
    }

    public function collection()
    {
        return collect($this->data);
    }
}
