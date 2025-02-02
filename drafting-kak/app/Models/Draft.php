<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    protected $table = 'kak'; // Pastikan nama tabel sesuai

    protected $primaryKey = 'kak_id'; // Jika primary key-nya `kak_id`

    protected $fillable = [
        'user_id',
        'kategori_id',
        'no_doc_mak',
        'judul',
        'status',
        'indikator',
        'satuan_ukur',
        'volume',
        'latar_belakang',
        'dasar_hukum',
        'gambaran_umum',
        'tujuan',
        'target_sasaran',
        'unit_kerja',
        'ruang_lingkup',
        'produk_jasa_dihasilkan',
        'waktu_pelaksanaan',
        'tenaga_ahli_terampil',
        'peralatan',
        'metode_kerja',
        'manajemen_resiko',
        'laporan_pengajuan_pekerjaan',
        'sumber_dana_prakiraan_biaya',
        'penutup',
        'lampiran',
    ];

    /**
     * Relasi ke tabel kategori_program
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id'); // Relasi ke kategori_program
    }

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // Relasi ke users
    }

    /**
     * Relasi ke tabel revisi
     */
    public function revisi()
    {
        return $this->hasOne(Revisi::class, 'kak_id', 'kak_id');
    }
}
