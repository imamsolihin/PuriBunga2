<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BukuBesarExport implements FromArray, WithHeadings, WithTitle
{
    protected $coas;
    protected $details;
    protected $selectedCoa;
    protected $saldoAwal;
    protected $startDate;
    protected $endDate;

    public function __construct($coas, $details, $selectedCoa, $saldoAwal, $startDate, $endDate)
    {
        $this->coas = $coas;
        $this->details = $details;
        $this->selectedCoa = $selectedCoa;
        $this->saldoAwal = $saldoAwal;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $data = [];
        
        if ($this->selectedCoa) {
            $data[] = ['Akun: ' . $this->selectedCoa->kode_akun . ' - ' . $this->selectedCoa->nama_akun];
            $data[] = ['Saldo Awal', '', '', '', $this->saldoAwal];
            
            $saldo = $this->saldoAwal;
            
            foreach ($this->details as $detail) {
                $dr = $detail->debit;
                $cr = $detail->kredit;
                
                if (in_array($this->selectedCoa->tipe, ['aset', 'beban'])) {
                    $saldo += ($dr - $cr);
                } else {
                    $saldo += ($cr - $dr);
                }
                
                $data[] = [
                    $detail->jurnal->tanggal,
                    $detail->jurnal->keterangan,
                    $dr,
                    $cr,
                    $saldo
                ];
            }
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            ['Paguyuban Puri Bunga 2'],
            ['Buku Besar'],
            ['Periode: ' . $this->startDate . ' s.d. ' . $this->endDate],
            [],
            ['Tanggal', 'Keterangan', 'Debit', 'Kredit', 'Saldo']
        ];
    }

    public function title(): string
    {
        return 'Buku Besar';
    }
}
