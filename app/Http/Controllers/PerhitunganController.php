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
use stdClass;

class PerhitunganController extends Controller
{
    private $title = "Perhitungan";
    public function __construct()
    {
        // mengambil data yang didetele tidak permanaen
        // Perhitungan::onlyTrashed()->get();
    }

    public function index()
    {
        $tampung = [];
        // mencari ranking dan total anggota 
        $param['ranking'] = Perhitungan::select("hasil", DB::raw('count(hasil) total'))
            ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
            ->orderBy('hasil', 'desc')
            ->groupBy('hasil')->get();

        // mengambil semua data perhitungan
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")
            ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
            ->orderBy('hasil', 'desc')->get();

        $array_ranking = [];
        // merubah dari array list ke array
        foreach ($param['ranking'] as $key => $value) {
            $array_ranking[] = $value->hasil;
        }
        // jika hasil ada didalam array rangking +1 hasilnya rankingnya
        foreach ($perhitungan as $key => $value) {
            // jika ada data tidak ditemukan di ranking otomatis mengambil ranking 1 karena array_search($value->hasil, $array_ranking) hasilnya kosong kalau di + 1 otomatis hasilya 1
            $ranking = array_search($value->hasil, $array_ranking) + 1;
            $tampung[] = ['ranking' => $ranking, 'perhitungan' => $value];
        }

        // param berfungsi untuk beberapa variabel menjadi 1 untuk dikirim ke view
        $param['perhitungan'] = $tampung;
        $param['title'] = $this->title;

        return view('perhitungan.index', compact('param'));
    }

