<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriIuran;
use Illuminate\Http\Request;

class KategoriIuranController extends Controller
{
    public function index()
    {
        $kategoris = KategoriIuran::withCount('iurans')->latest()->get();
        return view('admin.kategori-iuran.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori-iuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'   => 'required|string|max:100',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        KategoriIuran::create($request->all());

        return redirect()->route('admin.kategori-iuran.index')->with('success', 'Kategori iuran berhasil ditambahkan.');
    }

    public function edit(KategoriIuran $kategoriIuran)
    {
        return view('admin.kategori-iuran.edit', compact('kategoriIuran'));
    }

    public function update(Request $request, KategoriIuran $kategoriIuran)
    {
        $request->validate([
            'nama_kategori'   => 'required|string|max:100',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        $kategoriIuran->update($request->all());

        return redirect()->route('admin.kategori-iuran.index')->with('success', 'Kategori iuran berhasil diperbarui.');
    }

    public function destroy(KategoriIuran $kategoriIuran)
    {
        $kategoriIuran->delete();
        return redirect()->route('admin.kategori-iuran.index')->with('success', 'Kategori iuran berhasil dihapus.');
    }

    public function show(KategoriIuran $kategoriIuran)
    {
        return redirect()->route('admin.kategori-iuran.index');
    }
}
