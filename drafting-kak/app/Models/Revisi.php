<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    use HasFactory;

    protected $table = 'revisi'; // Nama tabel
    protected $primaryKey = 'revisi_id'; // Primary Key

    protected $fillable = [
        'user_id',
        'kak_id',
        'kategori_id',
        'alasan_penolakan',
        'saran',
    ];

    // Relasi ke tabel kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
