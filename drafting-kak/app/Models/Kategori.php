<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_program';

    protected $fillable = [
        'nama_divisi',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'kategori_id');
    }
}
