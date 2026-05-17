<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    use HasFactory;
    protected $table = 'penyakit';
    protected $fillable = [
        'kd_penyakit',
        'nama_penyakit',
        'deskripsi',
    ];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'idgejala', 'id');
    }

    public function aturan()
    {
        return $this->hasMany(Aturan::class, 'idpenyakit', 'id'); // Relasi ke tabel aturan berdasarkan idpenyakit
    }
        // Tambahkan relasi ke Solusi
        public function solusi()
        {
            return $this->hasOne(Solusi::class, 'idpenyakit', 'id');
        }    

        
}
