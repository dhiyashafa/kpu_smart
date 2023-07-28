<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';
    protected $fillable = [
    	'no_pendaftaran', 'nama', 'alamat', 'nomer','kelamin','email'
    ];

    // public function perhitungan()
    // {
    //     return $this->belongsTo(Perhitungan::class, 'perhitungan_id', 'id');
    // }
}
