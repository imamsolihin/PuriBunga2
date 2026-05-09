<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\KategoriIuran;
use App\Models\Warga;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $query = Iuran::with(['warga', 'kategoriIuran']);

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $iurans = $query->orderByDesc('tahun')->orderBy('bulan')->paginate(20);
        $wargas = Warga::orderBy('nama_lengkap')->get();
        $kategoris = KategoriIuran::all();

        return view('admin.iuran.index', compact('iurans', 'wargas', 'kategoris'));
    }

    public function create()
    {
        $wargas = Warga::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $kategoris = KategoriIuran::all();
        return view('admin.iuran.create', compact('wargas', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warga_id'          => 'required|exists:wargas,id',
            'kategori_iuran_id' => 'required|exists:kategori_iurans,id',
            'bulan'             => 'required|string',
            'tahun'             => 'required|integer|min:2000|max:2100',
            'nominal'           => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:lunas,belum',
            'tanggal_bayar'     => 'nullable|date',
        ]);

        Iuran::create($request->all());

        return redirect()->route('admin.iuran.index')->with('success', 'Data iuran berhasil ditambahkan.');
    }

    public function show(Iuran $iuran)
    {
        $iuran->load('warga', 'kategoriIuran');
        return view('admin.iuran.show', compact('iuran'));
    }

    public function edit(Iuran $iuran)
    {
        $wargas = Warga::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $kategoris = KategoriIuran::all();
        return view('admin.iuran.edit', compact('iuran', 'wargas', 'kategoris'));
    }

    public function update(Request $request, Iuran $iuran)
    {
        $request->validate([
            'warga_id'          => 'required|exists:wargas,id',
            'kategori_iuran_id' => 'required|exists:kategori_iurans,id',
            'bulan'             => 'required|string',
            'tahun'             => 'required|integer|min:2000|max:2100',
            'nominal'           => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:lunas,belum',
            'tanggal_bayar'     => 'nullable|date',
        ]);

        $iuran->update($request->all());

        return redirect()->route('admin.iuran.index')->with('success', 'Data iuran berhasil diperbarui.');
    }

    public function destroy(Iuran $iuran)
    {
        $iuran->delete();
        return redirect()->route('admin.iuran.index')->with('success', 'Data iuran berhasil dihapus.');
    }
}
