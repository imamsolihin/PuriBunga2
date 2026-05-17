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
    public function laporanKas(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfYear()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $coas = Coa::withSum(['jurnalDetails as total_debit' => function($q) use ($startDate, $endDate) {
            $q->whereHas('jurnal', fn($j) => $j->whereBetween('tanggal', [$startDate, $endDate]));
        }], 'debit')
        ->withSum(['jurnalDetails as total_kredit' => function($q) use ($startDate, $endDate) {
            $q->whereHas('jurnal', fn($j) => $j->whereBetween('tanggal', [$startDate, $endDate]));
        }], 'kredit')
        ->where('kode_akun', '!=', '101')
        ->orderBy('kode_akun')
        ->get();

        $kasOperasional = $coas->filter(fn($c) => in_array($c->tipe, ['pendapatan', 'beban']));
        $nonOperasional = $coas->filter(fn($c) => in_array($c->tipe, ['aset', 'kewajiban']));

        return view('admin.reports.laporan-kas', compact('kasOperasional', 'nonOperasional', 'startDate', 'endDate'));
    }

    public function neracaYtd(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');

        $coas = Coa::withSum(['jurnalDetails as total_debit' => function($q) use ($date) {
            $q->whereHas('jurnal', fn($j) => $j->where('tanggal', '<=', $date));
        }], 'debit')
        ->withSum(['jurnalDetails as total_kredit' => function($q) use ($date) {
            $q->whereHas('jurnal', fn($j) => $j->where('tanggal', '<=', $date));
        }], 'kredit')
        ->orderBy('kode_akun')
        ->get();

        $data = $coas->map(function($coa) {
            $dr = $coa->total_debit ?? 0;
            $cr = $coa->total_kredit ?? 0;
            $balance = 0;

            if (in_array($coa->tipe, ['aset', 'beban'])) {
                $balance = $dr - $cr;
            } else {
                $balance = $cr - $dr;
            }

            return [
                'id' => $coa->id,
                'kode' => $coa->kode_akun,
                'nama' => $coa->nama_akun,
                'tipe' => $coa->tipe,
                'balance' => $balance,
            ];
        });

        $aset = $data->filter(fn($d) => $d['tipe'] === 'aset');
        $kewajiban = $data->filter(fn($d) => $d['tipe'] === 'kewajiban' || $d['tipe'] === 'hutang');
        $modalAccounts = $data->filter(fn($d) => $d['tipe'] === 'modal');
        $pendapatan = $data->filter(fn($d) => $d['tipe'] === 'pendapatan');
        $beban = $data->filter(fn($d) => $d['tipe'] === 'beban');

        $totalPendapatan = $pendapatan->sum('balance');
        $totalBeban = $beban->sum('balance');
        $labaRugi = $totalPendapatan - $totalBeban;

        $totalModal = $modalAccounts->sum('balance') + $labaRugi;

        return view('admin.reports.neraca-ytd', compact('aset', 'kewajiban', 'modalAccounts', 'labaRugi', 'totalModal', 'date'));
    }
}
