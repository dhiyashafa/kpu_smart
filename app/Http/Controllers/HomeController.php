<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Perhitungan;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $param['user'] = User::get();
        $param['kriteria'] = Kriteria::count();
        $param['anggota'] = Alternatif::count();
        $param['ranking'] = Perhitungan::select("hasil", DB::raw('count(hasil) total'))
        ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
        ->orderBy('hasil', 'desc')
        ->having('hasil', '>', '0')
        ->groupBy('hasil')->get();// mencari ranking dan total anggota                                
        $total = 0 ; 
        $nilai = '';
        foreach ($param['ranking'] as $key => $value) {
            $total += $value->total;
            if ($total >= 5) {
                $nilai = $value->hasil;
                break;// menjumlah total anggota menjadi 5
            }
            
        }
        
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")
        ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
        ->where('hasil','>=',$nilai)
        ->orderBy('hasil', 'desc')->get();// mengambil data perhitungan berdasarkan minimal $nilai dari ranking 
        $array_ranking = [];
        $ranking = 1;
        foreach ($param['ranking'] as $key => $value) {
            $array_ranking[] = $value->hasil;// merubah dari array list ke array 
        }
        foreach ($perhitungan as $key => $value) {// jika hasil ada didalam array rangking +1 hasilnya rankingnya
            $ranking = array_search($value->hasil, $array_ranking) + 1;
            $tampung[] = ['ranking' => $ranking, 'perhitungan' => $value];
        }
        $param['perhitungan'] = $tampung;
        return view('home', $param);
    }
}
