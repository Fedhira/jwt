<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('kategori_id')->nullable(); // Kolom untuk foreign key
            $table->string('username', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('nik', 17)->nullable();
            $table->enum('role', ['admin', 'staff', 'supervisor'])->default('staff');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
