<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    use HasFactory;
    protected $table = 'aturan';
    protected $fillable = [
        'idpenyakit',
        'idgejala',
        'nilai_cf',
    ];
    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'idpenyakit', 'id'); 
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'idgejala', 'id'); 
    }
}
