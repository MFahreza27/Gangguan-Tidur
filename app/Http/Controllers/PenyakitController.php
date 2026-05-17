<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PenyakitController extends Controller
{

    public function penyakit(){
        $data          = Penyakit::get();
        $penyakitCount = Penyakit::count();
        $datau         = User::get();
        $data1         = Auth::user();
        $Pasien       = Pasien::where('iduser', $data1->id)->first();


        return view('admin.penyakit', [
            'data'          => $data,
            'penyakitCount' => $penyakitCount,
            'data1'         => $data1,
            'datau'         => $datau,
            'Pasien'       => $Pasien
        ]);

    }

    public function create(){
        $data  = User::get();
        $data1 = Auth::user();

        return view('admin.create_penyakit', [
            'data'  => $data,
            'data1' => $data1,
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'kode'      => 'required|unique:penyakit,kd_penyakit', // Cek unik
            'nama'      => 'required',
            'deskripsi' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator)->with('error', 'Data gagal ditambahkan!');
        }
    
        Penyakit::create([
            'kd_penyakit'   => $request->kode,
            'nama_penyakit' => $request->nama,
            'deskripsi'     => $request->deskripsi,
        ]);
    
        return redirect()->route('admin.penyakit')->with('success', 'Data Penyakit berhasil ditambahkan.');
    }
    
    

    public function edit(Request $request,$id){
        $data  = Penyakit::find($id);
        $data1 = Auth::user();
        return view('admin.edit_penyakit',compact('data','data1'));
    }
    
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'kode'      => 'required',
            'nama'      => 'required',
            'deskripsi' => 'required',
        ]);
        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);
        $data['kd_penyakit']   = $request->kode;
        $data['nama_penyakit'] = $request->nama;
        $data['deskripsi']     = $request->deskripsi;
        Penyakit::whereId($id)->update($data);
        return redirect()->route('admin.penyakit')->with('success', 'Data Penyakit berhasil diperbarui.');
    }

    public function delete(Request $request,$id){
        $data = Penyakit::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.penyakit')->with('success', 'Data Penyakit berhasil dihapus');
    }
}
