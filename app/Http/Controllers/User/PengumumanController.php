<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::where('status', 'aktif')->orderByDesc('created_at')->paginate(10);
        return view('user.pengumuman.index', compact('pengumumans'));
    }
}
