<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kak', function (Blueprint $table) {
            $table->id('kak_id'); // Primary Key
            $table->unsignedBigInteger('user_id'); // Foreign Key untuk User
            $table->unsignedBigInteger('kategori_id'); // Foreign Key untuk Kategori Program
            $table->string('no_doc_mak', 100)->nullable();
            $table->string('judul', 255);
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'draft']);
            $table->text('indikator')->nullable();
            $table->text('satuan_ukur')->nullable();
            $table->text('volume')->nullable();
            $table->text('latar_belakang')->nullable();
            $table->text('dasar_hukum')->nullable();
            $table->text('gambaran_umum')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('target_sasaran')->nullable();
            $table->text('unit_kerja')->nullable();
            $table->text('ruang_lingkup')->nullable();
            $table->text('produk_jasa_dihasilkan')->nullable();
            $table->text('waktu_pelaksanaan')->nullable();
            $table->text('tenaga_ahli_terampil')->nullable();
            $table->text('peralatan')->nullable();
            $table->text('metode_kerja')->nullable();
            $table->text('manajemen_resiko')->nullable();
            $table->text('laporan_pengajuan_pekerjaan')->nullable();
            $table->text('sumber_dana_prakiraan_biaya')->nullable();
            $table->text('penutup')->nullable();
            $table->string('lampiran', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kak');
    }
};
