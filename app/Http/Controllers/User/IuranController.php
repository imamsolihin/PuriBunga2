<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Iuran;

class IuranController extends Controller
{
    public function index()
    {
        $warga = auth()->user()->warga;

        if (!$warga) {
            return view('user.iuran.index', ['iurans' => collect(), 'warga' => null]);
        }

        $query = Iuran::where('warga_id', $warga->id)->with('kategoriIuran');

        if (request('tahun')) {
            $query->where('tahun', request('tahun'));
        }

        if (request('status')) {
            $query->where('status_pembayaran', request('status'));
        }

        $iurans = $query->orderByDesc('tahun')
                       ->orderByDesc('id') // Bulan order logic is complex, ID usually reflects order
                       ->paginate(20);

        return view('user.iuran.index', compact('warga', 'iurans'));
    }
}
