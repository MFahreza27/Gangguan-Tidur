<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use App\Models\Pasien;
use App\Models\Solusi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function riwayat()
    {
        $riwayat = Pasien::where('iduser', Auth::id())
                         ->with(['penyakit.solusi']) // Pastikan relasi penyakit dan solusi diambil
                         ->latest()
                         ->get();
    
        $data1 = Auth::user();

    
        foreach ($riwayat as $item) {
            $item->nama_penyakit = $item->penyakit->nama_penyakit ?? 'Belum Terdiagnosa';
             
            // Memastikan solusi adalah objek atau null, bukan string
            $item->solusi = $item->penyakit->solusi->solusi ?? 'Solusi tidak tersedia';
    
            if (is_string($item->cf_max)) {
                $decoded = json_decode($item->cf_max, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded[0], $decoded[1])) {
                    $item->probability = $decoded[0];
                    $item->disease = $decoded[1];
                } else {
                    $item->probability = 0;
                    $item->disease = 'Belum Terdiagnosa';
                }
            } else {
                $item->probability = 0;
                $item->disease = 'Belum Terdiagnosa';
            }
    
            if (is_string($item->hasil_diagnosa)) {
                $decoded_diagnoses = json_decode($item->hasil_diagnosa, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $item->diagnoses = $decoded_diagnoses;
                } else {
                    $item->diagnoses = [];
                }
            } else {
                $item->diagnoses = [];
            }

            

            
        }

    
        return view('riwayat', [
            'riwayat' => $riwayat,
            'data1'   => $data1,

        ]);
    }
    
    
    public function show()
    {
        // Mengambil hanya data yang memiliki riwayat diagnosa
        $riwayat = Pasien::whereNotNull('hasil_diagnosa') // Pastikan hanya mengambil data yang memiliki diagnosa
                     ->with(['user', 'penyakit.solusi']) // Mengambil relasi user, penyakit, dan solusi
                     ->latest()
                     ->get();
    
        foreach ($riwayat as $item) {
            $item->nama_pasien = $item->user->nama ?? 'Tidak Diketahui';
            $item->tanggal = $item->created_at->format('d-m-Y H:i');
            $item->nama_penyakit = $item->penyakit->nama_penyakit ?? 'Belum Terdiagnosa';
            $item->solusi = $item->penyakit->solusi->solusi ?? 'Solusi tidak tersedia';
    
            if (is_string($item->cf_max)) {
                $decoded = json_decode($item->cf_max, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded[0], $decoded[1])) {
                    $item->probability = $decoded[0];
                    $item->disease = $decoded[1];
                } else {
                    $item->probability = 0;
                    $item->disease = 'Belum Terdiagnosa';
                }
            } else {
                $item->probability = 0;
                $item->disease = 'Belum Terdiagnosa';
            }
    
            if (is_string($item->hasil_diagnosa)) {
                $decoded_diagnoses = json_decode($item->hasil_diagnosa, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $item->diagnoses = $decoded_diagnoses;
                } else {
                    $item->diagnoses = [];
                }
            } else {
                $item->diagnoses = [];
            }
        }
    
        return view('admin.datariwayat', [
            'riwayat' => $riwayat,
            'data1'   => Auth::user(), // Tambahkan ini
        ]);
    }
    

    public function detail()
    {
        $riwayat = Pasien::all();
        $data1 = Auth::user();

        foreach ($riwayat as $item) {
            // Decode cf_max if it's a string JSON
            if (is_string($item->cf_max) && json_decode($item->cf_max, true)) {
                $decoded = json_decode($item->cf_max, true);
                $item->probability = $decoded[0] ?? 0;
                $item->disease = $decoded[1] ?? 'Belum Terdiagnosa';
            } else {
                $item->probability = 0;
                $item->disease = 'Belum Terdiagnosa';
            }
        }

        return view('riwayat', [
            'riwayat' => $riwayat,
            'data1'   => $data1,
        ]);
    }

    public function delete(Request $request,$id){
        $data = Pasien::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.datariwayat.show')->with('success', 'Data Riwayat Diagnosa berhasil dihapus');
    }
  
    public function downloadPdf()
    {
        // Ambil riwayat pasien berdasarkan user yang sedang login dan urutkan berdasarkan created_at terbaru
        $riwayat = Pasien::where('iduser', Auth::id())->orderBy('created_at', 'desc')->get();
        $data1 = Auth::user();
        
        // Decode field yang diperlukan untuk diagnosis
        foreach ($riwayat as $item) {
            // Pastikan cf_max adalah array dan decode dengan benar
            if (is_string($item->cf_max)) {
                $decoded = json_decode($item->cf_max, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded[0], $decoded[1])) {
                    $item->probability = $decoded[0];
                    $item->disease = $decoded[1];
                } else {
                    $item->probability = 0;
                    $item->disease = 'Belum Terdiagnosa';
                }
            } else {
                $item->probability = 0;
                $item->disease = 'Belum Terdiagnosa';
            }
                if (is_string($item->hasil_diagnosa)) {
                $decoded_diagnoses = json_decode($item->hasil_diagnosa, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Pastikan diagnoses adalah array
                    $item->diagnoses = is_array($decoded_diagnoses) ? $decoded_diagnoses : [];
                } else {
                    $item->diagnoses = [];
                }
            } else {
                $item->diagnoses = [];
            }
    
            // Ambil solusi berdasarkan idpenyakit
            $penyakit = Penyakit::find($item->idpenyakit);
            $item->solusi = $penyakit ? Solusi::where('idpenyakit', $penyakit->id)->first() : null;
        }
    
        // Return the PDF view
        $pdf = Pdf::loadView('pdf.pdf', [
            'riwayat' => $riwayat,
            'data1'   => $data1,
        ]);
    
        return $pdf->download('riwayat_diagnosa.pdf');
    }
    public function downloadPdff($id)
    {
        // Ambil data pasien berdasarkan ID — pastikan milik user yang login
        $riwayat = Pasien::where('id', $id)
            ->where('iduser', Auth::id()) // ownership check
            ->firstOrFail();

        $data1 = Auth::user();
    
        // Decode cf_max untuk mendapatkan probabilitas dan penyakit
        if (is_string($riwayat->cf_max)) {
            $decoded = json_decode($riwayat->cf_max, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded[0], $decoded[1])) {
                $riwayat->probability = $decoded[0];
                $riwayat->disease = $decoded[1];
            } else {
                $riwayat->probability = 0;
                $riwayat->disease = 'Belum Terdiagnosa';
            }
        } else {
            $riwayat->probability = 0;
            $riwayat->disease = 'Belum Terdiagnosa';
        }
    
        // Decode hasil_diagnosa
        if (is_string($riwayat->hasil_diagnosa)) {
            $decoded_diagnoses = json_decode($riwayat->hasil_diagnosa, true);
            $riwayat->diagnoses = is_array($decoded_diagnoses) ? $decoded_diagnoses : [];
        } else {
            $riwayat->diagnoses = [];
        }
    
        // Ambil solusi berdasarkan idpenyakit
        $penyakit = Penyakit::find($riwayat->idpenyakit);
        $riwayat->solusi = $penyakit ? Solusi::where('idpenyakit', $penyakit->id)->first() : null;
    
        // Generate PDF
        $pdf = Pdf::loadView('pdf.pdf', [
            'riwayat' => collect([$riwayat]), // Dibungkus array agar tetap kompatibel dengan tampilan lama
            'data1'   => $data1,
        ]);
    
        return $pdf->download('riwayat_diagnosa.pdf');
    }



    

    
}
