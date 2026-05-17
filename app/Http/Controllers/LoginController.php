<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
      //
    public function index(){
        return view('auth.login');
        }

    public function loginproses(Request $request){
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $data = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        
        if(Auth::attempt($data)){
            $request->session()->regenerate(); // cegah session fixation
            return redirect()->route('dashboard')->with('success','Berhasil Login');
        }else {
            return redirect()->route('login')->with('failed','Email atau Password Salah');
        }
       
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('success', 'Kamu Berhasil Logout');
    }

    public function register(){
        return view('auth.register');
    }

    public function registerproses(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'instansi' => 'nullable|string|max:255',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.unique'        => 'Email sudah terdaftar!',
            'password.confirmed'  => 'Password dan konfirmasi password tidak cocok!',
            'password.min'        => 'Password harus minimal 6 karakter!',
        ]);
    
        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'instansi' => $request->instansi ?? '-',
            'image'    => 'SIGATUR.PNG',
        ]);
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Pendaftaran Berhasil');
        }
    
        return redirect()->route('login')->with('failed', 'Email atau Password Salah');
    }
    
    
}

