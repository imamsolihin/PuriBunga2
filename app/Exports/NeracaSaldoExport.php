<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class NeracaSaldoExport implements FromArray, WithHeadings, WithTitle
{
    protected $data;
    protected $date;

    public function __construct($data, $date)
    {
        $this->data = $data;
        $this->date = $date;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->data as $item) {
            $rows[] = [
                $item['kode'],
                $item['nama'],
                $item['debit'],
                $item['kredit']
            ];
        }
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            ['Paguyuban Puri Bunga 2'],
            ['Neraca Saldo'],
            ['Per Tanggal: ' . $this->date],
            [],
            ['Kode', 'Akun', 'Debit', 'Kredit']
        ];
    }

    public function title(): string
    {
        return 'Neraca Saldo';
    }
}
