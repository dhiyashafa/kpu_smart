<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;


class KriteriaController extends Controller
{
    public function __construct()
    {
        // $this->kriterias = new kriteria();
    }

    public function index()
    {
        $kriteria = Kriteria::all();
        return view('kriteria.index', compact('kriteria'));
    }

    public function create(Request $request)
    {
        $total=Kriteria::sum('weight');
        if ($total == '100') {
            alert()->error('Gagal.',"Bobot Sudah Maximal !");

            return redirect()->route('kriteria.index');
        } else {
            return view('kriteria.create');
        }
        
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required','string'],
            'weight' => ['required','numeric']
        ]);

        Kriteria::create([
            'nama' => $request->get('nama'),
            'weight' => $request->get('weight')
        ]);

        $this->updateEigen();
        $total=Kriteria::sum('weight');
        alert()->success('Berhasil.',"Data telah ditambahkan! Dengan Total Bobot $total");

        return redirect()->route('kriteria.index');
    }

    public function show(Kriteria $kriteria)
    {
        return $kriteria;
    }

    public function edit($id)
    {
        $kriterias= Kriteria::findOrFail($id);
        return view('kriteria.edit', compact('kriterias'));
    }

    public function update(Request $request, $id)
    {
        Kriteria::find($id)->update([
            'nama' => $request->get('nama'),
            'weight' => $request->get('weight'),
        ]);

        $this->updateEigen();
        $total=Kriteria::sum('weight');
        alert()->success('Berhasil.',"Data telah diubah! Dengan Total Bobot $total");
        return redirect()->route('kriteria.index');
    }

    public function destroy($id)
    {
        Kriteria::find($id)->delete();
        $total=Kriteria::sum('weight');
        alert()->success('Berhasil.',"Data telah dihapus! Dengan Total Bobot $total");
        return redirect()->route('kriteria.index');
    }

    public function updateEigen()
    {
        $total=Kriteria::sum('weight');

        $kriteria=Kriteria::all();
        foreach ($kriteria as $kriterias) {
            $eigen=$kriterias->weight/$total;

            $kriterias->update([
                'eigen'=>$eigen
            ]);
        }
    }
}
