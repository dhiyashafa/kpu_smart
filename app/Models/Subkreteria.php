<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Alternatif;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subkreteria extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'sub_kriteria';
    protected $fillable = [
    	'perhitungan_id','kriterias_id', 'nilai'
    ];
}
