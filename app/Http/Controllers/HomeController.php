<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Perhitungan;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

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
        $param['perhitungan'] = Perhitungan::select("perhitungans.*", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->orderBy('hasil', 'desc')->limit(5)->get();
        return view ('home',$param);
    }
}
