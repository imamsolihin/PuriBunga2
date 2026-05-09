<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WargaController extends Controller
{
    public function index()
    {
        $wargas = Warga::with('user')->latest()->paginate(15);
        return view('admin.warga.index', compact('wargas'));
    }

    public function create()
    {
        return view('admin.warga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'blok_rumah'   => 'required|string|max:10',
            'nomor_rumah'  => 'required|string|max:10',
            'no_hp'        => 'nullable|string|max:20',
            'status'       => 'required|in:aktif,tidak',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        Warga::create([
            'user_id'      => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'blok_rumah'   => $request->blok_rumah,
            'nomor_rumah'  => $request->nomor_rumah,
            'no_hp'        => $request->no_hp,
            'status'       => $request->status,
        ]);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function show(Warga $warga)
    {
        $warga->load('user', 'iurans.kategoriIuran');
        return view('admin.warga.show', compact('warga'));
    }

    public function edit(Warga $warga)
    {
        return view('admin.warga.edit', compact('warga'));
    }

    public function update(Request $request, Warga $warga)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'blok_rumah'   => 'required|string|max:10',
            'nomor_rumah'  => 'required|string|max:10',
            'no_hp'        => 'nullable|string|max:20',
            'status'       => 'required|in:aktif,tidak',
        ]);

        $warga->update($request->only('nama_lengkap', 'blok_rumah', 'nomor_rumah', 'no_hp', 'status'));

        if ($warga->user && $request->filled('password')) {
            $warga->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        if ($warga->user) {
            $warga->user->delete();
        }
        $warga->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
