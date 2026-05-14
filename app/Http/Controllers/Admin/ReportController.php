<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function bukuBesar(Request $request)
    {
        $coas = Coa::orderBy('kode_akun')->get();
        $coaId = $request->coa_id;
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $details = [];
        $selectedCoa = null;
        $saldoAwal = 0;

        if ($coaId) {
            $selectedCoa = Coa::findOrFail($coaId);

            // Saldo Awal (before start date)
            $prevDebit = JurnalDetail::where('coa_id', $coaId)
                ->whereHas('jurnal', fn($q) => $q->where('tanggal', '<', $startDate))
                ->sum('debit');
            $prevKredit = JurnalDetail::where('coa_id', $coaId)
                ->whereHas('jurnal', fn($q) => $q->where('tanggal', '<', $startDate))
                ->sum('kredit');

            // Logic saldo awal based on COA type
            if (in_array($selectedCoa->tipe, ['aset', 'beban'])) {
                $saldoAwal = $prevDebit - $prevKredit;
            } else {
                $saldoAwal = $prevKredit - $prevDebit;
            }

            $details = JurnalDetail::with('jurnal')
                ->where('coa_id', $coaId)
                ->whereHas('jurnal', fn($q) => $q->whereBetween('tanggal', [$startDate, $endDate]))
                ->get()
                ->sortBy(fn($d) => $d->jurnal->tanggal);
        }

        return view('admin.reports.buku-besar', compact('coas', 'details', 'selectedCoa', 'saldoAwal', 'startDate', 'endDate'));
    }

    public function neracaSaldo(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');

        $coas = Coa::with(['jurnalDetails' => function($q) use ($date) {
            $q->whereHas('jurnal', fn($j) => $j->where('tanggal', '<=', $date));
        }])->orderBy('kode_akun')->get();

        $data = $coas->map(function($coa) {
            $totalDebit = $coa->jurnalDetails->sum('debit');
            $totalKredit = $coa->jurnalDetails->sum('kredit');

            $debit = 0;
            $kredit = 0;

            if (in_array($coa->tipe, ['aset', 'beban'])) {
                $balance = $totalDebit - $totalKredit;
                $debit = $balance > 0 ? $balance : 0;
                $kredit = $balance < 0 ? abs($balance) : 0;
            } else {
                $balance = $totalKredit - $totalDebit;
                $kredit = $balance > 0 ? $balance : 0;
                $debit = $balance < 0 ? abs($balance) : 0;
            }

            return [
                'kode' => $coa->kode_akun,
                'nama' => $coa->nama_akun,
                'debit' => $debit,
                'kredit' => $kredit,
            ];
        });

        return view('admin.reports.neraca-saldo', compact('data', 'date'));
    }
}
