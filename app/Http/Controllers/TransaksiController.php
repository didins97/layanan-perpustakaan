<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Buku;
use App\Transaksi;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

use function GuzzleHttp\Promise\each;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Daftar Transaksi';
        $transaksi = Transaksi::with('anggota','buku','user')->orderBy('created_at','desc')->paginate(6);
        foreach ($transaksi as $key) {
            // return $key->buku->whereIn('id', [1,2]);
            foreach ($key->buku as $kay) {
                // $key->buku->whereIn('id', [$kay->id])->update(['jumlah_buku' => $kay->jumlah_buku -1]);
                echo $kay->jumlah_buku;
            }
        }
        // return view('transaksi.index',compact('title','transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('transaksi.create',[
            'title' => 'Tambah Transaksi',
            'buku' => Buku::orderBy('judul','asc')->get(),
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = ['required' => 'atribute tidak boleh kosong' ];
        $request->validate([
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
        ],$message);

        //ambil anggota id
        $anggota = Anggota::where('nim',$request->nim)->get();
        foreach ($anggota as $val) {
            $anggota_id = $val->id;
        }
        if (isset($anggota_id)) {
            // //tolak jika anggota sudah pinjam 
            if (Transaksi::where('anggota_id',$anggota_id)->exists() == true && Transaksi::where('status','pinjam')->exists() == true) {
                session()->flash('fail','sorry, masih ada buku yang anda pinjam');

                return redirect('transaksi');
                exit;
            }

            $transaksi = Transaksi::create([

                'anggota_id' => $anggota_id,
                'kode_transaksi' => Str::random(10),
                // 'buku_id' => $request->buku_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'pinjam',
                'ket' => $request->ket,
                'user_id' => Auth::user()->id
            ]);

            $transaksi->buku()->attach($request->buku_id);

            //jika transaksi dilakukan maka stock buku akan berkurang 
            foreach ($transaksi->buku as $key) {
                $transaksi->buku->whereIn('id',[$key->id])->update(['jumlah_buku' => $key->jumlah_buku -1]);
            }
            // $transaksi->buku->where('id',$transaksi->buku_id)->update(['jumlah_buku' => $transaksi->buku->jumlah_buku -1]);
            return redirect('transaksi')->with('success','transaksi anda berhasil!');
        } else {
            toastr()->error('Anggota Tidak Terdaftar, Periksa Kembali Nim Anggota');
            return redirect('transaksi');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = Transaksi::with('buku','anggota','user')->find($id);
        return view('transaksi.show',compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $transaksi = Transaksi::with('buku','anggota','user')->find($id);   
        $buku = Buku::get();
        return view('transaksi.edit',compact('transaksi','buku'));
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
        $message = ['required' => 'atribute tidak boleh kosong' ];
        $request->validate([
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
        ],$message);

        //ambil anggota id
        $anggota = Anggota::where('nim',$request->nim)->get();
        foreach ($anggota as $val) {
            $anggota_id = $val->id;
        }
        if (isset($anggota_id)) {
            //update transaksi
            $transaksi = Transaksi::find($id);
            $transaksi->update([
                'anggota_id' => $anggota_id ?? $transaksi->anggota_id,
                'kode_transaksi' => Str::random(10),
                'buku_id' => $request->buku_id ?? $transaksi->buku_id,
                'tgl_pinjam' => $request->tgl_pinjam ?? $transaksi->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali ?? $transaksi->rgl_kembali,
                'status' => $request->status ?? $transaksi->status,
                'ket' => $request->ket ?? $transaksi->ket,
                'user_id' => Auth::user()->id
            ]);

            //jika transaksi dilakukan maka stock buku akan berkurang 
            $transaksi->buku->whereIn('id',$transaksi->buku_id)->update(['jumlah_buku' => $transaksi->buku->jumlah_buku -1]);
            return redirect('transaksi')->with('success','transaksi berhasil diupdate');
        } else {
            toastr()->error('Anggota Tidak Terdaftar, Periksa Kembali Nim Anggota');
            return redirect('transaksi');
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
        Transaksi::find($id)->delete();
        return redirect('transaksi')->with('success','data berhasil dihapus');
    }

    public function search(Request $request){


        $request->validate([
            'q' => 'required'
        ]);
        //cari dengan kode_transaksi
        $cari = $request->q;

        $title = 'Daftar Transaksi';
       
        $transaksi = Transaksi::where('kode_transaksi','LIKE',"%$cari%")->paginate();
       
        return view('transaksi.index',compact('title','transaksi'));
        
    }

    public function filterDate() 
    {
        $title = 'Daftar Transaksi';
        if (request()->start_date || request()->end_date) {
            $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
            $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
            $transaksi = Transaksi::whereBetween('created_at',[$start_date,$end_date])->paginate();
        } else {
            $transaksi = Transaksi::latest()->paginate();
        };
        return view('transaksi.index',compact('transaksi', 'title'));
    }
    
}
