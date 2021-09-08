<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Telepon;
use App\Models\JenisPeminjam;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\User;
use Session;
use Storage;

class PeminjamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $batas = 5;
        $data_peminjam = Peminjam::orderBy('id','asc')->paginate($batas);
        $no = 0;
        $jumlah_peminjam = Peminjam::count();
        return view('peminjams.index', compact('data_peminjam','no','jumlah_peminjam'));
    }

    public function create(){
        $user = User::find(\DB::table('users')->max('id'));
        $name = $user['name'];
        $user_id = $user['id'];
        $list_jenis_peminjam = JenisPeminjam::pluck('nama_jenis_peminjam','id_jenis_peminjam');
        return view('peminjams/create', compact('list_jenis_peminjam', 'user_id', 'name'));
    }
    public function store(Request $request){
        $this->validate($request, [
            'kode_peminjam'=>'required|string',
            'nama_peminjam'=>'required|string|max:40',
            'tgl_lahir'=>'required|date',
            'alamat'=>'required|string'
        ]);

        $this->validate($request,[
            'foto_peminjam'=>'required|image|mimes:jpeg,jpg,png',
        ]);
        $foto_peminjam = $request->foto_peminjam;
        $nama_file = time().'.'.$foto_peminjam->getClientOriginalExtension();
        $foto_peminjam->move('foto_peminjam/',$nama_file);
        
        $peminjam = new Peminjam;
        $peminjam->kode_peminjam = $request->kode_peminjam;
        $peminjam->nama_peminjam = $request->nama_peminjam;
        $peminjam->tgl_lahir = $request->tgl_lahir;
        $peminjam->alamat = $request->alamat;
        $peminjam->id_jenis_peminjam=$request->id_jenis_peminjam;
        $peminjam->foto_peminjam=$nama_file;
        $peminjam->user_id = $request->user_id;
        $peminjam->save();

        $telepon = new Telepon();
        $telepon->nomor_telepon = $request->nomor_telepon;
        $peminjam->telepon()->save($telepon);

        $request->session()->flash('flash_message', 'Data peminjam berhasil disimpan');
        
        return redirect('peminjam');
    }
    public function edit($id){
        $peminjam = Peminjam::find($id);
        if(!empty($peminjam->telepon->nomor_telepon)){
            $peminjam->nomor_telepon = $peminjam->telepon->nomor_telepon;
        }
        $list_jenis_peminjam = JenisPeminjam::pluck('nama_jenis_peminjam','id_jenis_peminjam');
        return view('peminjams/edit', compact('peminjam','list_jenis_peminjam'));
    }
        public function update(Request $request, $id){
        $peminjam = Peminjam::find($id);
        
        if($request->has('foto_peminjam')){
            $foto_peminjam = $request->foto_peminjam;
            $nama_file = time().'.'.$foto_peminjam->getClientOriginalExtension();
            $foto_peminjam->move('foto_peminjam/', $nama_file);
            $peminjam->kode_peminjam = $request->kode_peminjam;
            $peminjam->nama_peminjam = $request->nama_peminjam;
            $peminjam->tgl_lahir = $request->tgl_lahir;
            $peminjam->alamat = $request->alamat;
            $peminjam->id_jenis_peminjam = $request->id_jenis_peminjam;
            $peminjam->foto_peminjam=$nama_file;
            $peminjam->update();
        }
        else{
        $peminjam->kode_peminjam = $request->kode_peminjam;
        $peminjam->nama_peminjam = $request->nama_peminjam;
        $peminjam->tgl_lahir = $request->tgl_lahir;
        $peminjam->alamat = $request->alamat;
        $peminjam->id_jenis_peminjam = $request->id_jenis_peminjam;
        $peminjam->update();
        }
        //update nomor telepon, jika sebelumnya sudah ada nomor telepon
        if($peminjam->telepon){
            //jika telepon diisi, maka update
            if($request->filled('nomor_telepon')){
                $telepon = $peminjam->telepon;
                $telepon->nomor_telepon = $request->input('nomor_telepon');
                $peminjam->telepon()->save($telepon);
            }
            else{
                $peminjam->telepon()->delete();
            }
        }
        //buat entry baru, jika sebelumnya tidakada nomor telepon
        else{
            if($request->filled('nomor_telepon')){
                $telepon = new Telepon;
                $telepon->nomor_telepon = $request->nomor_telepon;
                $peminjam->telepon()->save($telepon);
            }
        }

        $request->session()->flash('flash_message', 'Data peminjam berhasil disimpan');

        return redirect('peminjam')->with('pesan','Data peminjam berhasil diupdate');
    }
    public function destroy(Request $request, $id){
        $peminjam = Peminjam::find($id);
        $peminjam->delete();

        $request->session()->flash('flash_message','Data peminjam berhasil dihapus');
        $request->flash('penting', true);

        return redirect('peminjam')->with('pesan', 'Data peminjam berhasil dihapus');
    }
    public function search(Request $request){
        $batas = 5;
        $cari = $request->kata;
        $data_peminjam = Peminjam::where('nama_peminjam','like','%'.$cari.'%')->paginate($batas);
        $no = $batas * ($data_peminjam->currentPage() - 1);
        return view('peminjams.search', compact('data_peminjam','no','cari'));
    }

    /*public function update(Request $request, $id){
    $request->validate([
    'kode_peminjam' => 'required',
    'nama_peminjam' => 'required',
    'tgl_lahir' => 'required',
    'alamat' => 'required',
    ]);
    Peminjam::find($id)->update($request->all());
    return redirect('peminjam');
    }*/

    public function CobaCollection(){
        $daftar = ['Budi Pranoto',
                    'Amy Delia',
                    'Cakra Lukman',
                    'Dewi Nova',
                    'Kartini Indah'];
        $collection = collect($daftar)->map(function($nama){
            return ucwords($nama);
        });
        return $collection;
    }
    public function collection_first(){
        $collection = Peminjam::all()->first();
        return $collection;
    }
    public function collection_last(){
        $collection = Peminjam::all()->last();
        return $collection;
    }
    public function collection_count(){
        $collection = Peminjam::all();
        $jumlah = $collection->count();
        return 'Jumlah Peminjam : '.$jumlah;
    }
    public function collection_take(){
        $collection = Peminjam::all()->take(4);
        return $collection;
    }
    public function collection_pluck(){
        $collection = Peminjam::all()->pluck('nama_peminjam');
        return $collection;
    }
    public function collection_where(){
        $collection = Peminjam::all()->where('kode_peminjam', 'P0004');
        return $collection;
    }
    public function collection_whereIn(){
        $collection = Peminjam::all()->whereIn('kode_peminjam', ['P0004', 'P0002', 'P0001']);
        return $collection;
    }
    public function collection_toarray(){
        $collection = Peminjam::select('kode_peminjam', 'nama_peminjam')->take(4)->get();
        $koleksi = $collection->toArray();
        foreach($koleksi as $peminjam){
            echo $peminjam['kode_peminjam'].' - '.$peminjam['nama_peminjam'].'<br>';
        }
    }
    public function collection_tojson(){
        $data = [
            ['kode_peminjam'=> 'P0001', 'nama_peminjam' => 'Baekhyun'],
            ['kode_peminjam'=> 'P0002', 'nama_peminjam' => 'Chanyeol'],
            ['kode_peminjam'=> 'P0003', 'nama_peminjam' => 'Renjun'],
            ['kode_peminjam'=> 'P0004', 'nama_peminjam' => 'Haechan'],
        ];
        $collection = collect($data)->toJson();
        return $collection;
    }
    
}