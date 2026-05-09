<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\KasKecil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasKecilController extends Controller
{
    public function index()
    {
        $kasKecils = KasKecil::with('jurnal')->orderByDesc('tanggal')->paginate(20);

        $saldoMasuk   = KasKecil::where('jenis', 'masuk')->sum('nominal');
        $saldoKeluar  = KasKecil::where('jenis', 'keluar')->sum('nominal');
        $saldo        = $saldoMasuk - $saldoKeluar;

        return view('admin.kas-kecil.index', compact('kasKecils', 'saldo', 'saldoMasuk', 'saldoKeluar'));
    }

    public function create()
    {
        $coas = Coa::orderBy('kode_akun')->get();
        return view('admin.kas-kecil.create', compact('coas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jenis'      => 'required|in:masuk,keluar',
            'nominal'    => 'required|numeric|min:0',
            'coa_debit'  => 'required|exists:coas,id',
            'coa_kredit' => 'required|exists:coas,id',
        ]);

        DB::transaction(function () use ($request) {
            // Buat Jurnal otomatis
            $jurnal = Jurnal::create([
                'tanggal'        => $request->tanggal,
                'keterangan'     => '[Kas Kecil] ' . $request->keterangan,
                'tipe_transaksi' => $request->jenis === 'masuk' ? 'pemasukan' : 'pengeluaran',
                'total'          => $request->nominal,
            ]);

            // Debit
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'coa_id'    => $request->coa_debit,
                'debit'     => $request->nominal,
                'kredit'    => 0,
            ]);

            // Kredit
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'coa_id'    => $request->coa_kredit,
                'debit'     => 0,
                'kredit'    => $request->nominal,
            ]);

            // Buat Kas Kecil
            KasKecil::create([
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jenis'      => $request->jenis,
                'nominal'    => $request->nominal,
                'jurnal_id'  => $jurnal->id,
            ]);
        });

        return redirect()->route('admin.kas-kecil.index')->with('success', 'Transaksi kas kecil berhasil dicatat dan jurnal otomatis dibuat.');
    }

    public function show(KasKecil $kasKecil)
    {
        $kasKecil->load('jurnal.details.coa');
        return view('admin.kas-kecil.show', compact('kasKecil'));
    }

    public function edit(KasKecil $kasKecil)
    {
        return view('admin.kas-kecil.edit', compact('kasKecil'));
    }

    public function update(Request $request, KasKecil $kasKecil)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jenis'      => 'required|in:masuk,keluar',
            'nominal'    => 'required|numeric|min:0',
        ]);

        $kasKecil->update($request->only('tanggal', 'keterangan', 'jenis', 'nominal'));

        if ($kasKecil->jurnal) {
            $kasKecil->jurnal->update([
                'tanggal'        => $request->tanggal,
                'keterangan'     => '[Kas Kecil] ' . $request->keterangan,
                'tipe_transaksi' => $request->jenis === 'masuk' ? 'pemasukan' : 'pengeluaran',
                'total'          => $request->nominal,
            ]);
        }

        return redirect()->route('admin.kas-kecil.index')->with('success', 'Transaksi kas kecil berhasil diperbarui.');
    }

    public function destroy(KasKecil $kasKecil)
    {
        $kasKecil->delete(); // jurnal ikut terhapus via cascade
        return redirect()->route('admin.kas-kecil.index')->with('success', 'Transaksi kas kecil berhasil dihapus.');
    }
}
