<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $userId = Auth::id();
    
        $data1 = User::find($userId);
    
        $user = user::where('id', $userId)->first();
        
    
        return view('Profile', compact('data1'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto'     => 'nullable|mimes:png,jpg,jpeg',
            'nama'     => 'required',
            'instansi' => 'required',
            'password' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator)
            ->with('error', 'Kode sudah ada atau input tidak valid!');
        }
    
        $user = User::find($id);
        $user->nama  = $request->nama;    
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filenameu = date('Y-m-d') . $foto->getClientOriginalName();
            $path = '/' . $filenameu;
    
            Storage::disk('public')->put($path, file_get_contents($foto));
            $user->image = $filenameu;
        }
    
        $user->save(); 
    
        $user->instansi = $request->instansi;
        $user->save();
    
        return redirect()->route('profile.edit', $id)->with('success', 'Profil berhasil diperbarui.');
    }
    
}
