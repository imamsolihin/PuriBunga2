<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanKasExport implements FromArray, WithHeadings, WithTitle
{
    protected $kasOperasional;
    protected $nonOperasional;
    protected $startDate;
    protected $endDate;

    public function __construct($kasOperasional, $nonOperasional, $startDate, $endDate)
    {
        $this->kasOperasional = $kasOperasional;
        $this->nonOperasional = $nonOperasional;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $data = [];
        
        // Kas Operasional
        $data[] = ['Kas Operasional'];
        $totalDrOp = 0;
        $totalCrOp = 0;
        
        foreach ($this->kasOperasional as $coa) {
            $dr = $coa->total_kredit ?? 0;
            $cr = $coa->total_debit ?? 0;
            $saldo = $dr - $cr;
            
            $totalDrOp += $dr;
            $totalCrOp += $cr;
            
            $data[] = [
                $coa->kode_akun,
                $coa->nama_akun,
                $dr,
                $cr,
                $saldo
            ];
        }
        
        $data[] = ['Jumlah Kas Operasional', '', $totalDrOp, $totalCrOp, $totalDrOp - $totalCrOp];
        $data[] = []; // Empty row
        
        // Kenaikan dan Penurunan Kas
        $data[] = ['Kenaikan dan Penurunan Kas'];
        $totalDrNon = 0;
        $totalCrNon = 0;
        
        foreach ($this->nonOperasional as $coa) {
            $dr = $coa->total_kredit ?? 0;
            $cr = $coa->total_debit ?? 0;
            $saldo = $dr - $cr;
            
            $totalDrNon += $dr;
            $totalCrNon += $cr;
            
            $data[] = [
                $coa->kode_akun,
                $coa->nama_akun,
                $dr,
                $cr,
                $saldo
            ];
        }
        
        $data[] = ['Jumlah Non-Operasional', '', $totalDrNon, $totalCrNon, $totalDrNon - $totalCrNon];
        $data[] = []; // Empty row
        
        // Total
        $totalKas = ($totalDrOp - $totalCrOp) + ($totalDrNon - $totalCrNon);
        $data[] = ['TOTAL PERUBAHAN KAS', '', '', '', $totalKas];
        
        return $data;
    }

    public function headings(): array
    {
        return [
            ['Paguyuban Puri Bunga 2'],
            ['Laporan Kas'],
            ['Periode: ' . $this->startDate . ' s.d. ' . $this->endDate],
            [],
            ['Kode', 'Akun', 'DR (Penerimaan)', 'CR (Pengeluaran)', 'Saldo']
        ];
    }

    public function title(): string
    {
        return 'Laporan Kas';
    }
}
