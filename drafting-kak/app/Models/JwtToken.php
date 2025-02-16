<?php

namespace App\Models; // Menentukan namespace model agar bisa digunakan di Laravel

use Illuminate\Database\Eloquent\Factories\HasFactory; // Menggunakan trait HasFactory untuk mempermudah pembuatan data dummy
use Illuminate\Database\Eloquent\Model; // Menggunakan Model Eloquent sebagai dasar model ini

class JwtToken extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk model ini

    protected $table = 'jwt_tokens'; // Menentukan nama tabel yang digunakan dalam database

    protected $fillable = ['user_id', 'token']; // Menentukan kolom yang bisa diisi secara massal (Mass Assignment)

    /**
     * Relasi dengan model User (Setiap JWT Token dimiliki oleh satu user).
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Menyatakan bahwa jwt_tokens memiliki hubungan "belongsTo" dengan tabel users
    }
}