    public function create(Request $request)
    {
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebih dahulu!");
            return redirect()->route('perhitungan.index');
        }
        $at = Alternatif::all();
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
        // mengambil data Kriteria berdasarkan nama
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebih dahulu!");
            return redirect()->route('perhitungan.index');
        }

        // untuk mencari nilai dari range
        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 80 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 79) {
            $tes_tulis = 2;
        }
        // untuk mencari nilai dari range
        $tes_wawancara = 1;
        if ($request->tes_wawancara >= 90) {
            $tes_wawancara = 4;
        } elseif ($request->tes_wawancara >= 80 && $request->tes_wawancara <= 89) {
            $tes_wawancara = 3;
        } elseif ($request->tes_wawancara >= 70 && $request->tes_wawancara <= 79) {
            $tes_wawancara = 2;
        }
        // untuk melakukkan perulangan biar tidak 1 persatu
        $request_custom = ['Kelengkapan_Data' => $request->Kel_data, 'Tes_Tulis' => $tes_tulis, 'Tes_Wawancara' => $tes_wawancara, 'Tanggapan_Masyarakat' => $request->tang_masya];
        // untuk mendapatakan id perhitungans
        $perhitungan = Perhitungan::create([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);

        foreach ($request_custom as $key => $value) {
            // untuk memecah underscore menjadi array
            $where = explode('_', $key);

            //di convert ke spasi untuk mengambil data kreteria
            $get_kreteria = Kriteria::where('nama', $where[0] . ' ' . $where[1])->first();
            $eigen = $get_kreteria->eigen;

            $create_sub = ['kriterias_id' => $get_kreteria->id, 'nilai' => $value, 'perhitungan_id' => $perhitungan->id];
            // untuk menambahkan Subkreteria
            Subkreteria::create($create_sub);

            //mencari nilai max dan min dari subkreteria
            $max = Subkreteria::where('kriterias_id', $get_kreteria->id)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $get_kreteria->id)->min('nilai');

            // Jika Max dan Min sama otomatis dibuat 0 biar tidak error saat perhitungannya
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            // untuk menghitung subperhitungan_na
            $total = $hasil * $eigen;
            $subperhitungans_na = ['kriterias_id' => $get_kreteria->id, 'hasil' => $total, 'perhitungan_id' => $perhitungan->id];;
            // untuk menambahkan subperhitungan_na
            subperhitungans_na::create($subperhitungans_na);
        }

        $nilai = subperhitungans_na::where('perhitungan_id', $perhitungan->id)->sum('hasil');
        // jika nilai dibelakang titik lebih dari 3 otomatis menjadi cuma 3 
        $nilai_expload = explode('.', $nilai);
        if (count($nilai_expload) > 1) {
            $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
        }
        // untuk update total akhir dari perhitungan
        $update = ['hasil' => $nilai];
        Perhitungan::find($perhitungan->id)->update($update);
        // untuk mengupdate semua perhitungan biar tidak mengedit perhitungannya
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil ditambahkan!");

        return redirect()->route('perhitungan.index');
    }
    public function update_hasil_all()
    {
        // mengambil semua perhitungan
        $perhitungan = Perhitungan::get();
        foreach ($perhitungan as $key => $value) {
            // mengambil semua subkreteria
            $sub = Subkreteria::where('perhitungan_id', $value->id)->get();
            foreach ($sub as $key_sub => $value_sub) {
                // mengambil data kreteria
                $eigen = Kriteria::find($value_sub->kriterias_id)->eigen;
                //mencari nilai max dan min dari subkreteria
                $max = Subkreteria::where('kriterias_id', $value_sub->kriterias_id)->max('nilai');
                $min = Subkreteria::where('kriterias_id', $value_sub->kriterias_id)->min('nilai');
                // Jika Max dan Min sama otomatis dibuat 0 biar tidak error saat perhitungannya
                if ($max == $min) {
                    $hasil = 0;
                } else {
                    $hasil = ($value_sub->nilai - $min) / ($max - ($min) * (100 / 100));
                }
                // untuk menghitung subperhitungan_na
                $total = $hasil * $eigen;
                // untuk menambahkan subperhitungan_na
                $update_subkreteria = ['hasil' => $total];;
                subperhitungans_na::where('perhitungan_id', $value->id)->where('kriterias_id', $value_sub->kriterias_id)->update($update_subkreteria);
            }
            // untuk menghitung subperhitungan_na dan di kali 100
            $nilai = subperhitungans_na::where('perhitungan_id', $value->id)->sum('hasil') * 100;
            // jika nilai dibelakang titik lebih dari 3 otomatis menjadi cuma 3 
            $nilai_expload = explode('.', $nilai);
            if (count($nilai_expload) > 1) {
                $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
            }
            // untuk update total akhir dari perhitungan
            $update = ['hasil' => $nilai];
            Perhitungan::find($value->id)->update($update);
        }
    }

    public function show($id)
    {
        // return $alternatif;
        // dd();
        $param['perhitungan'] = Perhitungan::select("perhitungans.hasil", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->where('perhitungans.id',$id)->first();
        $param['subkreteria'] = Subkreteria::select("sub_kriteria.perhitungan_id", "sub_kriteria.nilai", "kriterias.nama")->leftJoin('kriterias', 'sub_kriteria.kriterias_id', '=', 'kriterias.id')->leftJoin('alternatifs', 'sub_kriteria.kriterias_id', '=', 'alternatifs.id')->where('sub_kriteria.perhitungan_id',$id)->get();

        return view('perhitungan.show', compact('param'));
    }

    public function edit($id)
    {
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebih dahulu!");
            return redirect()->route('perhitungan.index');
        }
        // untuk *_value mengambil data dari database
        $param['perhitungan'] = Perhitungan::findorfail($id);
        $kd_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '1')->first();
        $param['kd_value'] = $kd_value ? $kd_value->nilai : '0';
        $tw_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '3')->first();
        $param['tw_value'] = $tw_value ? $tw_value->nilai : '0';
        $tm_value = Subkreteria::where('perhitungan_id', $id)->where('kriterias_id', '4')->first();

        $param['tm_value'] = $tm_value ? $tm_value->nilai : '0';
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

        // mengambil data Kriteria berdasarkan nama
        $check_data = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        if ($check_data->count() != 4) {
            alert()->error('Gagal.', "Data Kreteria Tidak Lengkap, Silakan ditambahkan terlebih dahulu!");
            return redirect()->route('perhitungan.index');
        }
        // untuk mencari nilai dari range
        $tes_tulis = 1;
        if ($request->tes_tulis >= 90) {
            $tes_tulis = 4;
        } elseif ($request->tes_tulis >= 80 && $request->tes_tulis <= 89) {
            $tes_tulis = 3;
        } elseif ($request->tes_tulis >= 70 && $request->tes_tulis <= 79) {
            $tes_tulis = 2;
        }

        // untuk mencari nilai dari range
        $tes_wawancara = 1;
        if ($request->tes_wawancara >= 90) {
            $tes_wawancara = 4;
        } elseif ($request->tes_wawancara >= 80 && $request->tes_wawancara <= 89) {
            $tes_wawancara = 3;
        } elseif ($request->tes_wawancara >= 70 && $request->tes_wawancara <= 79) {
            $tes_wawancara = 2;
        }
        // untuk update data anggota di perhitungan
        Perhitungan::find($id)->update([
            'alternatifs_id' => $request->anggota,
            'hasil' => '0',
        ]);
        // untuk melakukkan perulangan biar tidak 1 persatu
        $request_custom = ['Kelengkapan_Data' => $request->Kel_data, 'Tes_Tulis' => $tes_tulis, 'Tes_Wawancara' => $tes_wawancara, 'Tanggapan_Masyarakat' => $request->tang_masya];
        // untuk menghapus Subkreteria
        Subkreteria::where('perhitungan_id', $id)->delete();
        foreach ($request_custom as $key => $value) {
            // untuk memecah underscore menjadi array
            $where = explode('_', $key);
            //di convert ke spasi untuk mengambil data kreteria
            $get_kreteria = Kriteria::where('nama', $where[0] . ' ' . $where[1])->first();
            // untuk menambahkan Subkreteria
            $create_sub = ['kriterias_id' => $get_kreteria->id, 'nilai' => $value, 'perhitungan_id' => $id];
            Subkreteria::create($create_sub);
            $eigen = $get_kreteria->eigen;

            //mencari nilai max dan min dari subkreteria
            $max = Subkreteria::where('kriterias_id', $get_kreteria->id)->max('nilai');
            $min = Subkreteria::where('kriterias_id', $get_kreteria->id)->min('nilai');

            // Jika Max dan Min sama otomatis dibuat 0 biar tidak error saat perhitungannya
            if ($max == $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - ($min) * (100 / 100));
            }
            // untuk menghitung subperhitungan_na
            $total = $hasil * $eigen;
            $update_subkreteria = ['hasil' => $total];;

            $check_data_subperhitungans_na = subperhitungans_na::where('perhitungan_id', $id)->get();
            // untuk mengecek data subperhitungans_na jika data lengkap cuma di update jika tidak akan di tambahkan
            if ($check_data_subperhitungans_na->count() == 4) {
                subperhitungans_na::where('perhitungan_id', $id)->where('kriterias_id', $get_kreteria->id)->update($update_subkreteria);
            } else {
                // untuk menambahkan subperhitungan_na
                $subperhitungans_na = ['kriterias_id' => $get_kreteria->id, 'hasil' => $total, 'perhitungan_id' => $id];;
                subperhitungans_na::create($subperhitungans_na);
            }
        }

        $nilai = subperhitungans_na::where('perhitungan_id', $id)->sum('hasil') * 100;

        // jika nilai dibelakang titik lebih dari 3 otomatis menjadi cuma 3 
        $nilai_expload = explode('.', $nilai);
        if (count($nilai_expload) > 1) {
            $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
        }
        // untuk mengupdate total nilai diperhitungans
        $update = ['hasil' => $nilai];
        Perhitungan::find($id)->update($update);
        // untuk mengupdate semua perhitungan biar tidak mengedit perhitungannya
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil diedit!");
        return redirect()->route('perhitungan.index');
    }
    public function delete_all()
    {
        // untuk menghpus tapi masih ada historynya
        subperhitungans_na::where('id', 'like', '%%')->delete();
        Subkreteria::where('id', 'like', '%%')->delete();
        Perhitungan::where('id', 'like', '%%')->delete();
        // untuk mengupdate semua perhitungan biar tidak mengedit perhitungannya
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }

    public function destroy($id)
    {
        // untuk menghpus permanen
        Perhitungan::findorfail($id)->forceDelete();
        // untuk mengupdate semua perhitungan biar tidak mengedit perhitungannya
        $this->update_hasil_all();
        alert()->success('Berhasil.', "Data Berhasil di hapus !");
        return redirect()->route('perhitungan.index');
    }
    function export_pdf()
    {
        // mengambil data perhitungan
        $perhitungan = Perhitungan::select("perhitungans.*", "alternatifs.nama")->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')->orderBy('hasil', 'desc')->get();

        $pdf = Pdf::loadview('perhitungan.pdf', ['perhitungan' => $perhitungan]);
        return $pdf->stream();
    }
    function semua($id = 0)
    {
        $tampung = [];

        // kriteria 
        $param['kriteria'] = Kriteria::whereIn('nama', ['Kelengkapan Data', 'Tes Tulis', 'Tes Wawancara', 'Tanggapan Masyarakat'])->get();
        // mencari ranking semua anggota
        $param['ranking'] = Perhitungan::select("hasil", DB::raw('count(hasil) total'))
            ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
            ->orderBy('hasil', 'desc')
            ->groupBy('hasil')->get();

        // mengambil semua data perhitungan
        $perhitungan = Perhitungan::select("perhitungans.hasil", "perhitungans.id", "alternatifs.nama")
            ->leftJoin('alternatifs', 'perhitungans.alternatifs_id', '=', 'alternatifs.id')
            ->orderBy('hasil', 'desc')->get();

        // mengmbil semuadata di tabel subkreteria karena untuk mencari nilai mix dan maxnya
        $subkreteria = Subkreteria::select("sub_kriteria.perhitungan_id", "sub_kriteria.nilai", "kriterias.nama")->leftJoin('kriterias', 'sub_kriteria.kriterias_id', '=', 'kriterias.id')->get();

        // jika idnya tidak 0 maka menampilkan data setiap anggota , jika id 0 maka menampilkan semua anggota
        if ($id != 0) {



            // mengmbil data di tabel subperhitungans_na berdasarkan perhitungan_id
            $subperhitungans_na = subperhitungans_na::select("subperhitungans_na.perhitungan_id", "subperhitungans_na.hasil", "kriterias.nama")->leftJoin('kriterias', 'subperhitungans_na.kriterias_id', '=', 'kriterias.id')->where('subperhitungans_na.perhitungan_id', $id)->get();

            // untuk memasukkan dari variabel $subkreteria ke dalam $perhitungan
            $data_perhitungan = $this->proses_hasil($perhitungan, $subkreteria);

            // untuk memasukkan dari variabel $subperhitungans_na ke dalam $perhitungan , untuk 'subperhitungans_na' berfungsi untuk membedakan antara subkreteria dengan subperhitungans_na
            $data_perhitungan = $this->proses_hasil($perhitungan, $subperhitungans_na, 'subperhitungans_na');


            $array_ranking = [];
            // merubah dari array list ke array
            foreach ($param['ranking'] as $key => $value) {
                $array_ranking[] = $value->hasil;
            }
            // jika hasil ada didalam array rangking +1 hasilnya rankingnya
            foreach ($data_perhitungan as $key => $value) {
                // jika ada data tidak ditemukan di ranking otomatis mengambil ranking 1 karena array_search($value->hasil, $array_ranking) hasilnya kosong kalau di + 1 otomatis hasilya 1
                $ranking = array_search($value->hasil, $array_ranking) + 1;
                $tampung[] = ['ranking' => $ranking, 'perhitungan' => $value];
            }
        } else {

            // mengmbil data di tabel subperhitungans_na berdasarkan perhitungan_id
            $subperhitungans_na = subperhitungans_na::select("subperhitungans_na.perhitungan_id", "subperhitungans_na.hasil", "kriterias.nama")->leftJoin('kriterias', 'subperhitungans_na.kriterias_id', '=', 'kriterias.id')->get();
            // untuk memasukkan dari variabel $subkreteria ke dalam $perhitungan
            $data_perhitungan = $this->proses_hasil($perhitungan, $subkreteria);
            // untuk memasukkan dari variabel $subperhitungans_na ke dalam $perhitungan , untuk 'subperhitungans_na' berfungsi untuk membedakan antara subkreteria dengan subperhitungans_na
            $data_perhitungan = $this->proses_hasil($data_perhitungan, $subperhitungans_na, 'subperhitungans_nai');
            // dd($data_perhitungan);

            // dd($data_perhitungan);
            $array_ranking = [];
            // merubah dari array list ke array
            foreach ($param['ranking'] as $key => $value) {
                $array_ranking[] = $value->hasil;
            }
            // jika hasil ada didalam array rangking +1 hasilnya rankingnya
            foreach ($data_perhitungan as $key => $value) {
                // jika ada data tidak ditemukan di ranking otomatis mengambil ranking 1 karena array_search($value->hasil, $array_ranking) hasilnya kosong kalau di + 1 otomatis hasilya 1
                $ranking = array_search($value->hasil, $array_ranking) + 1;
                $tampung[] = ['ranking' => $ranking, 'perhitungan' => $value];
            }
        }

        // mengambil semua data perhitungan

        $param['id'] = $id;
        $param['sub_kriteria'] = $tampung;
        $param['title'] = $this->title;

        return view('perhitungan.semua', compact('param'));
    }
    function proses_hasil($perhitungan, $hasil, $title = '')
    {
        // perulangan pertama untuk menyamakan perhitungan.id dengan perhitungan_id yang ada dalam tabel subkreteria atau subperhitungans_na
        foreach ($perhitungan as $key_perhitungan => $value_perhitungan) {

            // jika $title kosong maka akan membuat variabel baru dengan nama subkreteria jika tidak kosong maka membuat variabel baru dengan nama subperhitungans_na
            if (!empty($title)) {
                // membuat array object
                $perhitungan[$key_perhitungan]->subperhitungans_na = new stdClass;
            } else {
                // membuat array object
                $perhitungan[$key_perhitungan]->subkreteria = new stdClass;
            }
            // pengulangan ke dua untuk memasukkan dari data  subkreteria atau subperhitungans_na kedalam perhitungan 
            foreach ($hasil as $key => $value) {
                // jika id perhitungan sama dengan perhitungan_id maka data akan di masukkan ke dalam perhitungan
                if ($value_perhitungan->id == $value->perhitungan_id) {
                    // untuk memecah spasi menjadi array
                    $nama = explode(' ', $value->nama);
                    // untuk menggabungkan array kreteria yang awalnya $nama[0]= kelengkapan , dan $nama[1]=Data jadi kelengkapanData
                    $variabel = $nama[0] . $nama[1];
                    // jika title tidak kosong maka akan dimasukkan kedalam variabel subperhitungans_na, jika kosong akan dimasukkan kedalam subkreteria
                    if (!empty($title)) {
                        // jika nilai dibelakang titik lebih dari 3 otomatis menjadi cuma 3 
                        $nilai_expload = explode('.', $value->hasil);
                        if (count($nilai_expload) > 1) {
                            $nilai = $nilai_expload[0] . '.' . substr($nilai_expload[1], 0, 3);
                            // untuk memasukkan nilai kedalam subperhitungans_na dengan nama $variabel yang mengambil nama kreteria tanpa spasi
                            $perhitungan[$key_perhitungan]->subperhitungans_na->$variabel = $nilai;
                        } else {
                            // untuk memasukkan nilai kedalam subperhitungans_na dengan nama $variabel yang mengambil nama kreteria tanpa spasi
                            $perhitungan[$key_perhitungan]->subperhitungans_na->$variabel = $value->hasil;
                        }
                    } else {
                        // untuk memasukkan nilai kedalam subkreteria dengan nama $variabel yang mengambil nama kreteria tanpa spasi
                        // awalnya $perhitungan[$key_perhitungan]->subkreteria->kelengkapan Data jadi $perhitungan[$key_perhitungan]->subkreteria->kelengkapanData karena variabel tidak boleh ada spasi
                        $perhitungan[$key_perhitungan]->subkreteria->$variabel = $value->nilai;
                    }
                }
            }
        }
        // untuk mengembalikan data yang sudah dimasukkan
        return $perhitungan;
    }
}
