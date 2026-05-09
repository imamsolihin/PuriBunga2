<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $warga = $user->warga;

        $totalLunas = $warga
            ? Iuran::where('warga_id', $warga->id)->where('status_pembayaran', 'lunas')->count()
            : 0;

        $totalBelum = $warga
            ? Iuran::where('warga_id', $warga->id)->where('status_pembayaran', 'belum')->count()
            : 0;

        $iurans = $warga
            ? Iuran::where('warga_id', $warga->id)
                ->with('kategoriIuran')
                ->orderByDesc('tahun')->orderByDesc('bulan')
                ->take(5)->get()
            : collect();

        $pengumumans = Pengumuman::where('status', 'aktif')->orderByDesc('created_at')->take(3)->get();

        return view('user.dashboard', compact('warga', 'totalLunas', 'totalBelum', 'iurans', 'pengumumans'));
    }
}
