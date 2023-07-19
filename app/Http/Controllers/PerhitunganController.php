<?php

namespace App\Http\Controllers;

use App\Models\Perhitungan;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkreteria;
use App\Models\subperhitungans_na;
use Barryvdh\DomPDF\Facade\Pdf;

class PerhitunganController extends Controller
{
    private $title = "Perhitungan";
    public function __construct()
    {
        // echo 'CONTRAK';
        // die();
        // dd(Perhitungan::onlyTrashed()->get());
    }

    public function index()
    {
        $param['perhitungan'] = Perhitungan::select("perhitungans.*", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->orderBy('hasil', 'desc')->get();

        // dd(Perhitungan::onlyTrashed()->get());
        // dd($param['perhitungan']);
        $param['title'] = $this->title;

        return view('perhitungan.index', compact('param'));
    }

    public function create(Request $request)
    {
        $at = Alternatif::all(); //nama anggota
        $tanggapan = ['4' => 'Sangat Baik', '3' => 'Baik', '2' => 'Cukup', '1' => 'Kurang'];
        $param['kd'] = ['4' => 'Data Lengkap', '1' => 'Tidak Lengkap'];;;
        $param['tw'] = $tanggapan;
        $param['tm'] = $tanggapan;
        $param['at'] = $at;
        $param['title'] = $this->title;
        return view('perhitungan.create', compact('param'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'anggota' => ['required'],
            'Kel_data' => ['required'],
            'tes_tulis' => ['required'],
            'tes_wawancara' => ['required'],
            'tang_masya' => ['required']
        ]);
        // DB::beginTransaction();
        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 50 && $request->tes_tulis <= 69) {
            $tes_tulis = 2;
        }
        $request_custom = ['1' => $request->Kel_data, '2' => $tes_tulis, '3' => $request->tes_wawancara, '4' => $request->tang_masya];

        $perhitungan = Perhitungan::create([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);

        foreach ($request_custom as $key => $value) {
            $eigen = Kriteria::find($key)->eigen;
            $create_sub = ['kriterias_id' => $key, 'nilai' => $value, 'perhitungan_id' => $perhitungan->id];
            Subkreteria::create($create_sub);
            $max = Subkreteria::where('kriterias_id', $key)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $key)->min('nilai');
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            $total = $hasil * $eigen;
            $subperhitungans_na = ['kriterias_id' => $key, 'hasil' => $total, 'perhitungan_id' => $perhitungan->id];;
            subperhitungans_na::create($subperhitungans_na);
        }
        $nilai = subperhitungans_na::where('perhitungan_id', $perhitungan->id)->sum('hasil');
        $update = ['hasil' => $nilai];
        Perhitungan::find($perhitungan->id)->update($update);
        $this->update_hasil_all();
        alert()->success('Berhasil.',"Data Berhasil ditambahkan!");

        return redirect()->route('perhitungan.index');
    }
    public function update_hasil_all()
    {
        $perhitungan = Perhitungan::get();
        foreach ($perhitungan as $key => $value) {
            $sub = Subkreteria::where('perhitungan_id', $value->id)->get();
            foreach ($sub as $key_sub => $value_sub) {
                $eigen = Kriteria::find($value_sub->kriterias_id)->eigen;
                $max = Subkreteria::where('kriterias_id', $value_sub->kriterias_id)->max('nilai');
                $min = Subkreteria::where('kriterias_id', $value_sub->kriterias_id)->min('nilai');
                if ($max == $min) {
                    $hasil = 0;
                } else {
                    $hasil = ($value_sub->nilai - $min) / ($max - ($min) * (100 / 100));
                }
                $total = $hasil * $eigen;
                $update_subkreteria = ['hasil' => $total];;
                subperhitungans_na::where('perhitungan_id', $value->id)->where('kriterias_id', $value_sub->kriterias_id)->update($update_subkreteria);
            }
            $nilai = subperhitungans_na::where('perhitungan_id', $value->id)->sum('hasil');
            $update = ['hasil' => $nilai];
            Perhitungan::find($value->id)->update($update);
        }
    }

    public function show(Alternatif $alternatif)
    {
        return $alternatif;
    }

    public function edit($id)
    {
        $param['perhitungan'] = Perhitungan::findorfail($id);
        $param['kd_value'] = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '1')->first()->nilai;
        $param['tw_value'] = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '3')->first()->nilai;
        $param['tm_value'] = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '4')->first()->nilai;
        // dd($param);
        $at = Alternatif::all();
        $tanggapan = ['4' => 'Sangat Baik', '3' => 'Baik', '2' => 'Cukup', '1' => 'Kurang'];
        $param['kd'] = ['4' => 'Data Lengkap', '1' => 'Tidak Lengkap'];;
        $param['tw'] = $tanggapan;
        $param['tm'] = $tanggapan;
        $param['at'] = $at;
        $param['title'] = $this->title;

        return view('perhitungan.edit', compact('param'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'anggota' => ['required'],
            'Kel_data' => ['required'],
            'tes_tulis' => ['required'],
            'tes_wawancara' => ['required'],
            'tang_masya' => ['required']
        ]);

        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 50 && $request->tes_tulis <= 69) {
            $tes_tulis = 2;
        }
        Perhitungan::find($id)->update([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);
        $request_custom = ['1' => $request->Kel_data, '2' => $tes_tulis, '3' => $request->tes_wawancara, '4' => $request->tang_masya];
        Subkreteria::where('perhitungan_id', $id)->delete();
        foreach ($request_custom as $key => $value) {
            $create_sub = ['kriterias_id' => $key, 'nilai' => $value, 'perhitungan_id' => $id];
            Subkreteria::create($create_sub);
            $eigen = Kriteria::find($key)->eigen;
            $max = Subkreteria::where('kriterias_id', $key)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $key)->min('nilai');
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            $total = $hasil * $eigen;
            $update_subkreteria = ['hasil' => $total];;
            subperhitungans_na::where('perhitungan_id', $id)->where('kriterias_id', $key)->update($update_subkreteria);
        }
        $nilai = subperhitungans_na::where('perhitungan_id', $id)->sum('hasil');
        $update = ['hasil' => $nilai];
        Perhitungan::find($id)->update($update);
        $this->update_hasil_all();
        alert()->success('Berhasil.',"Data Berhasil diedit!");
        return redirect()->route('perhitungan.index');
    }
    public function delete_all()
    {

        subperhitungans_na::where('id', 'like', '%%')->delete();
        Subkreteria::where('id', 'like', '%%')->delete();
        Perhitungan::where('id', 'like', '%%')->delete();
        $this->update_hasil_all();
        alert()->success('Berhasil.',"Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }

    public function destroy($id)
    {
        Perhitungan::findorfail($id)->forceDelete();
        $this->update_hasil_all();
        alert()->success('Berhasil.',"Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }
    function export_pdf()
    {
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->orderBy('hasil', 'desc')->get();
        // dd($perhitungan->item);
        $pdf = Pdf::loadview('perhitungan.pdf',['perhitungan'=>$perhitungan]);
        return $pdf->stream();
    }
}