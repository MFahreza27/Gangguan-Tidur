<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Pasien;

class User extends Authenticatable
{
    use HasFactory,HasRoles;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'image',
        'instansi',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'iduser');
    }
}

