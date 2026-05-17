<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Jurnal;
use App\Models\KasKecil;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarga = Warga::where('nama_lengkap', '!=', 'Tanpa Nama')
            ->where('nama_lengkap', '!=', '0')
            ->where('nama_lengkap', '!=', '')
            ->count();

        $totalPemasukan = Jurnal::where('tipe_transaksi', 'pemasukan')->sum('total');
        $totalPengeluaran = Jurnal::where('tipe_transaksi', 'pengeluaran')->sum('total');
        
        $coaKas = \App\Models\Coa::where('kode_akun', '10')->first();
        $totalKas = 0;
        if ($coaKas) {
            $debit = \App\Models\JurnalDetail::where('coa_id', $coaKas->id)->sum('debit');
            $kredit = \App\Models\JurnalDetail::where('coa_id', $coaKas->id)->sum('kredit');
            $totalKas = $debit - $kredit;
        }


        $iuranLunas = Iuran::where('status_pembayaran', 'lunas')->count();
        $iuranBelum = Iuran::where('status_pembayaran', 'belum')->count();

        // Transaksi terbaru
        $transaksiTerbaru = Jurnal::orderByDesc('tanggal')->take(10)->get();

        // Grafik 6 bulan terakhir (pemasukan vs pengeluaran)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulan = $date->format('M Y');
            $pemasukan = Jurnal::where('tipe_transaksi', 'pemasukan')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('total');
            $pengeluaran = Jurnal::where('tipe_transaksi', 'pengeluaran')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('total');
            $chartData[] = [
                'bulan' => $bulan,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ];
        }

        return view('admin.dashboard', compact(
            'totalWarga', 'totalPemasukan', 'totalPengeluaran', 'totalKas',
            'iuranLunas', 'iuranBelum', 'transaksiTerbaru', 'chartData'
        ));
    }
}
