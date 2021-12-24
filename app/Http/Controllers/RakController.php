<?php

namespace App\Http\Controllers;

use App\Buku;
use App\Rak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rak.index',[
            'title' => 'Rak Buku',
            'rak' => Rak::orderBy('nm_rak','asc')->paginate(4)
            // 'rak' => Rak::leftJoin('buku', 'rak.id', '=', 'buku.rak_id')->orderBy('nm_rak','asc')->get();
        ]);

        // $rak = Rak::orderBy('nm_rak','asc')->get();
        // foreach ($rak as $key) {
        //     foreach ($key->buku as $kay) {
        //         echo $kay->judul;
        //     }
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nm_rak' => 'required|unique:rak',
            'kapasitas' => 'required'
        ],[
            'required' => 'atribute tidak boleh kosong',
            'unique' => 'atribute sudah terdaftar'
            // 'mimes' => 'atribute harus format jpg,jpeg,png atau svg'
        ]);
        Rak::create([
            "nm_rak" => $request->nm_rak,
            "kapasitas" => $request->kapasitas
        ]);
        toastr()->success('Data Rak Berhasil Disimpan');
        return redirect('/rak-buku');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rak = Rak::find($id);
        return view('rak.detail', compact('rak'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rak = Rak::find($id);
        return view('rak.edit', compact('rak'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rak = Rak::find($id);
        if ($request->kapasitas < $rak->buku->sum('jumlah_buku')) {
            toastr()->error('Daya tampung harus lebih besar dari jumlah');
            return redirect('/rak-buku');
        } else {
            $rak->update([
                "nm_rak" => $request->nm_rak,
                "kapasitas" => $request->kapasitas
            ]);
            toastr()->success('Data Rak Berhasil Diupdate');
            return redirect('/rak-buku');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rak = Rak::find($id);
        $rak->delete();
        toastr()->success('Data Rak Berhasil Dihapus');
        return redirect('/rak-buku');
    }

    // pencarian
    public function search(Request $request)
    {
        $cari = $request->search;
        $rak = Rak::where('nm_rak','LIKE',"%$cari%")->paginate();

        return view('rak.search',[
            'title' => 'Daftar Rak',
            'rak' => $rak
        ]);
    }

}
