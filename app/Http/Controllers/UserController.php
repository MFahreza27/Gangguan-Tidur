<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Pasien;
use App\Models\Pertanyaan;
use App\Models\Solusi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function dashboard(){
        $data1         = Auth::user();
        $data          = User::get();
        $dataP         = Penyakit::get();
        $userCount     = User::count();
        $penyakitCount = Penyakit::count();
        $gejalaCount   = Gejala::count();
        $pertanyaanCount = Pertanyaan::count();
        $pasienCount   = Pasien::count();
        $solusiCount = Solusi::count();
        $pasienCountuser = Pasien::where('iduser', $data1->id)->count();
        $DataPasien   = User::with('Pasien')->get();
        $Pasien = Pasien::where('iduser', $data1->id)->first();
        $statistik = Penyakit::leftJoin('pasien', 'penyakit.id', '=', 'pasien.idpenyakit')
        ->selectRaw('penyakit.id, penyakit.nama_penyakit, COUNT(pasien.idpenyakit) as jumlah')
        ->groupBy('penyakit.id', 'penyakit.nama_penyakit')
        ->orderByDesc('jumlah')
        ->get();
    

        return view('admin.dashboard', [
            'data1'         => $data1,
            'data'          => $data,
            'dataP'         => $dataP,
            'userCount'     => $userCount,
            'penyakitCount' => $penyakitCount,
            'gejalaCount'   => $gejalaCount,
            'DataPasien'   => $DataPasien,
            'Pasien'       => $Pasien,
            'pasienCount' => $pasienCount,
            'statistik' =>$statistik,
            'pertanyaanCount'=>$pertanyaanCount,
            'solusiCount' => $solusiCount,
            'pasienCountuser' =>$pasienCountuser
        ]);

    }

    public function index(){
        $data  = User::get();
        $data1 = Auth::user();
        return view('index', [
            'data'  => $data,
            'data1' => $data1,
        ]);
    }
    public function create(){
        $data1 = Auth::user();
        return view('create', [
            'data1' => $data1,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'instansi' => 'required',
            'password' => 'required',
            'role'     => 'required|in:admin,user',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator)
            ->with('error', 'Kode sudah ada atau input tidak valid!');
        }
    
        try {
            // Check if there's a file
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                // Save the file directly in the 'public' directory
                $fileName = date('Y-m-d') . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('', $fileName, 'public'); // Saving directly to public storage
            } else {
                return redirect()->back()->with('error', 'File foto tidak ditemukan.');
            }
    
            // Create the user with the stored image path
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'instansi' => $request->instansi,
                'image'    => $path,
            ]);
    
            // Assign Role
            $user->assignRole($request->role);
    
            return redirect()->route('admin.index')->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function edit(Request $request,$id){
        $data1 = Auth::user();
        $data  = User::find($id);
        $roles = Role::get();
        return view('edit',compact('data','roles','data1'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto tidak wajib
            'nama'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $id, // Pastikan email unik, kecuali untuk user yang sedang diedit
            'instansi' => 'required',
            'password' => 'nullable',
            'role'     => 'required|in:admin,user',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $data = User::findOrFail($id);
    
        if ($request->hasFile('foto')) {
            if ($data->image && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
    
            $foto = $request->file('foto');
            $fileName = date('Y-m-d') . '_' . $foto->getClientOriginalName();
            $path = $foto->storeAs('', $fileName, 'public'); 
            $data->image = $path; 
        }
    
        $data->nama     = $request->nama;
        $data->email    = $request->email;
        $data->instansi = $request->instansi;
    
        if ($request->password) {
            $data->password = Hash::make($request->password);
        }
    
        $data->syncRoles($request->role);
        $data->role = $request->role;
    
        $data->save();
    
        return redirect()->route('admin.index')->with('success', 'Data Pengguna berhasil diperbarui!');
    }
    
    

    public function delete(Request $request,$id){
        $data = User::find($id);
        if($data){
            $data->delete();
        }
        return redirect()->route('admin.index')->with('success', 'Data Pengguna berhasil Dihapus!');
    }
}


