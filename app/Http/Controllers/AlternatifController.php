<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;


class AlternatifController extends Controller
{
    public function __construct()
    {
        // $this->kriterias = new kriteria();
    }

    public function index()
    {
        $alternatif = Alternatif::all();
        return view('alternatif.index', compact('alternatif'));
    }

    //     $kriteria = [
    //         'kriteria' => $this->kriteria->allData(),
    //     ];
    //     return view('user.kriteria', $kriteria);
    // }

    public function create(Request $request)
    {
        // $request->validate([
        //     'nama' => ['required','string'],
        //     'weight' => ['required','numeric']
        //     'jenis' => 'required',
        // ]);
        return view('alternatif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => ['required','unique:alternatifs','numeric'],
            'nama' => ['required','string'],
            'kelamin' => ['required'],
            'alamat' => ['nullable'],
            'email' => ['email','required']

            // 'jenis' => 'required',
        ]);

        Alternatif::create([
            'no_pendaftaran' => $request->get('no_pendaftaran'),
            'nama' => $request->get('nama'),
            'kelamin' => $request->get('kelamin'),
            'alamat' => $request->get('alamat'),
            'email' => $request->get('email')
        ]);
        alert()->success('Berhasil.',"Data Berhasil ditambahkan!");
        return redirect()->route('alternatif.index');
    }

    public function show(Alternatif $alternatif)
    {
        return $alternatif;
    }

    public function edit( $id)
    {
        $alternatifs= Alternatif::find($id);

        return view('alternatif.edit', compact('alternatifs'));
    }

    public function update($id , Request $request)
    {
        $request->validate([
            'no_pendaftaran' => ['required','numeric'],
            'nama' => ['required','string'],
            'kelamin' => ['required'],
            'alamat' => ['nullable'],
            'email' => ['email','required']
        ]);

        Alternatif::find($id)->update($request->all());
        alert()->success('Berhasil.',"Data Berhasil diedit!");
        return redirect()->route('alternatif.index');
    }

    public function destroy($id)
    {
        Alternatif::find($id)->delete();
        alert()->success('Berhasil.','Data telah dihapus!');
        return redirect()->route('alternatif.index');
    }
}
