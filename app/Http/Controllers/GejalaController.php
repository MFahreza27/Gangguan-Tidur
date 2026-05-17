<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GejalaController extends Controller
{ 
    public function gejala(){
        $data        = Gejala::get();
        $gejalaCount = Gejala::count();
        $datau       = User::get();
        $data1       = Auth::user();
        $Pasien = Pasien::where('iduser', $data1->id)->first();
        return view('admin.gejala', [
            'data'        => $data,
            'gejalaCount' => $gejalaCount,
            'data1'       => $data1,
            'datau'       => $datau,
            'Pasien'     => $Pasien,
        ]);
    }

    public function create(){
        $data  = User::get();
        $data1 = Auth::user();
        return view('admin.create_gejala', [
            'data'  => $data,
            'data1' => $data1,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'      => 'required|unique:gejala,kd_gejala',
            'deskripsi' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Data gagal ditambahkan!');
        }
    
        Gejala::create([
            'kd_gejala' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);
    
        return redirect()->route('admin.gejala')->with('success', 'Data Gejala berhasil ditambahkan.');
    }
    
    public function edit(Request $request,$id){
        $data  = Gejala::find($id);
        $data1 = Auth::user();
        return view('admin.edit_gejala',compact('data','data1'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'kode'      => 'required',
            'deskripsi' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $data['kd_gejala'] = $request->kode;
        $data['deskripsi'] = $request->deskripsi;
    
        Gejala::whereId($id)->update($data);
    
        return redirect()->route('admin.gejala')->with('success', 'Data Gejala berhasil diperbarui.');
    }
    
    public function delete(Request $request,$id){
        $data = Gejala::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.gejala')->with('success', 'Data Gejala berhasil dihapus');
    }
}
