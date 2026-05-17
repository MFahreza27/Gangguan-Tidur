<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    use HasFactory;
    protected $table = 'gejala';
    protected $fillable = [
        'kd_gejala',
        'deskripsi',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'idgejala');
    }

    public function aturan()
    {
        return $this->hasMany(Aturan::class, 'idgejala', 'id'); // Relasi ke tabel aturan berdasarkan idgejala
    }
}
