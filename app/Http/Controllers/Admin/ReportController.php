<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\Jurnal;
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

    public function laporanLabaRugi(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $coas = Coa::withSum(['jurnalDetails as total_debit' => function($q) use ($startDate, $endDate) {
            $q->whereHas('jurnal', fn($j) => $j->whereBetween('tanggal', [$startDate, $endDate]));
        }], 'debit')
        ->withSum(['jurnalDetails as total_kredit' => function($q) use ($startDate, $endDate) {
            $q->whereHas('jurnal', fn($j) => $j->whereBetween('tanggal', [$startDate, $endDate]));
        }], 'kredit')
        ->orderBy('kode_akun')
        ->get();

        $pendapatan = $coas->filter(fn($c) => $c->tipe === 'pendapatan');
        $beban = $coas->filter(fn($c) => $c->tipe === 'beban');

        return view('admin.reports.laba-rugi', compact('pendapatan', 'beban', 'startDate', 'endDate'));
    }

    public function importCsv()
    {
        // Increase time limit for large files
        set_time_limit(300);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        \App\Models\JurnalDetail::truncate();
        \App\Models\Jurnal::truncate();
        Coa::truncate();
        
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 1. Import COA
        $coaFile = public_path('images/COA.csv');
        if (file_exists($coaFile)) {
            $handle = fopen($coaFile, 'r');
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (empty($data[0])) continue;
                
                $kode = trim($data[0]);
                $nama = trim($data[1]);
                
                $codeInt = (int)$kode;
                $tipe = 'beban';
                if ($codeInt >= 10 && $codeInt <= 19) $tipe = 'aset';
                elseif ($codeInt >= 20 && $codeInt <= 29) $tipe = 'kewajiban';
                elseif ($codeInt >= 30 && $codeInt <= 39) $tipe = 'ekuitas';
                elseif ($codeInt >= 40 && $codeInt <= 49) $tipe = 'pendapatan';
                
                Coa::create([
                    'kode_akun' => $kode,
                    'nama_akun' => $nama,
                    'tipe' => $tipe,
                ]);
            }
            fclose($handle);
        }

        // 2. Import Jurnal
        $this->importJurnalFile(public_path('images/JURNAL_26.csv'));
        $this->importJurnalFile(public_path('images/JURNAL_25.csv'));

        return redirect()->route('admin.laporan.index')->with('success', 'Data berhasil diimpor dari CSV!');
    }

    private function importJurnalFile($filePath)
    {
        if (!file_exists($filePath)) return;

        $handle = fopen($filePath, 'r');
        $headerFound = false;
        
        $cashCoa = Coa::where('kode_akun', '10')->first();
        if (!$cashCoa) {
            $cashCoa = Coa::create(['kode_akun' => '10', 'nama_akun' => 'Kas dan Bank', 'tipe' => 'aset']);
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (!$headerFound) {
                if (isset($data[1]) && str_contains($data[1], 'Tanggal')) {
                    $headerFound = true;
                }
                continue;
            }

            if (empty($data[1])) continue;
            
            $tanggal = date('Y-m-d', strtotime($data[1]));
            $accCode = trim($data[5]);
            $accName = $data[6];
            $uraian = $data[7];
            $dr = (float)$data[9];
            $cr = (float)$data[10];

            if ($dr == 0 && $cr == 0) continue;

            $coa = Coa::where('kode_akun', $accCode)->first();
            if (!$coa) {
                $codeInt = (int)$accCode;
                $tipe = 'beban';
                if ($codeInt >= 10 && $codeInt <= 19) $tipe = 'aset';
                elseif ($codeInt >= 20 && $codeInt <= 29) $tipe = 'kewajiban';
                elseif ($codeInt >= 30 && $codeInt <= 39) $tipe = 'ekuitas';
                elseif ($codeInt >= 40 && $codeInt <= 49) $tipe = 'pendapatan';

                $coa = Coa::create([
                    'kode_akun' => $accCode,
                    'nama_akun' => $accName ?: 'Uncategorized',
                    'tipe' => $tipe,
                ]);
            }

            $jurnal = \App\Models\Jurnal::create([
                'tanggal' => $tanggal,
                'keterangan' => $uraian ?: 'Imported',
                'total' => max($dr, $cr),
                'tipe_transaksi' => $dr > 0 ? 'pemasukan' : 'pengeluaran',
            ]);

            if ($dr > 0) {
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $cashCoa->id,
                    'debit' => $dr,
                    'kredit' => 0,
                ]);
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $coa->id,
                    'debit' => 0,
                    'kredit' => $dr,
                ]);
            } else {
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $coa->id,
                    'debit' => $cr,
                    'kredit' => 0,
                ]);
                \App\Models\JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $cashCoa->id,
                    'debit' => 0,
                    'kredit' => $cr,
                ]);
            }
        }
        fclose($handle);
    }
}
