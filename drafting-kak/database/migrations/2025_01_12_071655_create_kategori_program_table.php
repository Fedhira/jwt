<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriProgramTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_program', function (Blueprint $table) {
            $table->id(); // unsignedBigInteger
            $table->string('nama_divisi', 100);
            $table->enum('status', ['plt', 'definitif']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_program');
    }
}
