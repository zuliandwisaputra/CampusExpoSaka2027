<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel inti Campus Expo sesuai bagian "Struktur data" di PRD.
 * Anggota adalah pusat datanya: setiap anggota menunjuk ke satu kampus dan satu divisi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('kampus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('singkatan', 30)->nullable();
            $table->string('jenis', 20)->index(); // ptn, pts, politeknik, kedinasan
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable()->index();
            $table->string('logo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('jalur_masuk')->nullable();
            $table->text('kisaran_biaya')->nullable();
            $table->text('beasiswa')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram', 60)->nullable();
            $table->timestamps();
        });

        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampus_id')->constrained('kampus')->cascadeOnDelete();
            $table->string('nama');
            $table->string('jenjang', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->foreignId('kampus_id')->constrained('kampus')->restrictOnDelete();
            $table->foreignId('divisi_id')->constrained('divisi')->restrictOnDelete();
            $table->string('jurusan')->nullable();
            $table->string('angkatan', 10)->nullable();
            $table->string('jabatan', 80)->default('Anggota');
            $table->string('instagram', 60)->nullable();
            $table->string('linkedin')->nullable();
            $table->string('no_hp', 30)->nullable(); // hanya terlihat admin
            $table->boolean('ditampilkan')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->foreignId('kampus_id')->nullable()->constrained('kampus')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->boolean('acara_utama')->default(false);
            $table->timestamps();
        });

        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('isi');
            $table->string('status', 10)->default('draf')->index(); // draf, terbit
            $table->boolean('disematkan')->default(false);
            $table->dateTime('terbit_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('album', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });

        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('album')->cascadeOnDelete();
            $table->string('file');
            $table->string('thumb')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->text('nilai')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('koordinator')->after('password'); // super_admin, koordinator
            $table->foreignId('divisi_id')->nullable()->after('role')->constrained('divisi')->nullOnDelete();
            $table->boolean('aktif')->default(true)->after('divisi_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('divisi_id');
            $table->dropColumn(['role', 'aktif']);
        });

        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('faq');
        Schema::dropIfExists('foto');
        Schema::dropIfExists('album');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('anggota');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('kampus');
        Schema::dropIfExists('divisi');
    }
};
