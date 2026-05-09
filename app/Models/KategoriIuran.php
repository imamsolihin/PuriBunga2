<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriIuran extends Model
{
    protected $fillable = ['nama_kategori', 'nominal_default'];

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
}
