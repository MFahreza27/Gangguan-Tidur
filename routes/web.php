<?php

use App\Http\Controllers\AturanController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SolusiController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login',[LoginController::class,'index'])->name('login');
Route::post('/loginproses',[LoginController::class,'loginproses'])->name('loginproses');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');

Route::get('/register',[LoginController::class,'register'])->name('register');
Route::post('/registerproses',[LoginController::class,'registerproses'])->name('registerproses');


Route::group(['prefix' => 'admin','middleware' => ['auth','role:admin'], 'as' => 'admin.'], function(){

//User
    Route::get('/user',[UserController::class,'index'])->name('index');
    Route::get('/user/create',[UserController::class,'create'])->name('user.create');
    Route::post('/user/store',[UserController::class,'store'])->name('user.store');
    Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('user.edit');
    Route::post('/user/update/{id}',[UserController::class,'update'])->name('user.update');
    Route::delete('/user/delete/{id}',[UserController::class,'delete'])->name('user.delete');

//Penyakit
    Route::get('/penyakit',[PenyakitController::class,'penyakit'])->name('penyakit');
    Route::get('/penyakit/create',[PenyakitController::class,'create'])->name('penyakit.create');
    Route::post('/penyakit/store',[PenyakitController::class,'store'])->name('penyakit.store');
    Route::get('penyakit/edit/{id}',[PenyakitController::class,'edit'])->name('penyakit.edit');
    Route::post('penyakit//update/{id}',[PenyakitController::class,'update'])->name('penyakit.update');
    Route::delete('penyakit/delete/{id}',[PenyakitController::class,'delete'])->name('penyakit.delete');

//Gejala
    Route::get('/gejala',[GejalaController::class,'gejala'])->name('gejala');
    Route::get('/gejala/create',[GejalaController::class,'create'])->name('gejala.create');
    Route::post('/gejala/store',[GejalaController::class,'store'])->name('gejala.store');
    Route::get('gejala/edit/{id}',[GejalaController::class,'edit'])->name('gejala.edit');
    Route::post('gejala/update/{id}',[GejalaController::class,'update'])->name('gejala.update');
    Route::delete('gejala/delete/{id}',[GejalaController::class,'delete'])->name('gejala.delete');

//Pertanyaan
    Route::get('/pertanyaan',[PertanyaanController::class,'pertanyaan'])->name('pertanyaan');
    Route::get('/pertanyaan/create',[PertanyaanController::class,'create'])->name('pertanyaan.create');
    Route::post('/pertanyaan/store',[PertanyaanController::class,'store'])->name('pertanyaan.store');
    Route::get('pertanyaan/edit/{id}',[PertanyaanController::class,'edit'])->name('pertanyaan.edit');
    Route::post('pertanyaan/update/{id}',[PertanyaanController::class,'update'])->name('pertanyaan.update');
    Route::delete('pertanyaan/delete/{id}',[PertanyaanController::class,'delete'])->name('pertanyaan.delete');

//Solusi
    Route::get('/solusi',[SolusiController::class,'solusi'])->name('solusi');
    Route::get('/solusi/create',[SolusiController::class,'create'])->name('solusi.create');
    Route::post('/solusi/store',[SolusiController::class,'store'])->name('solusi.store');
    Route::get('solusi/edit/{id}',[SolusiController::class,'edit'])->name('solusi.edit');
    Route::post('solusi/update/{id}',[SolusiController::class,'update'])->name('solusi.update');
    Route::delete('solusi/delete/{id}',[SolusiController::class,'delete'])->name('solusi.delete');
    
//DataTable
    Route::get('/clientside',[DataTableController::class,'clientside'])->name('clientside');

//Aturan
    Route::get('/aturan',[AturanController::class,'aturan'])->name('aturan');
    Route::get('/aturan/{id}', [AturanController::class, 'show'])->name('aturan.show');
    Route::post('/aturan/store', [AturanController::class, 'store'])->name('aturan.store');

//Riwayat
    Route::get('/datariwayat',[RiwayatController::class,'show'])->name('datariwayat.show');
    Route::delete('datariwayat/delete/{id}',[RiwayatController::class,'delete'])->name('datariwayat.delete');
    Route::get('/riwayat/cetak/{id}', [RiwayatController::class, 'downloadPdff'])->name('riwayat.cetak');
    
});

//Dashboard
Route::get('/dashboard',[UserController::class,'dashboard'])->name('dashboard');

//Profile
Route::get('/profile/edit/{id}',[ProfileController::class,'edit'])->name('profile.edit');
Route::post('profile/update/{id}',[ProfileController::class,'update'])->name('profile.update');

//Diagnosa
Route::get('/diagnosa', [DiagnosaController::class, 'diagnosa'])->name('diagnosa');
Route::post('/diagnosa', [DiagnosaController::class, 'simpanDiagnosa'])->name('diagnosa.simpanDiagnosa');

//Riwayat & PDF — dilindungi auth
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat',[RiwayatController::class,'riwayat'])->name('riwayat');
    Route::get('/riwayat/{id}',[RiwayatController::class,'detail'])->name('riwayat.detail');
    Route::get('/riwayat/pdf/pdf', [RiwayatController::class, 'downloadPdf'])->name('riwayat.pdf');
    Route::get('/riwayat/cetak/{id}', [RiwayatController::class, 'downloadPdff'])->name('riwayat.cetak');
});

Route::redirect('/', '/login');








Route::get('/ui-element-buttons', function () {
    return view('pages.ui-element-buttons', ['type_menu' => 'ui-element']);
});
Route::get('/ui-element-dropdowns', function () {
    return view('pages.ui-element-dropdowns', ['type_menu' => 'ui-element']);
});
Route::get('/ui-element-typograpy', function () {
    return view('pages.ui-element-typograpy', ['type_menu' => 'ui-element']);
});
Route::get('/form-element-basic', function () {
    return view('pages.form-element-basic', ['type_menu' => 'form-element']);
});
Route::get('/table-basic', function () {
    return view('pages.table-basic', ['type_menu' => 'table']);
});
Route::get('/icons-mdi', function () {
    return view('pages.icons-mdiicons', ['type_menu' => 'icon']);
});
Route::get('/charts-js', function () {
    return view('pages.chart-chartjs', ['type_menu' => 'chart']);
});

// Route::get('/register', function () {
//     return view('pages.user-register', ['type_menu' => 'user']);
// });
Route::get('/error-404', function () {
    return view('pages.error-404', ['type_menu' => 'error']);
});
Route::get('/error-500', function () {
    return view('pages.error-500', ['type_menu' => 'error']);
});
Route::get('/documentation', function () {
    return view('pages.documentation', ['type_menu' => 'doc']);
});
