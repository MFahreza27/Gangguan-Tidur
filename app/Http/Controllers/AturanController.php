<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AturanController extends Controller
{
    public function aturan(){
        $data  = Gejala::get();
        $datau = User::get();
        $data1 = Auth::user();
        $dataP = Penyakit::all();
        $dataG = Gejala::all();
        $Pasien = Pasien::where('iduser', $data1->id)->first();
        return view('admin.aturan', [
            'data'  => $data,
            'data1' => $data1,
            'datau' => $datau,
            'dataP' => $dataP,
            'dataG' => $dataG,
            'Pasien' => $Pasien,
        ]);
    }
    public function show($id)
    {
        $data1 = Auth::user();
        $dataP  = Penyakit::all();
        $dataG = Gejala::all();   
        $dataA = Aturan::where('idpenyakit', $id)->get();
        return view('admin.aturan', compact('dataP', 'data1', 'dataG', 'dataA'));  
    }
    public function store(Request $request)
    {
        $request->validate([
            'idpenyakit' => 'required|exists:penyakit,id',
            'idgejala' => 'required|array',
            'cf' => 'required|array',
        ]);
    
        $idPenyakit = $request->input('idpenyakit');
        $gejalaIds = $request->input('idgejala');
        $cfValues = $request->input('cf');
        if (count($gejalaIds) !== count($cfValues)) {
            return redirect()->back()->withErrors(['error' => 'Jumlah gejala dan CF tidak sesuai.']);
        }
        foreach ($gejalaIds as $index => $gejalaId) {
            $cfValue = $cfValues[$index];  
            $dataA = Aturan::where('idpenyakit', $idPenyakit)
                                    ->where('idgejala', $gejalaId)
                                    ->first();
            if ($dataA) {
                $dataA->update([
                    'nilai_cf' => $cfValue,
                ]);
            } else {
                Aturan::create([
                    'idpenyakit' => $idPenyakit,
                    'idgejala' => $gejalaId,
                    'nilai_cf' => $cfValue,
                ]);
            }
        }
        return redirect()->route('admin.aturan')->with('success', 'Data Aturan berhasil disimpan');
    }
    
    
    
    
    
    
    
    
    
}
