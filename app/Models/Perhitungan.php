<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Alternatif;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perhitungan extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'perhitungans';
    protected $fillable = [
    	// 'alternatifs_id', 'kriterias_id', 'hasil'
    	'alternatifs_id', 'hasil'
    ];

    // public function alternatif()
    // {
    //     return $this->hasMany(Alternatif::class, 'alternatif_id', 'id');
    //     // return $this->hasOne(Alternatif::class);
    // }

    // public function kriteria()
    // {
    //     return $this->hasMany(Kriteria::class, 'kriteria_id', 'id');
    // }
}
