<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $fillable = ['tanggal', 'keterangan', 'total', 'tipe_transaksi'];

    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    public function kasKecil()
    {
        return $this->hasOne(KasKecil::class);
    }
}
