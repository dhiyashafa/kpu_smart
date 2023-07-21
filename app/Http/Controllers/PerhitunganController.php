<?php

namespace App\Http\Controllers;

use App\Models\Perhitungan;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Subkreteria;
use App\Models\subperhitungans_na;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    private $title = "Perhitungan";
    public function __construct()
    {
        // dd(Perhitungan::onlyTrashed()->get());
    }

    public function index()
    {
        $tampung = [];
        // mengambil ranking berdasarkan hasil paling tinngi
        $param['ranking'] = Perhitungan::select("hasil", DB::raw('count(hasil) total'))
        ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
        ->orderBy('hasil', 'desc')
        ->having('hasil', '>', '0')
        ->groupBy('hasil')->get();

        // mengambil data perhitungan berdasarkan hasil paling tinngi
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")
        ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
        ->orderBy('hasil', 'desc')->get();
        $array_ranking = [];
        $ranking = 1;
        foreach ($param['ranking'] as $key => $value) {
            // merubah dari array list ke array 
            $array_ranking[] = $value->hasil;
        }
        foreach ($perhitungan as $key => $value) {
            // jika hasil ada didalam array rangking +1 hasilnya rankingnya
            $ranking = array_search($value->hasil, $array_ranking) + 1;
            $tampung[] = ['ranking' => $ranking, 'perhitungan' => $value];
        }
        $param['perhitungan'] = $tampung;
        $param['title'] = $this->title;

        return view('perhitungan.index', compact('param'));
    }

    public function create(Request $request)
    {
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
            return redirect()->route('perhitungan.index');
        }
        $at = Alternatif::all(); //nama anggota
        $tanggapan = ['4' => 'Sangat Baik', '3' => 'Baik', '2' => 'Cukup', '1' => 'Kurang'];
        $param['kd'] = ['4' => 'Data Lengkap', '1' => 'Tidak Lengkap'];
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
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
            return redirect()->route('perhitungan.index');
        }

        // DB::beginTransaction();
        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 50 && $request->tes_tulis <= 69) {
            $tes_tulis = 2;
        }

        $tes_wawancara = 1;
        if ($request->tes_wawancara >= 90) {
            $tes_wawancara = 4;
        } elseif ($request->tes_wawancara >= 70 && $request->tes_wawancara <= 89) {
            $tes_wawancara = 3;
        } elseif ($request->tes_wawancara >= 50 && $request->tes_wawancara <= 69) {
            $tes_wawancara = 2;
        }
        $request_custom = ['Kelengkapan_Data' => $request->Kel_data, 'Tes_Tulis' => $tes_tulis, 'Tes_Wawancara' => $tes_wawancara, 'Tanggapan_Masyarakat' => $request->tang_masya];
        $perhitungan = Perhitungan::create([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);

        foreach ($request_custom as $key => $value) {
            $where = explode('_', $key);
            $get_kreteria = Kriteria::where('nama', $where[0] . ' ' . $where[1])->first();
            $eigen = $get_kreteria->eigen;
            $create_sub = ['kriterias_id' => $get_kreteria->id, 'nilai' => $value, 'perhitungan_id' => $perhitungan->id];
            Subkreteria::create($create_sub);
            $max = Subkreteria::where('kriterias_id', $get_kreteria->id)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $get_kreteria->id)->min('nilai');
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            $total = $hasil * $eigen;
            $subperhitungans_na = ['kriterias_id' => $get_kreteria->id, 'hasil' => $total, 'perhitungan_id' => $perhitungan->id];;
            subperhitungans_na::create($subperhitungans_na);
        }
        // DB::rollBack();

        $nilai = subperhitungans_na::where('perhitungan_id', $perhitungan->id)->sum('hasil');
        $nilai_expload = explode('.', $nilai);
        if (count($nilai_expload) > 1) {
            $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
        }
        $update = ['hasil' => $nilai];
        Perhitungan::find($perhitungan->id)->update($update);
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil ditambahkan!");

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
            $nilai = subperhitungans_na::where('perhitungan_id', $value->id)->sum('hasil') * 100;

            $nilai_expload = explode('.', $nilai);
            if (count($nilai_expload) > 1) {
                $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
            }
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
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
            return redirect()->route('perhitungan.index');
        }

        $param['perhitungan'] = Perhitungan::findorfail($id);
        $kd_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '1')->first();
        $param['kd_value'] = $kd_value ? $kd_value->nilai : '0';
        $tw_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '3')->first();
        $param['tw_value'] = $tw_value ? $tw_value->nilai : '0';
        $tm_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '4')->first();

        $param['tm_value'] = $tm_value ? $tm_value->nilai : '0';
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
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
            return redirect()->route('perhitungan.index');
        }
        // DB::beginTransaction();
        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 50 && $request->tes_tulis <= 69) {
            $tes_tulis = 2;
        }
        $tes_wawancara = 1;
        if ($request->tes_wawancara >= 90) {
            $tes_wawancara = 4;
        } elseif ($request->tes_wawancara >= 70 && $request->tes_wawancara <= 89) {
            $tes_wawancara = 3;
        } elseif ($request->tes_wawancara >= 50 && $request->tes_wawancara <= 69) {
            $tes_wawancara = 2;
        }
        Perhitungan::find($id)->update([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);
        // $request_custom = ['1' => $request->Kel_data, '2' => $tes_tulis, '3' => $tes_wawancara, '4' => $request->tang_masya];
        $request_custom = ['Kelengkapan_Data' => $request->Kel_data, 'Tes_Tulis' => $tes_tulis, 'Tes_Wawancara' => $tes_wawancara, 'Tanggapan_Masyarakat' => $request->tang_masya];
        Subkreteria::where('perhitungan_id', $id)->delete();
        foreach ($request_custom as $key => $value) {
            $where = explode('_', $key);
            $get_kreteria = Kriteria::where('nama', $where[0] . ' ' . $where[1])->first();
            $create_sub = ['kriterias_id' => $get_kreteria->id, 'nilai' => $value, 'perhitungan_id' => $id];
            Subkreteria::create($create_sub);
            $eigen = $get_kreteria->eigen;
            $max = Subkreteria::where('kriterias_id', $get_kreteria->id)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $get_kreteria->id)->min('nilai');
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            $total = $hasil * $eigen;
            $update_subkreteria = ['hasil' => $total];;
            $check_data_subperhitungans_na = subperhitungans_na::where('perhitungan_id', $id)->get();
            if ($check_data_subperhitungans_na->count() == 4) {
                subperhitungans_na::where('perhitungan_id', $id)->where('kriterias_id', $get_kreteria->id)->update($update_subkreteria);
            } else {
                $subperhitungans_na = ['kriterias_id' => $get_kreteria->id, 'hasil' => $total, 'perhitungan_id' => $id];;
                subperhitungans_na::create($subperhitungans_na);
            }
        }
        $nilai = subperhitungans_na::where('perhitungan_id', $id)->sum('hasil') * 100;


        // DB::rollBack();
        $nilai_expload = explode('.', $nilai);
        if (count($nilai_expload) > 1) {
            $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
        }
        $update = ['hasil' => $nilai];
        Perhitungan::find($id)->update($update);
        $this->update_hasil_all();
        // DB::commit();
        alert()->success('Berhasil.', "Data Berhasil diedit!");
        return redirect()->route('perhitungan.index');
    }
    public function delete_all()
    {
        // $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        // if ($check_data->count() != 4) {
        //     alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
        //     return redirect()->route('perhitungan.index');
        // }
        subperhitungans_na::where('id', 'like', '%%')->delete();
        Subkreteria::where('id', 'like', '%%')->delete();
        Perhitungan::where('id', 'like', '%%')->delete();
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }

    public function destroy($id)
    {
        // $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        // if ($check_data->count() != 4) {
        //     alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebihdahulu!");
        //     return redirect()->route('perhitungan.index');
        // }
        Perhitungan::findorfail($id)->forceDelete();
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }
    function export_pdf()
    {
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->orderBy('hasil', 'desc')->get();
        // dd($perhitungan->item);
        $pdf = Pdf::loadview('perhitungan.pdf', ['perhitungan' => $perhitungan]);
        return $pdf->stream();
    }
}
