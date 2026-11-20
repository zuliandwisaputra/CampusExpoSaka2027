<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Foto;
use App\Models\Jadwal;
use App\Models\Kampus;
use App\Models\User;
use Database\Seeders\KontenAwalSeeder;
use Database\Seeders\PanitiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAksesTest extends TestCase
{
    use RefreshDatabase;

    private User $super;

    private User $koordinatorAcara;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed([PanitiaSeeder::class, KontenAwalSeeder::class]);

        $this->super = User::create([
            'name' => 'Super', 'email' => 'super@test.id', 'password' => 'rahasia123', 'role' => 'super_admin',
        ]);
        $this->koordinatorAcara = User::create([
            'name' => 'Nayla', 'email' => 'acara@test.id', 'password' => 'rahasia123', 'role' => 'koordinator',
            'divisi_id' => Divisi::where('slug', 'acara')->value('id'),
        ]);
    }

    private function anggotaDivisi(string $slug): Anggota
    {
        return Anggota::whereHas('divisi', fn ($q) => $q->where('slug', $slug))->firstOrFail();
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/anggota')->assertRedirect('/admin/login');
    }

    public function test_login_berhasil_dan_gagal(): void
    {
        $this->post('/admin/login', ['email' => 'super@test.id', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/admin/login', ['email' => 'super@test.id', 'password' => 'rahasia123'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->super);
    }

    public function test_percobaan_login_dibatasi(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'super@test.id', 'password' => 'salah']);
        }

        $this->post('/admin/login', ['email' => 'super@test.id', 'password' => 'rahasia123'])
            ->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_akun_nonaktif_tidak_bisa_login_dan_langsung_keluar(): void
    {
        $this->koordinatorAcara->update(['aktif' => false]);
        $this->post('/admin/login', ['email' => 'acara@test.id', 'password' => 'rahasia123']);
        $this->assertGuest();

        $this->koordinatorAcara->update(['aktif' => true]);
        $this->actingAs($this->koordinatorAcara)->get('/admin')->assertOk();
        $this->koordinatorAcara->update(['aktif' => false]);
        $this->actingAs($this->koordinatorAcara)->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_semua_halaman_admin_terbuka_untuk_super_admin(): void
    {
        $this->actingAs($this->super);
        $kampus = Kampus::first();
        $anggota = Anggota::first();

        foreach (['/admin', '/admin/anggota', '/admin/anggota/create', "/admin/anggota/{$anggota->id}/edit", '/admin/kampus',
            '/admin/kampus/create', "/admin/kampus/{$kampus->slug}", "/admin/kampus/{$kampus->slug}/edit", '/admin/divisi',
            '/admin/divisi/create', '/admin/divisi/acara/edit', '/admin/jadwal', '/admin/jadwal/create', '/admin/pengumuman',
            '/admin/pengumuman/create', '/admin/galeri', '/admin/galeri/create', '/admin/galeri/persiapan-panitia/edit',
            '/admin/faq', '/admin/faq/create', '/admin/pengaturan', '/admin/akun', '/admin/akun/create'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_koordinator_hanya_mengelola_anggota_divisinya(): void
    {
        $this->actingAs($this->koordinatorAcara);
        $acara = $this->anggotaDivisi('acara');
        $logistik = $this->anggotaDivisi('logistik');

        $this->get('/admin/anggota')->assertSee($acara->nama)->assertDontSee($logistik->nama);
        $this->get("/admin/anggota/{$acara->id}/edit")->assertOk();
        $this->get("/admin/anggota/{$logistik->id}/edit")->assertForbidden();
        $this->put("/admin/anggota/{$logistik->id}", ['nama' => 'Diubah'])->assertForbidden();
        $this->delete("/admin/anggota/{$logistik->id}")->assertForbidden();
        $this->assertDatabaseHas('anggota', ['id' => $logistik->id]);
    }

    public function test_anggota_baru_dari_koordinator_selalu_masuk_divisinya(): void
    {
        $logistikId = Divisi::where('slug', 'logistik')->value('id');

        $this->actingAs($this->koordinatorAcara)->post('/admin/anggota', [
            'nama' => 'Anggota Baru', 'kampus_id' => Kampus::first()->id, 'divisi_id' => $logistikId,
            'jabatan' => 'Anggota', 'ditampilkan' => '1',
        ])->assertRedirect('/admin/anggota');

        $this->assertSame($this->koordinatorAcara->divisi_id, Anggota::where('nama', 'Anggota Baru')->value('divisi_id'));
    }

    public function test_batasan_koordinator_sesuai_tabel_hak_akses(): void
    {
        $this->actingAs($this->koordinatorAcara);
        $jadwal = Jadwal::create(['judul' => 'Pembukaan', 'waktu_mulai' => '08:00']);

        // Tidak ada akses ke akun admin dan pengaturan acara.
        $this->get('/admin/akun')->assertForbidden();
        $this->get('/admin/pengaturan')->assertForbidden();

        // Kampus: lihat saja.
        $this->get('/admin/kampus')->assertOk();
        $this->get('/admin/kampus/create')->assertForbidden();
        $this->delete('/admin/kampus/'.Kampus::first()->slug)->assertForbidden();

        // Divisi: hanya deskripsi divisinya sendiri.
        $this->get('/admin/divisi/logistik/edit')->assertForbidden();
        $this->put('/admin/divisi/acara', ['deskripsi' => 'Deskripsi baru', 'nama' => 'Ganti nama'])->assertRedirect();
        $this->assertDatabaseHas('divisi', ['slug' => 'acara', 'deskripsi' => 'Deskripsi baru', 'nama' => 'Divisi Acara']);

        // Jadwal/pengumuman/galeri/FAQ: tambah dan ubah boleh, hapus tidak.
        $this->post('/admin/jadwal', ['judul' => 'Sesi tanya jawab', 'waktu_mulai' => '09:00'])->assertRedirect();
        $this->put("/admin/jadwal/{$jadwal->id}", ['judul' => 'Pembukaan acara', 'waktu_mulai' => '08:00'])->assertRedirect();
        $this->delete("/admin/jadwal/{$jadwal->id}")->assertForbidden();
        $this->post('/admin/faq', ['pertanyaan' => 'Tanya?', 'jawaban' => 'Jawab.', 'urutan' => 9])->assertRedirect();
        $this->delete('/admin/galeri/persiapan-panitia')->assertForbidden();
    }

    public function test_validasi_instagram_linkedin_dan_foto(): void
    {
        $this->actingAs($this->super);
        $dasar = ['nama' => 'Uji', 'kampus_id' => Kampus::first()->id, 'divisi_id' => Divisi::first()->id, 'jabatan' => 'Anggota'];

        $this->post('/admin/anggota', [...$dasar, 'instagram' => '@uji'])->assertSessionHasErrors('instagram');
        $this->post('/admin/anggota', [...$dasar, 'instagram' => 'https://instagram.com/uji'])->assertSessionHasErrors('instagram');
        $this->post('/admin/anggota', [...$dasar, 'linkedin' => 'https://linkedin.com/in/uji'])->assertSessionHasErrors('linkedin');
        $this->post('/admin/anggota', [...$dasar, 'foto' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf')])->assertSessionHasErrors('foto');
        $this->post('/admin/anggota', [...$dasar, 'foto' => UploadedFile::fake()->image('besar.jpg')->size(8000)])->assertSessionHasErrors('foto');
        $this->post('/admin/anggota', ['nama' => ''])->assertSessionHasErrors(['nama', 'kampus_id', 'divisi_id']);
    }

    public function test_foto_sampai_7_mb_diterima(): void
    {
        Storage::fake('public');
        $this->actingAs($this->super);

        $this->post('/admin/anggota', [
            'nama' => 'Foto Besar', 'kampus_id' => Kampus::first()->id, 'divisi_id' => Divisi::first()->id,
            'jabatan' => 'Anggota', 'foto' => UploadedFile::fake()->image('besar.jpg', 3000, 3000)->size(6500),
        ])->assertSessionHasNoErrors()->assertRedirect('/admin/anggota');

        $this->assertNotNull(Anggota::where('nama', 'Foto Besar')->value('foto'));
    }

    public function test_lokasi_peta_bisa_diatur_super_admin(): void
    {
        $this->actingAs($this->super)->put('/admin/pengaturan', [
            'nama_acara' => 'Campus Expo', 'tanggal_acara' => '2027-01-18', 'lokasi' => 'SMA Negeri 1 Kandanghaur',
            'peta_lokasi' => '-6.123456, 108.123456',
        ])->assertSessionHasNoErrors();

        $this->get('/')->assertSee('destination=-6.123456%2C%20108.123456', false);
    }

    public function test_foto_diunggah_dikecilkan_dan_disimpan_sebagai_webp(): void
    {
        Storage::fake('public');
        $this->actingAs($this->super);

        $this->post('/admin/anggota', [
            'nama' => 'Berfoto', 'kampus_id' => Kampus::first()->id, 'divisi_id' => Divisi::first()->id,
            'jabatan' => 'Anggota', 'instagram' => 'berfoto_123', 'linkedin' => 'https://www.linkedin.com/in/berfoto',
            'foto' => UploadedFile::fake()->image('foto.jpg', 1600, 1200),
        ])->assertRedirect('/admin/anggota');

        $path = Anggota::where('nama', 'Berfoto')->value('foto');
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);

        [$w, $h] = getimagesizefromstring(Storage::disk('public')->get($path));
        $this->assertLessThanOrEqual(600, $w);
        $this->assertLessThanOrEqual(600, $h);
    }

    public function test_upload_banyak_foto_galeri_membuat_thumbnail(): void
    {
        Storage::fake('public');
        $this->actingAs($this->koordinatorAcara);

        $this->post('/admin/galeri/persiapan-panitia/foto', [
            'foto' => [UploadedFile::fake()->image('a.jpg', 2000, 1500), UploadedFile::fake()->image('b.png', 800, 600)],
        ])->assertRedirect();

        $foto = Foto::whereNotNull('thumb')->get();
        $this->assertCount(2, $foto);
        foreach ($foto as $f) {
            Storage::disk('public')->assertExists([$f->file, $f->thumb]);
        }
    }

    public function test_super_admin_tidak_bisa_mengunci_dirinya_sendiri(): void
    {
        $this->actingAs($this->super);

        $this->put("/admin/akun/{$this->super->id}", [
            'name' => 'Super', 'email' => 'super@test.id', 'role' => 'koordinator', 'divisi_id' => Divisi::first()->id, 'aktif' => '1',
        ])->assertSessionHasErrors('role');
        $this->delete("/admin/akun/{$this->super->id}")->assertSessionHasErrors('hapus');
        $this->assertTrue($this->super->fresh()->isSuperAdmin());
    }

    public function test_super_admin_membuat_dan_menonaktifkan_koordinator(): void
    {
        $this->actingAs($this->super)->post('/admin/akun', [
            'name' => 'Koordinator Logistik', 'email' => 'logistik@test.id', 'role' => 'koordinator',
            'divisi_id' => Divisi::where('slug', 'logistik')->value('id'), 'password' => 'Logistik2027', 'password_confirmation' => 'Logistik2027', 'aktif' => '1',
        ])->assertRedirect('/admin/akun');

        $akun = User::where('email', 'logistik@test.id')->first();
        $this->assertTrue($akun->isKoordinator());

        $this->delete("/admin/akun/{$akun->id}")->assertRedirect('/admin/akun');
        $this->assertFalse($akun->fresh()->aktif);
    }

    public function test_kampus_yang_masih_punya_anggota_tidak_bisa_dihapus(): void
    {
        $this->actingAs($this->super);
        $kampus = Kampus::has('anggota')->first();

        $this->delete("/admin/kampus/{$kampus->slug}")->assertSessionHasErrors('hapus');
        $this->assertDatabaseHas('kampus', ['id' => $kampus->id]);
    }

    public function test_jurusan_disimpan_dari_teks_per_baris(): void
    {
        $this->actingAs($this->super);
        $kampus = Kampus::where('singkatan', 'Polindra')->first();

        $this->put("/admin/kampus/{$kampus->slug}", [
            'nama' => $kampus->nama, 'jenis' => 'politeknik',
            'jurusan' => "D3 | Teknik Informatika\nD4 | Rekayasa Perangkat Lunak\nTeknik Mesin",
        ])->assertRedirect('/admin/kampus');

        $this->assertSame(3, $kampus->jurusan()->count());
        $this->assertDatabaseHas('jurusan', ['kampus_id' => $kampus->id, 'jenjang' => 'D4', 'nama' => 'Rekayasa Perangkat Lunak']);

        $this->get('/')->assertSee('Rekayasa Perangkat Lunak');
    }
}
