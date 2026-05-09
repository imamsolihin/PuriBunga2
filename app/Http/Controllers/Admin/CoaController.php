<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $coas = Coa::orderBy('kode_akun')->paginate(20);
        return view('admin.coa.index', compact('coas'));
    }

    public function create()
    {
        return view('admin.coa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|string|unique:coas,kode_akun',
            'nama_akun' => 'required|string|max:100',
            'tipe'      => 'required|in:aset,kewajiban,ekuitas,pendapatan,beban',
        ]);

        Coa::create($request->all());

        return redirect()->route('admin.coa.index')->with('success', 'COA berhasil ditambahkan.');
    }

    public function show(Coa $coa)
    {
        return redirect()->route('admin.coa.index');
    }

    public function edit(Coa $coa)
    {
        return view('admin.coa.edit', compact('coa'));
    }

    public function update(Request $request, Coa $coa)
    {
        $request->validate([
            'kode_akun' => 'required|string|unique:coas,kode_akun,' . $coa->id,
            'nama_akun' => 'required|string|max:100',
            'tipe'      => 'required|in:aset,kewajiban,ekuitas,pendapatan,beban',
        ]);

        $coa->update($request->all());

        return redirect()->route('admin.coa.index')->with('success', 'COA berhasil diperbarui.');
    }

    public function destroy(Coa $coa)
    {
        $coa->delete();
        return redirect()->route('admin.coa.index')->with('success', 'COA berhasil dihapus.');
    }
}
