<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\DB;

class ImportCsvCommand extends Command
{
    protected $signature = 'import:csv';
    protected $description = 'Import COA and Jurnal data from CSV files';

    public function handle()
    {
        $this->info('Starting import...');

        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JurnalDetail::truncate();
        Jurnal::truncate();
        Coa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tables truncated.');

        // 1. Import COA
        $coaFile = public_path('images/COA.csv');
        if (file_exists($coaFile)) {
            $handle = fopen($coaFile, 'r');
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (empty($data[0])) continue;
                
                $kode = trim($data[0]);
                $nama = trim($data[1]);
                
                // Map code to type
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
            $this->info('COA imported.');
        }

        // 2. Import Jurnal 2026
        $this->importJurnal(public_path('images/JURNAL_26.csv'));
        
        // 3. Import Jurnal 2025
        $this->importJurnal(public_path('images/JURNAL_25.csv'));

        $this->info('Import completed!');
    }

    private function importJurnal($filePath)
    {
        if (!file_exists($filePath)) {
            $this->warn("File not found: $filePath");
            return;
        }

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

            if (empty($data[1])) continue; // Skip if date is empty
            
            $no = $data[0];
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

            $jurnal = Jurnal::create([
                'tanggal' => $tanggal,
                'keterangan' => $uraian ?: 'Imported',
                'total' => max($dr, $cr),
                'tipe_transaksi' => $dr > 0 ? 'pemasukan' : 'pengeluaran',
            ]);

            if ($dr > 0) {
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $cashCoa->id,
                    'debit' => $dr,
                    'kredit' => 0,
                ]);
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $coa->id,
                    'debit' => 0,
                    'kredit' => $dr,
                ]);
            } else {
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $coa->id,
                    'debit' => $cr,
                    'kredit' => 0,
                ]);
                JurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'coa_id' => $cashCoa->id,
                    'debit' => 0,
                    'kredit' => $cr,
                ]);
            }
        }
        fclose($handle);
        $this->info("Imported $filePath");
    }
}
