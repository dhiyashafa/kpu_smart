<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Alternatif;
use Illuminate\Database\Eloquent\SoftDeletes;

class subperhitungans_na extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'subperhitungans_na';
    protected $fillable = [
    	'perhitungan_id','kriterias_id', 'hasil'
    ];
}
