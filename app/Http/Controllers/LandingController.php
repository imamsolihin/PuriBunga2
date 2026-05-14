<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Data Keuangan Real
        $totalPemasukan = Jurnal::where('tipe_transaksi', 'pemasukan')->sum('total');
        $totalPengeluaran = Jurnal::where('tipe_transaksi', 'pengeluaran')->sum('total');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        // Pengumuman Terkini
        $pengumumans = Pengumuman::where('status', 'aktif')->orderByDesc('created_at')->take(4)->get();

        return view('welcome', compact('totalPemasukan', 'totalPengeluaran', 'totalKas', 'pengumumans'));
    }
}
