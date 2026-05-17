<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Pertanyaan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PertanyaanController extends Controller
{
    public function pertanyaan()
    {
        $data  = Pertanyaan::with('gejala')->get(); 
        $penyakit = Penyakit::get();
        $datau = User::get();
        $data1 = Auth::user();
        $Pasien = Pasien::where('iduser', $data1->id)->first();
        return view('admin.pertanyaan', [
            'data'    => $data,
            'data1'   => $data1,
            'datau'   => $datau,
            'Pasien' => $Pasien,
            'penyakit' => $penyakit,
        ]);
    }
    public function create(){
        $data  = User::get();
        $data1 = Auth::user();
        $data2 = Gejala::get();
        return view('admin.create_pertanyaan', [
            'data'  => $data,
            'data1' => $data1,
            'data2' => $data2
        ]);
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'idgejala'   => 'required|unique:pertanyaan,idgejala',
            'pertanyaan' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Data gagal ditambahkan!');
        }
    
        $data['idgejala']   = $request->idgejala;
        $data['pertanyaan'] = $request->pertanyaan;
        Pertanyaan::create($data);
    
        return redirect()->route('admin.pertanyaan')->with('success', 'Data Pertanyaan berhasil ditambahkan');
    }
    
    public function edit(Request $request,$id){
        $data  = pertanyaan::find($id);
        $data1 = Auth::user();
        $data2 = Gejala::get();
        return view('admin.edit_pertanyaan',compact('data','data1','data2'));
    }
    public function update(Request $request, $id)
    {
        $data = Pertanyaan::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'idgejala'   => 'required|exists:gejala,id', // Pastikan ID valid di tabel `gejala`
            'pertanyaan' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $data->update([
            'idgejala'   => (int) $request->idgejala, // Pastikan dikonversi ke integer
            'pertanyaan' => $request->pertanyaan,
        ]);
    
        return redirect()->route('admin.pertanyaan')->with('success', 'Data Pertanyaan berhasil diperbarui.');
    }
    
    public function delete(Request $request,$id){
        $data = pertanyaan::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.pertanyaan')->with('success', 'Data Pertanyaan berhasil Diahapus');
    }
}
