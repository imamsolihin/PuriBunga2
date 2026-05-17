<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LabaRugiExport implements FromArray, WithHeadings, WithTitle
{
    protected $pendapatan;
    protected $beban;
    protected $startDate;
    protected $endDate;

    public function __construct($pendapatan, $beban, $startDate, $endDate)
    {
        $this->pendapatan = $pendapatan;
        $this->beban = $beban;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $rows = [];
        
        $rows[] = ['PENDAPATAN'];
        $totalP = 0;
        foreach ($this->pendapatan as $coa) {
            $val = ($coa->total_kredit ?? 0) - ($coa->total_debit ?? 0);
            $totalP += $val;
            $rows[] = [$coa->kode_akun, $coa->nama_akun, $val];
        }
        $rows[] = ['Total Pendapatan', '', $totalP];
        $rows[] = [];
        
        $rows[] = ['BEBAN'];
        $totalB = 0;
        foreach ($this->beban as $coa) {
            $val = ($coa->total_debit ?? 0) - ($coa->total_kredit ?? 0);
            $totalB += $val;
            $rows[] = [$coa->kode_akun, $coa->nama_akun, $val];
        }
        $rows[] = ['Total Beban', '', $totalB];
        $rows[] = [];
        
        $rows[] = ['LABA / RUGI BERSIH', '', $totalP - $totalB];
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            ['Paguyuban Puri Bunga 2'],
            ['Laporan Laba Rugi'],
            ['Periode: ' . $this->startDate . ' s.d. ' . $this->endDate],
            [],
            ['Kode', 'Akun', 'Jumlah']
        ];
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }
}
