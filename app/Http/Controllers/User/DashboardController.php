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

        $penghunis = $warga ? $warga->penghunis : collect();

        return view('user.dashboard', compact('warga', 'totalLunas', 'totalBelum', 'iurans', 'pengumumans', 'penghunis'));
    }

    public function storePenghuni(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'hubungan' => 'nullable|string|max:50',
        ]);

        $warga = auth()->user()->warga;
        if (!$warga) {
            return redirect()->back()->with('error', 'Data warga tidak ditemukan.');
        }

        $warga->penghunis()->create($request->only('nama', 'nik', 'no_hp', 'hubungan'));

        return redirect()->back()->with('success', 'Data penghuni berhasil ditambahkan.');
    }
}
