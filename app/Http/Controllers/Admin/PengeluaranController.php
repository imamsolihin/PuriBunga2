<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Jurnal::where('tipe_transaksi', 'pengeluaran')
            ->orderByDesc('tanggal')
            ->paginate(20);

        return view('admin.pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        $coasBeban = Coa::where('tipe', 'beban')->orderBy('kode_akun')->get();
        $coasAset = Coa::where('tipe', 'aset')->orderBy('kode_akun')->get();
        return view('admin.pengeluaran.create', compact('coasBeban', 'coasAset'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'keterangan'   => 'required|string',
            'nominal'      => 'required|numeric|min:0',
            'coa_beban'    => 'required|exists:coas,id',
            'coa_kas'      => 'required|exists:coas,id',
        ]);

        DB::transaction(function () use ($request) {
            $jurnal = Jurnal::create([
                'tanggal'        => $request->tanggal,
                'keterangan'     => '[Pengeluaran] ' . $request->keterangan,
                'tipe_transaksi' => 'pengeluaran',
                'total'          => $request->nominal,
            ]);

            // Debit Beban
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'coa_id'    => $request->coa_beban,
                'debit'     => $request->nominal,
                'kredit'    => 0,
            ]);

            // Kredit Kas/Bank
            JurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'coa_id'    => $request->coa_kas,
                'debit'     => 0,
                'kredit'    => $request->nominal,
            ]);
        });

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function show(Jurnal $pengeluaran)
    {
        if ($pengeluaran->tipe_transaksi !== 'pengeluaran') {
            abort(404);
        }
        $pengeluaran->load('details.coa');
        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Jurnal $pengeluaran)
    {
        if ($pengeluaran->tipe_transaksi !== 'pengeluaran') {
            abort(404);
        }
        $pengeluaran->load('details');
        $coasBeban = Coa::where('tipe', 'beban')->orderBy('kode_akun')->get();
        $coasAset = Coa::where('tipe', 'aset')->orderBy('kode_akun')->get();
        
        // Find current coa_beban and coa_kas
        $coaBebanId = $pengeluaran->details->where('debit', '>', 0)->first()?->coa_id;
        $coaKasId = $pengeluaran->details->where('kredit', '>', 0)->first()?->coa_id;

        return view('admin.pengeluaran.edit', compact('pengeluaran', 'coasBeban', 'coasAset', 'coaBebanId', 'coaKasId'));
    }

    public function update(Request $request, Jurnal $pengeluaran)
    {
        if ($pengeluaran->tipe_transaksi !== 'pengeluaran') {
            abort(404);
        }

        $request->validate([
            'tanggal'      => 'required|date',
            'keterangan'   => 'required|string',
            'nominal'      => 'required|numeric|min:0',
            'coa_beban'    => 'required|exists:coas,id',
            'coa_kas'      => 'required|exists:coas,id',
        ]);

        DB::transaction(function () use ($request, $pengeluaran) {
            $pengeluaran->update([
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan, // Keep original or auto-prefix? Let's keep what user enters but maybe prefix if needed.
                'total'          => $request->nominal,
            ]);

            $pengeluaran->details()->delete();

            // Debit Beban
            JurnalDetail::create([
                'jurnal_id' => $pengeluaran->id,
                'coa_id'    => $request->coa_beban,
                'debit'     => $request->nominal,
                'kredit'    => 0,
            ]);

            // Kredit Kas/Bank
            JurnalDetail::create([
                'jurnal_id' => $pengeluaran->id,
                'coa_id'    => $request->coa_kas,
                'debit'     => 0,
                'kredit'    => $request->nominal,
            ]);
        });

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Jurnal $pengeluaran)
    {
        if ($pengeluaran->tipe_transaksi !== 'pengeluaran') {
            abort(404);
        }
        $pengeluaran->delete();
        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
