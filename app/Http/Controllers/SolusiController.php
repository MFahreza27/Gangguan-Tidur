<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use App\Models\Pasien;
use App\Models\Solusi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SolusiController extends Controller
{
    public function solusi(){
        $solusi  = Solusi::with('penyakit')->get(); 
        $data  = Solusi::get();
        $datau = User::get();
        $data1 = Auth::user();
        $Pasien = Pasien::where('iduser', $data1->id)->first();
        return view('admin.solusi', [
            'data'    => $data,
            'data1'   => $data1,
            'datau'   => $datau,
            'Pasien' => $Pasien,
            'solusi'  => $solusi,
        ]);
    }
    public function create(){
        $data  = User::get();
        $data1 = Auth::user();
        $data2 = Penyakit::get();
        return view('admin.create_solusi', [
            'data'  => $data,
            'data1' => $data1,
            'data2' => $data2
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'kode'   => 'required|unique:solusi,idpenyakit',
            'solusi' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator)
                ->with('error', 'Kode sudah ada atau input tidak valid!');
        }
    
        $data['idpenyakit'] = $request->kode;
        $data['solusi']      = $request->solusi;
        Solusi::create($data);
    
        return redirect()->route('admin.solusi')->with('success', 'Data Solusi berhasil ditambahkan');
    }
    
    public function edit(Request $request,$id){
        $data  = Solusi::find($id);
        $data1 = Auth::user();
        $data2 = Penyakit::get();
        return view('admin.edit_solusi',compact('data','data1','data2'));
    }
    public function update(Request $request, $id)
    {
        $data = Solusi::find($id);
        $validator = Validator::make($request->all(), [
            'kode'   => 'required|unique:solusi,idpenyakit,' . $data->id, 
            'solusi' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $data['idpenyakit'] = $request->kode;
        $data['solusi']     = $request->solusi;
        $data->save();
        return redirect()->route('admin.solusi')->with('success', 'Data Solusi berhasil diperbarui');
    }
    public function delete(Request $request,$id){
        $data = Solusi::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.solusi')->with('success', 'Data Solusi berhasil dihapus');
    }
}
