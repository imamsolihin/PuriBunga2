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
        $wargas = Warga::where('nama_lengkap', '!=', 'Tanpa Nama')
            ->where('nama_lengkap', '!=', '0')
            ->where('nama_lengkap', '!=', '')
            ->orderBy('nama_lengkap')
            ->get();
        $kategoris = KategoriIuran::all();

        return view('admin.iuran.index', compact('iurans', 'wargas', 'kategoris'));
    }

    public function create()
    {
        $wargas = Warga::where('status', 'aktif')
            ->where('nama_lengkap', '!=', 'Tanpa Nama')
            ->where('nama_lengkap', '!=', '0')
            ->where('nama_lengkap', '!=', '')
            ->orderBy('nama_lengkap')
            ->get();
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
        $wargas = Warga::where('status', 'aktif')
            ->where('nama_lengkap', '!=', 'Tanpa Nama')
            ->where('nama_lengkap', '!=', '0')
            ->where('nama_lengkap', '!=', '')
            ->orderBy('nama_lengkap')
            ->get();
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

    public function toggleStatus(Request $request, Iuran $iuran)
    {
        if ($iuran->status_pembayaran === 'belum') {
            $iuran->update([
                'status_pembayaran' => 'lunas',
                'tanggal_bayar' => now()->toDateString()
            ]);

            // Create Journal
            $keterangan = 'Pembayaran Iuran ' . $iuran->bulan . ' ' . $iuran->tahun . ' oleh ' . ($iuran->warga->nama_lengkap ?? '-');
            $jurnal = \App\Models\Jurnal::create([
                'tanggal' => now()->toDateString(),
                'keterangan' => $keterangan,
                'total' => $iuran->nominal,
                'tipe_transaksi' => 'pemasukan'
            ]);

            $kasCoa = \App\Models\Coa::where('kode_akun', '101')->first();
            
            $pendapatanName = 'Pendapatan Iuran ' . ($iuran->kategoriIuran->nama_kategori ?? '');
            $pendapatanCoa = \App\Models\Coa::where('nama_akun', $pendapatanName)->first();
            if (!$pendapatanCoa) {
                if (stripos($pendapatanName, 'Kas Lingkungan') !== false) {
                    $pendapatanCoa = \App\Models\Coa::where('kode_akun', '403')->first();
                } else if (stripos($pendapatanName, 'Perawatan') !== false) {
                    $pendapatanCoa = \App\Models\Coa::where('kode_akun', '404')->first();
                } else {
                    $pendapatanCoa = \App\Models\Coa::where('kode_akun', '405')->first();
                }
            }

            if ($kasCoa && $pendapatanCoa) {
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $kasCoa->id,
                    'debit' => $iuran->nominal,
                    'kredit' => 0
                ]);
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $pendapatanCoa->id,
                    'debit' => 0,
                    'kredit' => $iuran->nominal
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Status menjadi Lunas dan Jurnal tercatat.', 
                'status' => 'lunas'
            ]);
        } else {
            // Revert back to belum lunas
            $keterangan = 'Pembayaran Iuran ' . $iuran->bulan . ' ' . $iuran->tahun . ' oleh ' . ($iuran->warga->nama_lengkap ?? '-');
            $jurnal = \App\Models\Jurnal::where('keterangan', $keterangan)->where('tanggal', $iuran->tanggal_bayar)->first();
            
            if ($jurnal) {
                $jurnal->details()->delete();
                $jurnal->delete();
            }

            $iuran->update([
                'status_pembayaran' => 'belum',
                'tanggal_bayar' => null
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Status dikembalikan ke Belum Lunas.', 
                'status' => 'belum'
            ]);
        }
    }
}
