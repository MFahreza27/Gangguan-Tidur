<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solusi extends Model
{
    use HasFactory;
    protected $table = 'solusi';
    protected $fillable = [
        'idpenyakit',
        'solusi',
    ];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'idpenyakit', 'id');
    }


}
