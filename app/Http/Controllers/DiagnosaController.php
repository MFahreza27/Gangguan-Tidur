<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Pertanyaan;
use App\Models\pasien;
use App\Models\Solusi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\File;

class DiagnosaController extends Controller
{
    public function diagnosa()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action');
        }
    
        $pertanyaan  = Pertanyaan::get();
        $data  = Solusi::get();
        $datau = User::get();
        $data1 = Auth::user();
        $pasien = pasien::where('iduser', $data1->id)->first();
    
        return view('diagnosa', [
            'data'    => $data,
            'data1'   => $data1,
            'datau'   => $datau,
            'pasien' => $pasien,
            'pertanyaan' =>$pertanyaan,
        ]);
    }

    public function tingkat_keyakinan($keyakinan)
    {
        $map = [
            '0'   => 'Sangat Tidak Yakin',
            '0.2' => 'Tidak Yakin',
            '0.4' => 'Kurang Yakin',
            '0.6' => 'Cukup Yakin',
            '0.8' => 'Yakin',
            '1'   => 'Sangat Yakin',
        ];
        return $map[(string)$keyakinan] ?? 'Tidak Diketahui';
    }

    public function kalkulasi_cf($data)
    {
        $data_penyakit = [];
        $gejala_terpilih = [];

        foreach ($data['diagnosa'] as $input) {
            if (!empty($input)) {
                $opts = explode('+', $input);
                if (count($opts) < 2) {
                    continue;
                }

                $id_gejala = $opts[0];
                $cf_user   = (float) $opts[1];

                // Gejala dengan cf_user = 0 (Sangat Tidak Yakin) tidak perlu dihitung
                if ($cf_user == 0) {
                    continue;
                }

                $gejala = Gejala::find($id_gejala);

                if ($gejala) {
                    $aturan_list = Aturan::where('idgejala', $gejala->id)->get();

                    foreach ($aturan_list as $aturan) {
                        if (!isset($data_penyakit[$aturan->idpenyakit])) {
                            $data_penyakit[$aturan->idpenyakit] = [
                                $aturan->penyakit,
                                [$gejala, $cf_user, (float) $aturan->nilai_cf],
                            ];
                        } else {
                            array_push($data_penyakit[$aturan->idpenyakit], [$gejala, $cf_user, (float) $aturan->nilai_cf]);
                        }
                    }

                    $gejala_terpilih[$gejala->id] = [
                        'nama'      => $gejala->deskripsi,
                        'idgejala'  => $gejala->id,
                        'cf_user'   => $cf_user,
                        'keyakinan' => $this->tingkat_keyakinan($cf_user),
                    ];
                }
            }
        }

        $hasil_diagnosa = [];
        $cf_max         = null;

        foreach ($data_penyakit as $final) {
            // Minimal harus ada 1 gejala (index 0 = Penyakit, index 1+ = gejala)
            if (count($final) < 2) {
                continue;
            }

            $cf_combine = null;

            foreach ($final as $key => $value) {
                if ($key == 0) {
                    continue; // index 0 adalah objek Penyakit
                }

                // CF per gejala = nilai_cf_pakar × cf_user
                $cf_symptom = $final[$key][2] * $final[$key][1];

                if ($cf_combine === null) {
                    // Gejala pertama: langsung set cf_combine
                    $cf_combine = $cf_symptom;
                } else {
                    // Gejala berikutnya: kombinasikan dengan rumus CF sequential
                    $cf_combine = $cf_combine + ($cf_symptom * (1 - $cf_combine));
                }
            }

            $hasil_cf = $cf_combine;

            if ($hasil_cf === null) {
                continue;
            }

            // Update cf_max
            if ($cf_max === null || $hasil_cf > $cf_max[0]) {
                $cf_max = [$hasil_cf, "{$final[0]->nama_penyakit} ({$final[0]->kd_penyakit})"];
            }

            // Bangun detail hasil diagnosa
            $gejala_detail = [];
            foreach ($final as $key => $value) {
                if ($key == 0) continue;
                $gejala_detail[] = [
                    'nama'            => $final[$key][0]->deskripsi,
                    'kode'            => $final[$key][0]->kd_gejala,
                    'cf_user'         => $final[$key][1],
                    'cf_role'         => $final[$key][2],
                    'hasil_perkalian' => $final[$key][2] * $final[$key][1],
                ];
            }

            $hasil_diagnosa[$final[0]->id] = [
                'nama_penyakit' => $final[0]->nama_penyakit,
                'kode_penyakit' => $final[0]->kd_penyakit,
                'hasil_cf'      => $hasil_cf,
                'gejala'        => $gejala_detail,
            ];
        }

        return [
            'hasil_diagnosa' => $hasil_diagnosa,
            'gejala_terpilih' => $gejala_terpilih,
            'cf_max'          => $cf_max,
        ];
    }

    public function simpanDiagnosa(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan tindakan ini');
        }
    
        $validated = $request->validate([
            'diagnosa' => 'required|array|min:1',
            'diagnosa.*' => 'string', 
        ]);
    
        $user = Auth::user();
        $data = $request->all();
    
        $result = $this->kalkulasi_cf($data);
    
        if ($result['cf_max'] == null) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat kalkulasi.']);
        }
    
        if (isset($result['cf_max'][1])) {
            $penyakit_string = $result['cf_max'][1]; 
                        preg_match('/\((.*?)\)/', $penyakit_string, $matches);
            
            $kd_penyakit = isset($matches[1]) ? $matches[1] : null; 
        } else {
            $kd_penyakit = null;
        }
    
        if ($kd_penyakit) {
            $penyakit = Penyakit::where('kd_penyakit', $kd_penyakit)->first();
            $idpenyakit = $penyakit ? $penyakit->id : null;
        } else {
            $idpenyakit = null;
        }
            pasien::create([
            'iduser' => $user->id,
            'gejala_terpilih' => json_encode($result['gejala_terpilih']),
            'cf_max' => json_encode($result['cf_max']),
            'hasil_diagnosa' => json_encode($result['hasil_diagnosa']),
            'idpenyakit' => $idpenyakit,
        ]);
    
        return redirect()->route('riwayat')->with('success', 'Diagnosa berhasil disimpan!');
    }
    
    
    
    
    
    
    
    
}
