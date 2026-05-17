<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class NeracaYtdExport implements FromArray, WithHeadings, WithTitle
{
    protected $aset;
    protected $kewajiban;
    protected $modalAccounts;
    protected $labaRugi;
    protected $totalModal;
    protected $date;

    public function __construct($aset, $kewajiban, $modalAccounts, $labaRugi, $totalModal, $date)
    {
        $this->aset = $aset;
        $this->kewajiban = $kewajiban;
        $this->modalAccounts = $modalAccounts;
        $this->labaRugi = $labaRugi;
        $this->totalModal = $totalModal;
        $this->date = $date;
    }

    public function array(): array
    {
        $rows = [];
        
        $rows[] = ['ASET'];
        foreach ($this->aset as $item) {
            $rows[] = [$item['kode'], $item['nama'], $item['balance']];
        }
        $rows[] = ['Total Aset', '', $this->aset->sum('balance')];
        $rows[] = [];
        
        $rows[] = ['KEWAJIBAN'];
        foreach ($this->kewajiban as $item) {
            $rows[] = [$item['kode'], $item['nama'], $item['balance']];
        }
        $rows[] = ['Total Kewajiban', '', $this->kewajiban->sum('balance')];
        $rows[] = [];
        
        $rows[] = ['EKUITAS / MODAL'];
        foreach ($this->modalAccounts as $item) {
            $rows[] = [$item['kode'], $item['nama'], $item['balance']];
        }
        $rows[] = ['', 'Laba / Rugi Periode Berjalan', $this->labaRugi];
        $rows[] = ['Total Ekuitas', '', $this->totalModal];
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            ['Paguyuban Puri Bunga 2'],
            ['Neraca (Year to Date)'],
            ['Per Tanggal: ' . $this->date],
            [],
            ['Kode', 'Akun', 'Saldo']
        ];
    }

    public function title(): string
    {
        return 'Neraca YTD';
    }
}
