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

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE wargas DROP CONSTRAINT IF EXISTS wargas_status_check;');
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        // Truncate in order to respect FKs
        \App\Models\Penghuni::truncate();
        \App\Models\Iuran::truncate();
        \App\Models\JurnalDetail::truncate();
        \App\Models\Jurnal::truncate();
        Coa::truncate();
        \App\Models\Warga::truncate();
        \App\Models\KategoriIuran::truncate();
        
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

        // 3. Import Warga & Iuran
        $this->importWargaFile(public_path('images/IURAN WARGA.csv'));

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

    private function importWargaFile($filePath)
    {
        if (!file_exists($filePath)) return;

        $handle = fopen($filePath, 'r');
        $headerFound = false;
        
        $category = \App\Models\KategoriIuran::firstOrCreate(
            ['nama_kategori' => 'Iuran Bulanan'],
            ['nominal_default' => 100000]
        );

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (!$headerFound) {
                if (isset($data[0]) && $data[0] == 'No' && isset($data[1]) && $data[1] == 'Blok') {
                    $headerFound = true;
                }
                continue;
            }

            if (empty($data[1]) || $data[1] == 'Jumlah') continue;
            
            $blokFull = trim($data[1]);
            $nama = trim($data[2]);
            $jenisHunian = trim($data[3]);
            $kondisi = trim($data[4]);

            $blok = '';
            $nomor = $blokFull;
            if (str_contains($blokFull, '-')) {
                $parts = explode('-', $blokFull);
                $blok = $parts[0];
                $nomor = $parts[1];
            }

            $warga = \App\Models\Warga::create([
                'nama_lengkap' => $nama ?: 'Tanpa Nama',
                'blok_rumah' => $blok,
                'nomor_rumah' => $nomor,
                'jenis_hunian' => $jenisHunian,
                'status' => $kondisi ?: 'aktif',
            ]);

            // 2024
            for ($i = 6; $i <= 17; $i++) {
                $val = isset($data[$i]) ? (float)$data[$i] : 0;
                if ($val > 0) {
                    $monthNum = $i - 5;
                    \App\Models\Iuran::create([
                        'warga_id' => $warga->id,
                        'kategori_iuran_id' => $category->id,
                        'bulan' => $months[$monthNum],
                        'tahun' => 2024,
                        'nominal' => $val,
                        'status_pembayaran' => 'lunas',
                    ]);
                }
            }

            // 2025
            for ($i = 19; $i <= 30; $i++) {
                $val = isset($data[$i]) ? (float)$data[$i] : 0;
                if ($val > 0) {
                    $monthNum = $i - 18;
                    \App\Models\Iuran::create([
                        'warga_id' => $warga->id,
                        'kategori_iuran_id' => $category->id,
                        'bulan' => $months[$monthNum],
                        'tahun' => 2025,
                        'nominal' => $val,
                        'status_pembayaran' => 'lunas',
                    ]);
                }
            }

            // 2026
            for ($i = 32; $i <= 43; $i++) {
                $val = isset($data[$i]) ? (float)$data[$i] : 0;
                if ($val > 0) {
                    $monthNum = $i - 31;
                    \App\Models\Iuran::create([
                        'warga_id' => $warga->id,
                        'kategori_iuran_id' => $category->id,
                        'bulan' => $months[$monthNum],
                        'tahun' => 2026,
                        'nominal' => $val,
                        'status_pembayaran' => 'lunas',
                    ]);
                }
            }
        }
        fclose($handle);
    }

    public function createWargaUsers()
    {
        set_time_limit(300);
        
        $wargas = \App\Models\Warga::whereNull('user_id')->get();
        $count = 0;

        foreach ($wargas as $warga) {
            $slug = \Illuminate\Support\Str::slug($warga->nama_lengkap, '');
            if (empty($slug)) {
                $slug = 'warga' . $warga->id;
            }
            $email = $slug . '@gmail.com';

            $i = 1;
            while (\App\Models\User::where('email', $email)->exists()) {
                $email = $slug . $i . '@gmail.com';
                $i++;
            }

            $user = \App\Models\User::create([
                'name' => $warga->nama_lengkap,
                'email' => $email,
                'password' => bcrypt('12345678'),
                'role' => 'user',
            ]);

            $warga->update(['user_id' => $user->id]);
            $count++;
        }

        return redirect()->route('admin.laporan.index')->with('success', "$count akun user berhasil dibuat!");
    }
}
