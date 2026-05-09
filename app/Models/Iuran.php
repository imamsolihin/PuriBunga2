<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $fillable = [
        'warga_id', 'kategori_iuran_id', 'bulan', 'tahun',
        'nominal', 'status_pembayaran', 'tanggal_bayar',
    ];

    protected $casts = ['tanggal_bayar' => 'date'];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function kategoriIuran()
    {
        return $this->belongsTo(KategoriIuran::class);
    }
}
