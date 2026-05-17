<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurnal::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('coa_id')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('coa_id', $request->coa_id);
            });
        }

        if ($request->filled('nominal')) {
            $query->where('total', $request->nominal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $jurnals = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();
        $coas = Coa::orderBy('kode_akun')->get();

        return view('admin.jurnal.index', compact('jurnals', 'coas'));
    }

    public function create()
    {
        $coas = Coa::orderBy('kode_akun')->get();
        return view('admin.jurnal.create', compact('coas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'keterangan'      => 'required|string',
            'tipe_transaksi'  => 'required|in:pemasukan,pengeluaran,umum',
            'details'         => 'required|array|min:2',
            'details.*.coa_id'=> 'required|exists:coas,id',
            'details.*.debit' => 'nullable|numeric|min:0',
            'details.*.kredit'=> 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = collect($request->details)->sum(fn($d) => floatval($d['debit'] ?? 0));

            $jurnal = Jurnal::create([
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
                'tipe_transaksi' => $request->tipe_transaksi,
                'total'          => $total,
            ]);

            foreach ($request->details as $detail) {
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id'    => $detail['coa_id'],
                    'debit'     => floatval($detail['debit'] ?? 0),
                    'kredit'    => floatval($detail['kredit'] ?? 0),
                ]);
            }
        });

        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil dicatat.');
    }

    public function show(Jurnal $jurnal)
    {
        $jurnal->load('details.coa');
        return view('admin.jurnal.show', compact('jurnal'));
    }

    public function edit(Jurnal $jurnal)
    {
        $jurnal->load('details');
        $coas = Coa::orderBy('kode_akun')->get();
        return view('admin.jurnal.edit', compact('jurnal', 'coas'));
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'keterangan'      => 'required|string',
            'tipe_transaksi'  => 'required|in:pemasukan,pengeluaran,umum',
            'details'         => 'required|array|min:2',
            'details.*.coa_id'=> 'required|exists:coas,id',
            'details.*.debit' => 'nullable|numeric|min:0',
            'details.*.kredit'=> 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $jurnal) {
            $total = collect($request->details)->sum(fn($d) => floatval($d['debit'] ?? 0));

            $jurnal->update([
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
                'tipe_transaksi' => $request->tipe_transaksi,
                'total'          => $total,
            ]);

            $jurnal->details()->delete();

            foreach ($request->details as $detail) {
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id'    => $detail['coa_id'],
                    'debit'     => floatval($detail['debit'] ?? 0),
                    'kredit'    => floatval($detail['kredit'] ?? 0),
                ]);
            }
        });

        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Jurnal $jurnal)
    {
        $jurnal->delete();
        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil dihapus.');
    }
}
