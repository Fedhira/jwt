<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tambahkan foreign key untuk tabel `users`
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->nullable()->after('id'); // Tambahkan kolom jika belum ada
            }

            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_program')
                ->onDelete('set null'); // Jika kategori dihapus, set null
        });

        // Tambahkan foreign key untuk tabel `kak`
        Schema::table('kak', function (Blueprint $table) {
            if (!Schema::hasColumn('kak', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('kak_id'); // Tambahkan kolom jika belum ada
            }
            if (!Schema::hasColumn('kak', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->after('user_id'); // Tambahkan kolom jika belum ada
            }

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Hapus KAK jika user dihapus

            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_program')
                ->onDelete('cascade'); // Hapus KAK jika kategori dihapus
        });

        // Tambahkan foreign key untuk tabel `revisi`
        Schema::table('revisi', function (Blueprint $table) {
            if (!Schema::hasColumn('revisi', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id'); // Tambahkan kolom jika belum ada
            }
            if (!Schema::hasColumn('revisi', 'kak_id')) {
                $table->unsignedBigInteger('kak_id')->after('user_id'); // Tambahkan kolom jika belum ada
            }
            if (!Schema::hasColumn('revisi', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->after('kak_id'); // Tambahkan kolom jika belum ada
            }

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Hapus revisi jika user dihapus

            $table->foreign('kak_id')
                ->references('kak_id')
                ->on('kak')
                ->onDelete('cascade'); // Hapus revisi jika KAK dihapus

            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_program')
                ->onDelete('cascade'); // Hapus revisi jika kategori dihapus
        });
    }

    public function down()
    {
        // Hapus foreign key untuk tabel `users`
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });

        // Hapus foreign key untuk tabel `kak`
        Schema::table('kak', function (Blueprint $table) {
            if (Schema::hasColumn('kak', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('kak', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });

        // Hapus foreign key untuk tabel `revisi`
        Schema::table('revisi', function (Blueprint $table) {
            if (Schema::hasColumn('revisi', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('revisi', 'kak_id')) {
                $table->dropForeign(['kak_id']);
                $table->dropColumn('kak_id');
            }
            if (Schema::hasColumn('revisi', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });
    }
};
