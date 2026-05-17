<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasien';
    protected $casts = [
        'gejala_terpilih' => 'array',
        'hasil_diagnosa' => 'array',
    ];
    protected $fillable = [
        'iduser',
        'gejala_terpilih',
        'cf_max',
        'hasil_diagnosa',
        'pdf',
        'idpenyakit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    public function penyakit()
{
    return $this->belongsTo(Penyakit::class, 'idpenyakit', 'id');
}

}
