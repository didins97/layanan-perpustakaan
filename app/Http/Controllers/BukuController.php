<?php

namespace App\Http\Controllers;

use App\Buku;
use App\Rak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('buku.index',[
            'title' => 'Daftar Buku',
            'buku' => Buku::orderBy('penulis','asc')->paginate(4),
            'rak' => Rak::all()
        ]);
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
            'judul' => 'required|max:25|unique:buku',
            'isbn' => 'required|unique:buku',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'jumlah_buku' => 'required',
            // 'rak_id' => 'required',
            // 'gambar' => 'required|image|mimes:jpg,jpeg,png,svg'
        
        ],[
            'required' => 'atribute tidak boleh kosong',
            'unique' => 'atribute sudah terdaftar',
            'max' => 'karakter max 25',
            'image' => 'atribute harus gambar',
            // 'mimes' => 'atribute harus format jpg,jpeg,png atau svg'
        ]);

        //request file gambar jika ada tambahkan dan jika kosong
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = $file->store('img/buku');
        }

        // cek rak sudah full?
        $buku = Buku::where('rak_id', $request->rak_id)->get();
        $rak_buku = Rak::where('id', $request->rak_id)->first();

        // jumlahkan jmlah_buku didatabase dan tambahkan dengan request
        $jmlh_buku_rak = $buku->sum->jumlah_buku;
        $jmlh_buku_rak2 = $jmlh_buku_rak + $request->jumlah_buku;
        // echo $jmlh_buku_rak2, ' ' , $rak_buku->kapasitas;

        if ($jmlh_buku_rak2 > $rak_buku->kapasitas) 
        {
            toastr()->error('Rak Buku Sudah Full, Silakan Cek Rak Lain');
        } else {
            //insert to database buku
            Buku::create([
                'judul' => $request->judul,
                'isbn' => $request->isbn ?? '',
                'penulis' => $request->penulis,
                'penerbit' => $request->penerbit,
                'tahun_terbit' => $request->tahun_terbit,
                'jumlah_buku' => $request->jumlah_buku,
                'deskripsi' => $request->deskripsi ?? '',
                'rak_id' => $request->rak_id,
                'gambar' => $fileName ?? '',
                'created_at' => Carbon::now()
            ]);
            toastr()->success('Data Buku Berhasil Ditambahkan');
        }

        //session success 
        return redirect('buku');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buku = Buku::find($id);
        return view('buku.show',compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rak = Rak::all();
        $buku = Buku::find($id);
        return view('buku.edit',compact('buku', 'rak'));
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
        $buku = Buku::find($id);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            Storage::delete($buku->gambar);
            $fileName = $file->store('img/buku');
        }else{
            $fileName = $buku->gambar;
        }

        // cek rak sudah full?
        $buku_rak = Buku::where('rak_id', $request->rak_id)->whereNotIn('id',[$id])->get();
        $rak_buku = Rak::where('id', $request->rak_id)->first();

        $jmlh_buku_rak = $buku_rak->sum->jumlah_buku;
        $jmlh_buku_rak2 = $jmlh_buku_rak + $request->jumlah_buku;
        echo $jmlh_buku_rak2;

        if ($jmlh_buku_rak2 > $rak_buku->kapasitas) {
            toastr()->error('Rak Buku Sudah Full, Silakan Cek Rak Lain');
            return redirect('buku');
        } else {
            $buku->update([
                'judul' => $request->judul,
                'isbn' => $request->isbn,
                'penulis' => $request->penulis,
                'penerbit' => $request->penerbit,
                'tahun_terbit' => $request->tahun_terbit,
                'jumlah_buku' => $request->jumlah_buku,
                'rak_id' => $request->rak_id,
                'gambar' => $fileName ?? $request->file('gambar'),
                'updated_at' => Carbon::now()
            ]);
             return redirect('buku')->with('success','buku berhasil diupate');
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
        $buku = Buku::find($id);
        Storage::delete($buku->gambar);
        $buku->delete();
        return redirect('buku')->with('success','data berhasil dihapus');

    }

    // pencarian
    public function search(Request $request){

        $request->validate([
            'q' => 'required'
        ],[
            'required' => 'atribute tidak boleh kosong'
        ]);
        $cari = $request->q;
        $buku = Buku::where('judul','LIKE',"%$cari%")->orWhere('penulis','LIKE',"%$cari%")->paginate();

        return view('buku.index',[
            'title' => 'Daftar Buku',
            'buku' => $buku,
            'rak'  => Rak::all()
        ]);
    }
}
