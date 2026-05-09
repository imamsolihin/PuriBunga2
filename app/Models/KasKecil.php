<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasKecil extends Model
{
    protected $fillable = ['tanggal', 'keterangan', 'jenis', 'nominal', 'jurnal_id'];

    protected $casts = ['tanggal' => 'date'];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
