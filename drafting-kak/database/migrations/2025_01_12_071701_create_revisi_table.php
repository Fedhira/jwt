<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('revisi', function (Blueprint $table) {
            $table->id('revisi_id'); // Primary Key
            $table->unsignedBigInteger('user_id'); // Kolom untuk foreign key
            $table->unsignedBigInteger('kak_id'); // Kolom untuk foreign key
            $table->unsignedBigInteger('kategori_id'); // Kolom untuk foreign key
            $table->text('alasan_penolakan');
            $table->text('saran');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('revisi');
    }
};
