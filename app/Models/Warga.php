<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'blok_rumah', 'nomor_rumah', 'no_hp', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
}
