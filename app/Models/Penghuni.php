<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    protected $fillable = [
        'warga_id', 'nama', 'nik', 'no_hp', 'hubungan',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
