<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan';
    protected $fillable = [
        'idgejala',
        'pertanyaan',
    ];

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'idgejala', 'id');
    }
}
